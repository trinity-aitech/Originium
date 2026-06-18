-- Originium — migração 004
-- Opção de exibir o QR Code do link no perfil público.
SET NAMES utf8mb4;

ALTER TABLE users
    ADD COLUMN show_qr TINYINT(1) NOT NULL DEFAULT 0 AFTER contact_enabled;
