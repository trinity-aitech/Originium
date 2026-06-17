-- Originium — migração 003
-- Bordas mais visíveis nos cards do perfil público (melhor divisão das seções).
SET NAMES utf8mb4;

UPDATE themes SET border_color = 'rgba(255,255,255,0.18)', surface_hover = 'rgba(255,255,255,0.10)' WHERE is_dark = 1;
UPDATE themes SET border_color = 'rgba(15,23,42,0.16)',   surface_hover = '#eef2f8'                  WHERE is_dark = 0;
