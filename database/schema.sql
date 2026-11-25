-- =====================================================================
--  AI Tools Hub — MySQL 5.7+ / MariaDB 10.3+ schema
--  Import:  mysql -u root -p < database/schema.sql
--           mysql -u root -p ai_tool_hub < database/seed.sql
--  (or use phpMyAdmin → Import)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `ai_tool_hub`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `ai_tool_hub`;

SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- Tool categories (Image Tools, Calculators, ...)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug`       VARCHAR(40)  NOT NULL,
  `name`       VARCHAR(80)  NOT NULL,
  `icon`       VARCHAR(16)  NOT NULL DEFAULT '',
  `sort_order` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Tools (mirrors includes/tools-data.php; rows are also auto-created on first use)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tools` (
  `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id`       INT UNSIGNED NOT NULL,
  `slug`              VARCHAR(80)  NOT NULL,
  `name`              VARCHAR(120) NOT NULL,
  `short_description` VARCHAR(255) NOT NULL DEFAULT '',
  `icon`              VARCHAR(16)  NOT NULL DEFAULT '',
  `is_active`         TINYINT(1)   NOT NULL DEFAULT 1,
  `sort_order`        SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`        DATETIME     NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_tools_slug` (`slug`),
  KEY `idx_tools_category` (`category_id`),
  CONSTRAINT `fk_tools_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Anonymous daily usage counters (one row per tool per day)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tool_usage_daily` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tool_id`    INT UNSIGNED NOT NULL,
  `usage_date` DATE         NOT NULL,
  `views`      INT UNSIGNED NOT NULL DEFAULT 0,
  `uses`       INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usage_tool_day` (`tool_id`, `usage_date`),
  KEY `idx_usage_date` (`usage_date`),
  CONSTRAINT `fk_usage_tool` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Thumbs up / down feedback (1 = helpful, 0 = not helpful)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tool_feedback` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tool_id`    INT UNSIGNED NOT NULL,
  `rating`     TINYINT(1)   NOT NULL,
  `ip_hash`    CHAR(64)     NOT NULL,
  `created_at` DATETIME     NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_feedback_tool_date` (`tool_id`, `created_at`),
  KEY `idx_feedback_dedupe` (`tool_id`, `ip_hash`, `created_at`),
  CONSTRAINT `fk_feedback_tool` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Contact form messages
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) NOT NULL,
  `email`      VARCHAR(190) NOT NULL,
  `subject`    VARCHAR(100) NOT NULL DEFAULT 'General question',
  `message`    TEXT         NOT NULL,
  `ip_hash`    CHAR(64)     NOT NULL,
  `user_agent` VARCHAR(255) NOT NULL DEFAULT '',
  `is_read`    TINYINT(1)   NOT NULL DEFAULT 0,
  `created_at` DATETIME     NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_contact_read_date` (`is_read`, `created_at`),
  KEY `idx_contact_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Newsletter subscribers
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email`           VARCHAR(190) NOT NULL,
  `token`           CHAR(32)     NOT NULL,
  `is_active`       TINYINT(1)   NOT NULL DEFAULT 1,
  `subscribed_at`   DATETIME     NOT NULL,
  `unsubscribed_at` DATETIME     NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_subscribers_email` (`email`),
  KEY `idx_subscribers_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Admin users (create with: php scripts/create-admin.php <username>)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`      VARCHAR(50)  NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `last_login_at` DATETIME     NULL DEFAULT NULL,
  `created_at`    DATETIME     NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_admin_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Rate limiting (spam protection for forms / admin login)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rate_limits` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `bucket`     VARCHAR(40) NOT NULL,
  `ip_hash`    CHAR(64)    NOT NULL,
  `created_at` DATETIME    NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rate_lookup` (`bucket`, `ip_hash`, `created_at`),
  KEY `idx_rate_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Reporting view used by the admin dashboard
-- ---------------------------------------------------------------------
CREATE OR REPLACE VIEW `v_tool_stats` AS
SELECT
  t.`id`,
  t.`slug`,
  t.`name`,
  c.`name` AS `category`,
  COALESCE(SUM(u.`views`), 0) AS `total_views`,
  COALESCE(SUM(u.`uses`), 0)  AS `total_uses`,
  (SELECT COUNT(*) FROM `tool_feedback` f WHERE f.`tool_id` = t.`id` AND f.`rating` = 1) AS `likes`,
  (SELECT COUNT(*) FROM `tool_feedback` f WHERE f.`tool_id` = t.`id` AND f.`rating` = 0) AS `dislikes`
FROM `tools` t
JOIN `categories` c ON c.`id` = t.`category_id`
LEFT JOIN `tool_usage_daily` u ON u.`tool_id` = t.`id`
GROUP BY t.`id`, t.`slug`, t.`name`, c.`name`;
