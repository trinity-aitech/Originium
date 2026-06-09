# 🔗 Originium

Plataforma estilo Linktree em **PHP 8+ puro**, **MySQL** e **TailwindCSS** (sem Node, sem framework JS).
Arquitetura **MVC simples**, pronta para **hospedagem compartilhada**.

> Status: 🏗️ em desenvolvimento (arquitetura definida, implementação em andamento).

## ✨ Funcionalidades
- Cadastro / Login seguro (bcrypt, CSRF, sessão endurecida)
- Perfil público em `/u/{username}`
- Links personalizáveis (CRUD, reordenação, ativar/desativar)
- Dashboard
- Temas (paleta fria, glassmorphism discreto)
- Analytics básicos (cliques por link, views de perfil)

## 🎨 Design
Inspirado em Apple (iOS/macOS): glassmorphism discreto, tons frios, minimalista premium.

## 🧱 Stack
PHP 8+ · MySQL (utf8mb4/InnoDB) · TailwindCSS (CDN) · MVC próprio · sem Node.js

## 📁 Estrutura
```
public/        → document root (index.php, .htaccess, assets)
app/Core/      → infra (Router, Database, Auth, View, Csrf...)
app/Controllers, Models, Views, Middleware
config/        → app, database, routes
database/      → schema.sql, seed.sql
storage/logs/  → logs (fora da web)
```

## 🚀 Como rodar (local / XAMPP)
1. Clone dentro de `htdocs`.
2. `cp .env.example .env` e preencha as credenciais do MySQL.
3. Importe `database/schema.sql` (e `seed.sql`) no phpMyAdmin.
4. Acesse `http://localhost/Linktree%20da%20Temu/public`.

## 🔒 Segurança
PDO + prepared statements · `password_hash` · tokens CSRF · escape de saída (anti-XSS) · IP anonimizado (hash) nos analytics.

---
🤖 Desenvolvido com [Claude Code](https://claude.com/claude-code)
