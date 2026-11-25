<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';
$toolSlug = 'word-counter';
require INCLUDES_PATH . '/tool-start.php';
?>
    <label for="textInput">Type or paste your text</label>
    <textarea id="textInput" rows="9" placeholder="Start typing…"></textarea>
    <div class="btn-row">
      <button id="clearBtn" class="btn btn-ghost btn-sm" type="button">Clear</button>
      <button id="copyBtn" class="btn btn-secondary btn-sm" type="button">Copy text</button>
    </div>
    <div class="stat-grid" id="stats">
      <div class="stat"><b data-s="words">0</b><span>Words</span></div>
      <div class="stat"><b data-s="chars">0</b><span>Characters</span></div>
      <div class="stat"><b data-s="charsNoSpace">0</b><span>Chars (no spaces)</span></div>
      <div class="stat"><b data-s="sentences">0</b><span>Sentences</span></div>
      <div class="stat"><b data-s="paragraphs">0</b><span>Paragraphs</span></div>
      <div class="stat"><b data-s="reading">0 min</b><span>Reading time</span></div>
      <div class="stat"><b data-s="speaking">0 min</b><span>Speaking time</span></div>
    </div>
    <div id="keywordsBox" class="hidden"><h3 style="margin:.5rem 0">Top keywords</h3><div class="chip-row" id="keywords" style="flex-wrap:wrap"></div></div>
<?php require INCLUDES_PATH . '/tool-end.php'; ?>
