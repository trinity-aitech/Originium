-- Originium — instalação/reinstalação COMPLETA e segura
-- Pode reimportar quantas vezes quiser: apaga tudo e recria do zero.

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS contact_messages, contact_fields, coupons, gallery_images, timeline_events, profile_faqs, testimonials, profile_views, clicks, links, users, themes;
SET FOREIGN_KEY_CHECKS=1;

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

-- Originium — temas de fábrica (paleta fria, glassmorphism discreto)
-- Execute depois de schema.sql.

INSERT INTO themes (name, slug, bg_class, card_class, text_class, accent_class, is_default) VALUES
('Glacier',  'glacier',
    'bg-gradient-to-b from-slate-900 via-slate-950 to-black',
    'bg-white/5 border border-white/10 hover:bg-white/10 backdrop-blur',
    'text-slate-100', 'text-sky-300', 1),

('Midnight', 'midnight',
    'bg-gradient-to-b from-[#0b1020] via-[#0a0e1a] to-black',
    'bg-indigo-500/10 border border-indigo-400/20 hover:bg-indigo-500/20 backdrop-blur',
    'text-indigo-50', 'text-indigo-300', 0),

('Aurora',   'aurora',
    'bg-gradient-to-b from-teal-950 via-slate-950 to-black',
    'bg-teal-400/10 border border-teal-300/20 hover:bg-teal-400/20 backdrop-blur',
    'text-teal-50', 'text-teal-300', 0),

('Graphite', 'graphite',
    'bg-neutral-950',
    'bg-neutral-900 border border-neutral-800 hover:border-neutral-700',
    'text-neutral-100', 'text-zinc-400', 0),

('Frost',    'frost',
    'bg-gradient-to-b from-sky-50 to-slate-100',
    'bg-white/70 border border-white hover:bg-white backdrop-blur shadow-sm',
    'text-slate-800', 'text-sky-600', 0),

('Arctic',   'arctic',
    'bg-gradient-to-b from-zinc-100 to-white',
    'bg-white border border-zinc-200 hover:border-sky-300 shadow-sm',
    'text-zinc-900', 'text-sky-600', 0);

-- Originium Sprint — migração 002
-- Personalização (hex/animações), Blueprint profissional e blocos de conteúdo.
SET NAMES utf8mb4;

-- ── Personalização + Blueprint no usuário ────────────────────────────
ALTER TABLE users
    ADD COLUMN headline          VARCHAR(120) NULL AFTER display_name,
    ADD COLUMN accent_color      VARCHAR(7)   NULL AFTER theme_id,
    ADD COLUMN bg_animation      VARCHAR(20)  NOT NULL DEFAULT 'none' AFTER accent_color,
    ADD COLUMN contact_enabled   TINYINT(1)   NOT NULL DEFAULT 0 AFTER bg_animation,
    ADD COLUMN bp_values         TEXT NULL,
    ADD COLUMN bp_work_method    TEXT NULL,
    ADD COLUMN bp_availability   VARCHAR(120) NULL,
    ADD COLUMN bp_working_hours  VARCHAR(120) NULL,
    ADD COLUMN bp_contact_prefs  TEXT NULL,
    ADD COLUMN bp_current_focus  VARCHAR(200) NULL,
    ADD COLUMN bp_project_status VARCHAR(40)  NULL,
    ADD COLUMN bp_client_compat  TEXT NULL,
    ADD COLUMN bp_expectations   TEXT NULL;

-- ── Paleta hex dos temas (overhaul de legibilidade) ──────────────────
ALTER TABLE themes
    ADD COLUMN is_dark      TINYINT(1)  NOT NULL DEFAULT 1,
    ADD COLUMN bg_from      VARCHAR(7)  NULL,
    ADD COLUMN bg_to        VARCHAR(7)  NULL,
    ADD COLUMN surface      VARCHAR(32) NULL,
    ADD COLUMN surface_hover VARCHAR(32) NULL,
    ADD COLUMN border_color VARCHAR(32) NULL,
    ADD COLUMN text_color   VARCHAR(7)  NULL,
    ADD COLUMN text_muted   VARCHAR(7)  NULL,
    ADD COLUMN accent       VARCHAR(7)  NULL;

-- ── Depoimentos / recomendações ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS testimonials (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id     BIGINT UNSIGNED NOT NULL,
    author_name VARCHAR(80)  NOT NULL,
    author_role VARCHAR(120) NULL,
    quote       TEXT NOT NULL,
    position    INT NOT NULL DEFAULT 0,
    is_active   TINYINT(1) NOT NULL DEFAULT 1,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_testimonials_user (user_id, position),
    CONSTRAINT fk_testimonials_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── FAQ do perfil ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS profile_faqs (
    id        BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id   BIGINT UNSIGNED NOT NULL,
    question  VARCHAR(200) NOT NULL,
    answer    TEXT NOT NULL,
    position  INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_faqs_user (user_id, position),
    CONSTRAINT fk_faqs_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Linha do tempo ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS timeline_events (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id     BIGINT UNSIGNED NOT NULL,
    period      VARCHAR(40)  NOT NULL,
    title       VARCHAR(120) NOT NULL,
    description TEXT NULL,
    position    INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_timeline_user (user_id, position),
    CONSTRAINT fk_timeline_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Galeria (slideshow) ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS gallery_images (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id    BIGINT UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    caption    VARCHAR(160) NULL,
    position   INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_gallery_user (user_id, position),
    CONSTRAINT fk_gallery_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Cupons de desconto ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS coupons (
    id             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id        BIGINT UNSIGNED NOT NULL,
    code           VARCHAR(40)  NOT NULL,
    description    VARCHAR(160) NULL,
    discount_label VARCHAR(40)  NULL,
    url            VARCHAR(2048) NULL,
    expires_at     DATE NULL,
    is_active      TINYINT(1) NOT NULL DEFAULT 1,
    position       INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_coupons_user (user_id, position),
    CONSTRAINT fk_coupons_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Formulário de contato: campos customizáveis ──────────────────────
CREATE TABLE IF NOT EXISTS contact_fields (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id     BIGINT UNSIGNED NOT NULL,
    label       VARCHAR(80) NOT NULL,
    field_type  VARCHAR(20) NOT NULL DEFAULT 'text',
    placeholder VARCHAR(120) NULL,
    is_required TINYINT(1) NOT NULL DEFAULT 0,
    position    INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_contact_fields_user (user_id, position),
    CONSTRAINT fk_contact_fields_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Mensagens recebidas pelo formulário ──────────────────────────────
CREATE TABLE IF NOT EXISTS contact_messages (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id    BIGINT UNSIGNED NOT NULL,
    payload    TEXT NOT NULL,
    ip_hash    CHAR(64) NULL,
    is_read    TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_messages_user (user_id, created_at),
    CONSTRAINT fk_messages_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Overhaul de paleta dos 6 temas (legibilidade, sem neon/roxo) ─────
UPDATE themes SET is_dark=1, bg_from='#0b1220', bg_to='#05080f', surface='rgba(255,255,255,0.05)', surface_hover='rgba(255,255,255,0.09)', border_color='rgba(255,255,255,0.10)', text_color='#eef2f8', text_muted='#9aa6b8', accent='#6ea8d8' WHERE slug='glacier';
UPDATE themes SET is_dark=1, bg_from='#0a0f1e', bg_to='#04060d', surface='rgba(255,255,255,0.05)', surface_hover='rgba(255,255,255,0.09)', border_color='rgba(255,255,255,0.10)', text_color='#eef1f8', text_muted='#97a0b5', accent='#6f9fd0' WHERE slug='midnight';
UPDATE themes SET is_dark=1, bg_from='#08140f', bg_to='#04090a', surface='rgba(255,255,255,0.05)', surface_hover='rgba(255,255,255,0.09)', border_color='rgba(255,255,255,0.10)', text_color='#eaf4ef', text_muted='#93a8a0', accent='#54b9a6' WHERE slug='aurora';
UPDATE themes SET is_dark=1, bg_from='#161616', bg_to='#0a0a0a', surface='rgba(255,255,255,0.05)', surface_hover='rgba(255,255,255,0.09)', border_color='rgba(255,255,255,0.10)', text_color='#f2f2f2', text_muted='#a1a1aa', accent='#c89b6a' WHERE slug='graphite';
UPDATE themes SET is_dark=0, bg_from='#f5f8fc', bg_to='#e9eff6', surface='#ffffff', surface_hover='#f4f7fb', border_color='rgba(15,23,42,0.10)', text_color='#1f2937', text_muted='#5b6573', accent='#2f6fb0' WHERE slug='frost';
UPDATE themes SET is_dark=0, bg_from='#fcfcfd', bg_to='#eef1f6', surface='#ffffff', surface_hover='#f3f5f9', border_color='rgba(15,23,42,0.10)', text_color='#18181b', text_muted='#646b78', accent='#2563a8' WHERE slug='arctic';

-- Originium — migração 003
-- Bordas mais visíveis nos cards do perfil público (melhor divisão das seções).
SET NAMES utf8mb4;

UPDATE themes SET border_color = 'rgba(255,255,255,0.18)', surface_hover = 'rgba(255,255,255,0.10)' WHERE is_dark = 1;
UPDATE themes SET border_color = 'rgba(15,23,42,0.16)',   surface_hover = '#eef2f8'                  WHERE is_dark = 0;

-- Originium — migração 004
-- Opção de exibir o QR Code do link no perfil público.
SET NAMES utf8mb4;

ALTER TABLE users
    ADD COLUMN show_qr TINYINT(1) NOT NULL DEFAULT 0 AFTER contact_enabled;
