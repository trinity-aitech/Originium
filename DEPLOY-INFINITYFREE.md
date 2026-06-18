# 🚀 Hospedar o Originium no InfinityFree

O Originium é PHP 8 + MySQL puro, então roda no plano gratuito do InfinityFree.
A única particularidade: no InfinityFree a raiz pública do site é a pasta **`htdocs`**
(não dá pra apontar para `public/`). Por isso o conteúdo de `public/` vai **direto dentro de `htdocs`**,
e as pastas internas (`app/`, `config/`, etc.) ficam ao lado, protegidas por `.htaccess`.

> O `index.php` detecta esse layout automaticamente — não precisa editar código.

---

## 1. Criar a conta e o banco de dados

1. Crie uma conta em https://infinityfree.com e um site (você ganha um subdomínio grátis, ex.: `seunome.infinityfreeapp.com`, ou conecte um domínio próprio).
2. No **Control Panel** → **MySQL Databases** → crie um banco. Anote os dados que aparecem:
   - **Host** (ex.: `sql123.infinityfree.com`)
   - **Database name** (ex.: `epiz_12345678_originium`)
   - **Username** (ex.: `epiz_12345678`)
   - **Password** (a senha da sua conta InfinityFree)

## 2. Importar as tabelas (phpMyAdmin)

1. No Control Panel, abra o **phpMyAdmin** do banco criado.
2. Selecione o banco na lateral e vá em **Import**. Importe **nesta ordem**, um arquivo por vez:
   1. `database/schema.sql`
   2. `database/seed.sql`
   3. `database/migrations/002_sprint.sql`
   4. `database/migrations/003_profile_ui.sql`

## 3. Preparar o `.env`

1. Pegue o projeto: no GitHub, **Code → Download ZIP** (ou use seu clone local).
2. Copie `.env.example` para `.env` e preencha com os dados do passo 1:

   ```env
   APP_NAME="Originium"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://seunome.infinityfreeapp.com

   DB_HOST=sql123.infinityfree.com
   DB_PORT=3306
   DB_DATABASE=epiz_12345678_originium
   DB_USERNAME=epiz_12345678
   DB_PASSWORD=suaSenhaInfinityFree
   ```

   > Use `APP_DEBUG=false` em produção e `https://` no `APP_URL` (o InfinityFree oferece SSL grátis).

## 4. Definir o PHP 8

No Control Panel → **PHP Config** (ou similar) → selecione **PHP 8.1+**.

## 5. Enviar os arquivos (FTP — recomendado)

O vídeo do hero tem ~5,6 MB, então o **FTP** é mais confortável que o File Manager.
Use o **FileZilla** com os dados de FTP do Control Panel (host `ftpupload.net`, usuário `epiz_…`, sua senha).

Dentro da pasta **`htdocs`** do servidor, organize assim:

```
htdocs/
├── index.php          ← (vem de public/index.php)
├── .htaccess          ← (vem de public/.htaccess)
├── assets/            ← (vem de public/assets/, inclui videos/hero.mp4)
├── app/
├── config/
├── database/
├── storage/
└── .env
```

Ou seja:
- **Conteúdo de `public/`** (o `index.php`, o `.htaccess` e a pasta `assets/`) → vai **direto em `htdocs/`**.
- As pastas **`app/`, `config/`, `database/`, `storage/`** e o arquivo **`.env`** → vão **dentro de `htdocs/`** também (como subpastas).
- Não precisa enviar: `public/` (a pasta em si), `.git/`, `README.md`, `dev-router.php`, `DEPLOY-INFINITYFREE.md`.

## 6. Permissões de escrita (uploads)

Avatares e galeria são gravados em `assets/uploads`, e logs em `storage/logs`.
No FileZilla, clique com o direito nessas pastas → **File permissions** → defina **755** (se upload falhar, tente **777**).

## 7. Pronto 🎉

Acesse `https://seunome.infinityfreeapp.com`. Crie sua conta em `/register` e explore o dashboard.

---

## Observações

- **QR Code**: é gerado 100% em PHP (sem internet/GD), então funciona normalmente.
- **Formulário de contato**: as mensagens ficam salvas no banco (não dependem de envio de e-mail).
- **Uploads de imagem**: dependem da extensão `fileinfo` do PHP (ativa por padrão no InfinityFree).
- **Limites do plano grátis**: há limite de "hits" diários e de inodes (nº de arquivos) — suficiente para um projeto pessoal.
- O InfinityFree pode levar alguns minutos para ativar a conta/SSL na primeira vez.
