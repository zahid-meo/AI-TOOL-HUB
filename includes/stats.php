<?php
/**
 * Database-backed helpers: tool IDs, usage counters, popular tools, dashboard stats.
 * Every function degrades gracefully when the database is unavailable.
 */
declare(strict_types=1);

function ensure_category_id(string $slug): ?int
{
    $cats = tool_categories();
    if (!isset($cats[$slug])) {
        return null;
    }
    $id = db_value('SELECT id FROM categories WHERE slug = ?', [$slug]);
    if ($id) {
        return (int) $id;
    }
    db_run('INSERT INTO categories (slug, name, icon, sort_order) VALUES (?, ?, ?, ?)',
        [$slug, $cats[$slug]['name'], $cats[$slug]['icon'], $cats[$slug]['sort']]);
    $id = db_value('SELECT id FROM categories WHERE slug = ?', [$slug]);
    return $id ? (int) $id : null;
}

/** Returns the DB id for a tool slug, creating the row from the registry on first use. */
function ensure_tool_id(string $slug): ?int
{
    $tool = tool_get($slug);
    if (!$tool) {
        return null;
    }
    $id = db_value('SELECT id FROM tools WHERE slug = ?', [$slug]);
    if ($id) {
        return (int) $id;
    }
    $catId = ensure_category_id($tool['cat']);
    if (!$catId) {
        return null;
    }
    db_run('INSERT INTO tools (slug, category_id, name, short_description, icon, is_active, created_at) VALUES (?, ?, ?, ?, ?, 1, ?)',
        [$slug, $catId, $tool['name'], $tool['short'], $tool['icon'], now()]);
    $id = db_value('SELECT id FROM tools WHERE slug = ?', [$slug]);
    return $id ? (int) $id : null;
}

/** Increment today's counter. $type = 'view' | 'use'. */
function record_usage(string $slug, string $type): bool
{
    if (!db_available()) {
        return false;
    }
    $toolId = ensure_tool_id($slug);
    if (!$toolId) {
        return false;
    }
    $col = $type === 'use' ? 'uses' : 'views';
    $today = date('Y-m-d');
    $st = db_run("UPDATE tool_usage_daily SET $col = $col + 1 WHERE tool_id = ? AND usage_date = ?", [$toolId, $today]);
    if ($st && $st->rowCount() > 0) {
        return true;
    }
    $views = $type === 'use' ? 0 : 1;
    $uses  = $type === 'use' ? 1 : 0;
    $ins = db_run('INSERT INTO tool_usage_daily (tool_id, usage_date, views, uses) VALUES (?, ?, ?, ?)', [$toolId, $today, $views, $uses]);
    if (!$ins) {   // lost a race against a parallel insert → just update
        db_run("UPDATE tool_usage_daily SET $col = $col + 1 WHERE tool_id = ? AND usage_date = ?", [$toolId, $today]);
    }
    return true;
}

/** Slugs of the most viewed tools in the last 30 days; falls back to the registry's "popular" flags. */
function popular_tools(int $limit = 6): array
{
    $slugs = [];
    if (db_available()) {
        $rows = db_all(
            'SELECT t.slug, SUM(u.views + u.uses) AS score FROM tool_usage_daily u JOIN tools t ON t.id = u.tool_id
             WHERE u.usage_date >= ? GROUP BY t.slug ORDER BY score DESC LIMIT ' . (int) $limit,
            [date('Y-m-d', strtotime('-30 days'))]
        );
        foreach ($rows as $r) {
            if (tool_get($r['slug'])) {
                $slugs[] = $r['slug'];
            }
        }
    }
    if (count($slugs) < $limit) {
        foreach (tools_all() as $s => $t) {
            if ($t['popular'] && !in_array($s, $slugs, true)) {
                $slugs[] = $s;
            }
        }
    }
    return array_map('tool_get', array_slice($slugs, 0, $limit));
}
