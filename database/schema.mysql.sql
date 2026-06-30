-- Lachman Sons Drycleaners – MySQL schema (reference).
-- Use with DB_DRIVER=mysql. SQLite users can ignore this file; the app
-- auto-creates the SQLite database via database/init.php.

CREATE DATABASE IF NOT EXISTS ls_drycleaners
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ls_drycleaners;

CREATE TABLE IF NOT EXISTS services (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  slug        VARCHAR(80)  NOT NULL UNIQUE,
  title       VARCHAR(120) NOT NULL,
  description TEXT          NOT NULL,
  icon        VARCHAR(80)   NOT NULL,
  accent      VARCHAR(20)   NOT NULL DEFAULT '#0ea5e9',
  sort_order  INT           NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS features (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(120) NOT NULL,
  description TEXT          NOT NULL,
  icon        VARCHAR(80)   NOT NULL,
  accent      VARCHAR(20)   NOT NULL DEFAULT '#0ea5e9',
  sort_order  INT           NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS steps (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  step_no     VARCHAR(8)   NOT NULL,
  title       VARCHAR(120) NOT NULL,
  description TEXT          NOT NULL,
  icon        VARCHAR(80)   NOT NULL,
  sort_order  INT           NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS stats (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  label      VARCHAR(120) NOT NULL,
  value      INT          NOT NULL,
  suffix     VARCHAR(8)   NOT NULL DEFAULT '',
  sort_order INT          NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS testimonials (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  quote      TEXT          NOT NULL,
  author     VARCHAR(120)  NOT NULL,
  role       VARCHAR(120)  NOT NULL,
  rating     INT           NOT NULL DEFAULT 5,
  sort_order INT           NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pricing_categories (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  slug       VARCHAR(60)  NOT NULL UNIQUE,
  name       VARCHAR(80)  NOT NULL,
  icon       VARCHAR(80)  NOT NULL DEFAULT 'rupee',
  sort_order INT          NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pricing_items (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT          NOT NULL,
  name        VARCHAR(120) NOT NULL,
  detail      VARCHAR(190) NOT NULL DEFAULT '',
  price_from  INT          NOT NULL DEFAULT 0,
  sort_order  INT          NOT NULL DEFAULT 0,
  CONSTRAINT fk_pricing_cat FOREIGN KEY (category_id)
    REFERENCES pricing_categories (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pickup_requests (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(120)  NOT NULL,
  phone      VARCHAR(40)   NOT NULL,
  email      VARCHAR(190)  NOT NULL DEFAULT '',
  area       VARCHAR(160)  NOT NULL DEFAULT '',
  service    VARCHAR(120)  NOT NULL DEFAULT '',
  message    TEXT          NOT NULL,
  ip_address VARCHAR(64)   NOT NULL DEFAULT '',
  created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS newsletter_subscribers (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  email      VARCHAR(190) NOT NULL UNIQUE,
  created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
