# 🔗 Originium

Plataforma estilo Linktree em **PHP 8+ puro**, **MySQL** e **TailwindCSS** (sem Node, sem framework JS).
Arquitetura **MVC simples**, pronta para **hospedagem compartilhada**.

> Visual dark premium inspirado em **Apple (iOS/macOS)** e **Launch UI**: glassmorphism discreto,
> tipografia grande, brilho "eclipse" em **tons frios** (sky/indigo) e estética minimalista.

## ✨ Funcionalidades
- Cadastro / Login seguro (bcrypt, CSRF, sessão endurecida, anti-XSS, PDO prepared statements)
- Perfil público em `/u/{username}`
- Links personalizáveis (CRUD, reordenar ▲▼, mostrar/ocultar, ícone emoji)
- Dashboard com visão geral
- 6 temas em tons frios (Glacier, Midnight, Aurora, Graphite, Frost, Arctic)
- Analytics básicos (cliques por link, cliques por dia, visitas ao perfil) com IP anonimizado

## 🧱 Stack
PHP 8+ · MySQL/MariaDB (utf8mb4/InnoDB) · TailwindCSS (CDN) · MVC próprio · sem Composer/Node

## 📁 Estrutura
```
public/        → document root (index.php, .htaccess, assets/)
app/Core/      → Router, Database, Auth, View, Csrf, Session, Validator, Env...
app/Middleware → auth, guest, csrf
app/Controllers, Models, Views
config/        → app, database, routes
database/      → schema.sql, seed.sql
storage/logs/  → logs (fora da web)
```

## 🚀 Como rodar (local / XAMPP)
1. Clone dentro de `htdocs`.
2. Copie o ambiente: `cp .env.example .env` e ajuste as credenciais do MySQL.
3. Crie o banco e importe:
   ```sql
   CREATE DATABASE originium CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
   ```bash
   mysql -u root originium < database/schema.sql
   mysql -u root originium < database/seed.sql
   ```
4. Acesse `http://localhost/Originium/public` (ou o nome da sua pasta + `/public`).

## ☁️ Deploy em hospedagem compartilhada
- **Ideal:** aponte o *Document Root* do domínio para a pasta `public/`.
- **Alternativa:** suba tudo e acesse por `.../public/`. O `.htaccess` da raiz bloqueia
  acesso direto a `app/`, `config/`, `database/`, `storage/` e ao `.env`.
- Defina `APP_DEBUG=false` em produção.

## 🔒 Segurança
PDO + prepared statements · `password_hash` (bcrypt) · token CSRF em todo POST ·
escape de saída (anti-XSS) · sessão `httponly`/`samesite` com regeneração no login ·
IP anonimizado (SHA-256) nos analytics · cabeçalhos de segurança no `.htaccess`.

## 🗺️ Rotas principais
| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/` | Landing page |
| GET/POST | `/register`, `/login` | Autenticação |
| GET | `/dashboard` | Painel |
| GET/POST | `/dashboard/links*` | CRUD de links |
| GET/POST | `/dashboard/themes` | Temas |
| GET | `/dashboard/analytics` | Métricas |
| GET | `/u/{username}` | Perfil público |
| GET | `/l/{id}` | Redirecionamento rastreável |

---
🤖 Desenvolvido com [Claude Code](https://claude.com/claude-code)
