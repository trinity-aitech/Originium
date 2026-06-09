-- Originium — esquema do banco de dados
-- MySQL 5.7+/8+ · utf8mb4 · InnoDB
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Temas (cadastrados de fábrica, ver seed.sql)
CREATE TABLE IF NOT EXISTS themes (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name        VARCHAR(40)  NOT NULL,
    slug        VARCHAR(40)  NOT NULL,
    bg_class    VARCHAR(255) NOT NULL,
    card_class  VARCHAR(255) NOT NULL,
    text_class  VARCHAR(255) NOT NULL,
    accent_class VARCHAR(255) NOT NULL DEFAULT '',
    is_default  TINYINT(1)   NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY uq_themes_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Usuários
CREATE TABLE IF NOT EXISTS users (
    id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    username      VARCHAR(30)  NOT NULL,
    email         VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    display_name  VARCHAR(60)  NOT NULL,
    bio           VARCHAR(160) NULL,
    avatar_path   VARCHAR(255) NULL,
    theme_id      BIGINT UNSIGNED NULL,
    is_active     TINYINT(1)   NOT NULL DEFAULT 1,
    created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_username (username),
    UNIQUE KEY uq_users_email (email),
    KEY idx_users_theme (theme_id),
    CONSTRAINT fk_users_theme FOREIGN KEY (theme_id) REFERENCES themes (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Links
CREATE TABLE IF NOT EXISTS links (
    id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id      BIGINT UNSIGNED NOT NULL,
    title        VARCHAR(80)   NOT NULL,
    url          VARCHAR(2048) NOT NULL,
    icon         VARCHAR(40)   NULL,
    position     INT           NOT NULL DEFAULT 0,
    is_active    TINYINT(1)    NOT NULL DEFAULT 1,
    clicks_count INT UNSIGNED  NOT NULL DEFAULT 0,
    created_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_links_user_position (user_id, position),
    CONSTRAINT fk_links_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cliques (analytics)
CREATE TABLE IF NOT EXISTS clicks (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    link_id    BIGINT UNSIGNED NOT NULL,
    user_id    BIGINT UNSIGNED NOT NULL,
    referrer   VARCHAR(255) NULL,
    user_agent VARCHAR(255) NULL,
    ip_hash    CHAR(64)     NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_clicks_user_date (user_id, created_at),
    KEY idx_clicks_link (link_id),
    CONSTRAINT fk_clicks_link FOREIGN KEY (link_id) REFERENCES links (id) ON DELETE CASCADE,
    CONSTRAINT fk_clicks_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Visualizações de perfil (analytics)
CREATE TABLE IF NOT EXISTS profile_views (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id    BIGINT UNSIGNED NOT NULL,
    ip_hash    CHAR(64)  NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_views_user_date (user_id, created_at),
    CONSTRAINT fk_views_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
