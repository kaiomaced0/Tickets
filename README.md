# 🎫 Sistema de Tickets

Sistema completo de gerenciamento de tickets desenvolvido com Laravel 11, incluindo autenticação, autorização, notificações por email e API REST.

> ✅ **Status do Projeto:** Todas as funcionalidades solicitadas foram implementadas com sucesso, incluindo:
> - ✅ Interface web completa (frontend)
> - ✅ API REST com todos os endpoints documentados
> - ✅ Testes automatizados (43 testes / 103 assertivas)
> - ✅ Requisitos bônus e opcionais

## ✨ Funcionalidades

### 🎯 Requisitos Principais (100% Implementado)

- 🔐 **Autenticação e Autorização** - Sistema completo com roles (Admin/User)
- 🎫 **Gestão de Tickets** - CRUD completo com status, prioridades e classificação
- 👥 **Gestão de Usuários** - Administração de usuários com ativação/desativação via SoftDeletes
- � **Histórico de Status** - Log completo de mudanças de status dos tickets

### 🌟 Requisitos Bônus (100% Implementado)

- 📧 **Notificações por Email**
  - Envio automático quando ticket é marcado como RESOLVIDO
  - Email enviado ao solicitante do ticket
  - Processamento via fila (Queue) em background para não bloquear requisição
  - Suporte a SMTP (Gmail, etc)
  - *(Credencial temporária de email já configurada nos arquivos `.env` - pode ser usada para testes)*
- 📱 **API REST Completa** 
  - Endpoints para todas as operações (tickets e usuários)
  - Autenticação via token SHA-256
  - Documentação completa de rotas e exemplos
  - Políticas de autorização aplicadas
- ✅ **Testes Automatizados Abrangentes**
  - 43 testes implementados (103 assertivas)
  - Cobertura de Feature Tests (autenticação, tickets, usuários)
  - Testes de API (CRUD completo, autorização)
  - Testes de Policies e validações
- 🎨 **Dark Mode** - Tema escuro/claro com persistência de preferência

### 🚀 Funcionalidades Opcionais (100% Implementado)

- 🐳 **Docker com Docker Compose** - Deploy em produção facilitado com multi-stage build
- 🌎 **Localização pt_BR** - Timezone America/São_Paulo em todos os níveis (container, PHP, Laravel)
- 📊 **Service Layer** - Arquitetura com camada de serviços (Services/Email, Ticket, User)
- 🔒 **Policies** - Autorização granular com TicketPolicy e UserPolicy
- 🎯 **Form Requests** - Validação centralizada e reutilizável
- 💾 **SoftDeletes** - Exclusão lógica para dados críticos (User, Ticket)
- 🎨 **Interface Responsiva** - Design moderno com TailwindCSS

## 🚀 Deploy com Docker (Recomendado)

> 🏠 **Por padrão, roda localmente em `http://localhost:8000`**  

### Início Rápido

```bash
# 1. Clonar repositório
git clone https://github.com/seu-usuario/tickets.git
cd tickets

# 2. Copiar arquivo de ambiente
cp .env.docker.example .env

# 3. Subir containers
docker-compose up -d --build

# OU usar Make (se disponível)
make deploy

# 4. Acessar aplicação
# http://localhost:8000
```

A aplicação já subirá com:
- ✅ Banco de dados configurado e migrado
- ✅ 8 usuários criados automaticamente (seed)
- ✅ Servidor rodando na porta 8000

**O que acontece automaticamente no container:**
1. 📦 Instalação de dependências (Composer e NPM)
2. 🔑 Geração de APP_KEY (se não existir)
3. ⏳ Espera o banco de dados estar pronto
4. 🗄️ Executa migrations automaticamente
5. 🌱 Popula banco com seed (se `RUN_SEED=true`)
6. 🧹 Limpa caches para desenvolvimento
7. 🔐 Ajusta permissões de arquivos
8. 🚀 Inicia servidor Laravel

> 💡 Todo esse processo é gerenciado pelo script `docker/entrypoint-dev.sh` que garante um ambiente pronto para uso.

### 👥 Usuários Criados pelo Seed

O sistema cria automaticamente 8 usuários para teste:

**Administradores (ADMIN):**
- **Matheus Mariano** - `matheus@example.com` / `password`
- **Any Sayuri** - `anysayuri@example.com` / `password`

**Usuários Comuns (USER):**
- **Carlos Silva** - `carlos@example.com` / `password`
- **Caio Fernandes** - `caio@example.com` / `password`
- **Paulo Costa** - `paulo@example.com` / `password`
- **Maria Oliveira** - `maria@example.com` / `password`
- **Roberto Lima** - `roberto@example.com` / `password`
- **Juliana Alves** - `juliana@example.com` / `password`

> 💡 **Dica:** Admins podem gerenciar todos os tickets e usuários. Usuários comuns só podem criar e visualizar seus próprios tickets.

### Comandos Docker

```bash
# Gerenciamento básico
docker-compose up -d              # Iniciar containers
docker-compose down               # Parar containers
docker-compose restart            # Reiniciar containers
docker-compose logs -f app        # Ver logs em tempo real

# Comandos Make (se disponível)
make help          # Ver todos os comandos disponíveis
make deploy        # Deploy completo
make logs          # Ver logs
make test          # Rodar testes
make backup        # Backup do banco

# Comandos dentro do container
docker-compose exec app php artisan migrate        # Rodar migrations
docker-compose exec app php artisan db:seed        # Rodar seed
docker-compose exec app php artisan test           # Rodar testes
docker-compose exec app php artisan cache:clear    # Limpar cache
```

📚 **Documentação Completa Docker:** Ver [docker/README.md](docker/README.md) para configurações avançadas, produção e troubleshooting.

---

### Pré-requisitos

- PHP 8.2+
- Composer
- Node.js 18+ e NPM
- MariaDB/MySQL
- Git

### Passos

```bash
# 1. Clonar repositório
git clone https://github.com/seu-usuario/tickets.git
cd tickets

# 2. Instalar dependências
composer install
npm install

# 3. Configurar ambiente
cp .env.example .env
php artisan key:generate

# 4. Configurar banco no .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tickets
DB_USERNAME=root
DB_PASSWORD=

# 5. Executar migrations e seed
php artisan migrate --seed
# Isso criará 8 usuários (2 admins + 6 usuários). Ver seção "Usuários Criados pelo Seed"

# 6. Build assets
npm run build

# 7. Iniciar servidor
php artisan serve
```

Acesse: http://localhost:8000

> 👥 **Usuários de teste:** Veja os 8 usuários criados automaticamente na seção ["Usuários Criados pelo Seed"](#-usuários-criados-pelo-seed) acima.

---

## 🏗️ Arquitetura

### Stack Tecnológico

- **Backend:** Laravel 11.48 (PHP 8.2+)
- **Frontend:** Blade + Vite + TailwindCSS
- **Banco de Dados:** MariaDB 11.2
- **Cache/Queue:** Redis (produção) / File/Sync (dev)
- **Email:** SMTP (Gmail)
- **Containerização:** Docker + Docker Compose

### Estrutura de Diretórios

```
app/
├── Enums/              # Status e Prioridades
├── Http/
│   ├── Controllers/    # Controllers web e API
│   ├── Middleware/     # Admin, Auth Token
│   └── Requests/       # Form Requests com validação
├── Models/             # Ticket, User, TicketStatusLog
├── Notifications/      # Email de ticket resolvido
├── Policies/           # TicketPolicy, UserPolicy
└── Services/           # Lógica de negócio segregada
    ├── Email/
    ├── Ticket/
    └── User/

resources/
├── views/              # Blade templates
│   ├── tickets/        # CRUD de tickets
│   ├── admin/          # Admin panel
│   └── layouts/        # Layouts base
└── js/                 # JavaScript/Vite

docker/
├── nginx/              # Configurações Nginx
├── php/                # PHP.ini customizado
├── supervisor/         # Queue workers
├── entrypoint.sh       # Script inicialização (produção)
├── entrypoint-dev.sh   # Script inicialização (desenvolvimento)
└── README.md           # Documentação Docker
```

### Infraestrutura Docker

**Desenvolvimento (`docker-compose.dev.yml`):**
- Container App: PHP 8.2-FPM + Nginx + Node.js
- Container DB: MariaDB 11.2
- Entrypoint automático (migrations, seed, dependências)
- Hot reload de código (volumes montados)

**Produção (`docker-compose.yml`):**
- Multi-stage build otimizado
- Supervisor gerenciando PHP-FPM + Nginx + 2 Queue Workers
- Redis para cache, sessions e queue
- Entrypoint com otimizações de cache

---

## 📡 API REST

### Autenticação

Token SHA-256 via endpoint `/api/auth/token`:

```bash
# Gerar token
curl -X POST http://localhost:8000/api/auth/token \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# Usar token
curl -X GET http://localhost:8000/api/tickets \
  -H "Authorization: Bearer {seu-token}"
```

### Endpoints Principais

```
POST   /api/auth/token              # Gerar token
POST   /api/auth/revoke             # Revogar token

GET    /api/tickets                 # Listar tickets
POST   /api/tickets                 # Criar ticket
GET    /api/tickets/{id}            # Ver ticket
PATCH  /api/tickets/{id}            # Atualizar ticket
PATCH  /api/tickets/{id}/status     # Mudar status
POST   /api/tickets/{id}/toggle-active  # Ativar/Desativar

GET    /api/users                   # Listar usuários (admin)
POST   /api/users                   # Criar usuário (admin)
GET    /api/users/{id}              # Ver usuário (admin)
PATCH  /api/users/{id}              # Atualizar usuário (admin)
POST   /api/users/{id}/toggle-active  # Ativar/Desativar (admin)
```

> 📌 **Nota:** Todos os endpoints da API estão **totalmente funcionais** e cobertos por testes automatizados. A autorização é aplicada via Policies em todas as rotas.

---

## ✅ Testes

```bash
# Rodar todos os testes
php artisan test --without-tty      # Windows PowerShell
php artisan test                    # Linux/Mac

# OU use o script helper (Windows)
.\test.bat

# Testes específicos
php artisan test --without-tty --filter=ApiTest
php artisan test --without-tty --filter=TicketAccessTest

# Com coverage
php artisan test --without-tty --coverage
```

> 💡 **Nota Windows:** No PowerShell é necessário usar `--without-tty` ou o script `test.bat` fornecido.

### 📊 Cobertura de Testes

**Total:** 43 testes passando (103 assertivas)

**Feature Tests:**
- ✅ Autenticação (login, logout, registro, verificação de email)
- ✅ Gestão de Tickets (CRUD, filtros, autorização)
- ✅ Gestão de Usuários (CRUD, ativação/desativação)
- ✅ Gestão de Perfil (atualização, senha, tema)

**API Tests:**
- ✅ Autenticação via Token (geração, revogação)
- ✅ CRUD de Tickets via API
- ✅ CRUD de Usuários via API (admin)
- ✅ Autorização e Policies
- ✅ Validações de campos obrigatórios

**Unit Tests:**
- ✅ Validações de regras de negócio

---

## 🔐 Segurança

### Implementações

- ✅ Policies para autorização (TicketPolicy, UserPolicy)
- ✅ Form Requests com validação
- ✅ SoftDeletes para dados críticos
- ✅ CSRF Protection
- ✅ SQL Injection protection (Eloquent ORM)
- ✅ XSS Protection (Blade escaping)
- ✅ Password hashing (bcrypt)
- ✅ API Token (SHA-256)

### Configurações de Produção

```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
QUEUE_CONNECTION=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

---

## 📧 Sistema de Notificações

### Email

- **Trigger:** Quando ticket muda para status RESOLVIDO
- **Destinatário:** Solicitante do ticket
- **Processamento:** Via fila (Queue) em background
- **Fila:** Redis (produção) / Sync (desenvolvimento)

> 💡 **Para Testes:** Uma credencial temporária de email já está configurada nos arquivos `.env` e pode ser usada imediatamente para testar o envio de notificações.

### Queue Worker

Em produção (Docker), Supervisor gerencia 2 workers automaticamente.

Manual:
```bash
php artisan queue:work --tries=3
```

---

## 🎨 Interface

- **Design System:** TailwindCSS 3
- **Dark Mode:** Suporte nativo
- **Responsivo:** Mobile-first
- **Ícones:** Heroicons
- **Build:** Vite 6.4

---

## 🔧 Manutenção

### Cache

```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Criar cache (produção)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Migrations

```bash
# Rodar migrations
php artisan migrate

# Rollback última migration
php artisan migrate:rollback

# Reset completo
php artisan migrate:fresh --seed
```

### Logs

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Docker logs
docker-compose logs -f app
```

---


## 👤 Autor: Kaio Macedo Machado

Desenvolvido como teste técnico para processo seletivo na Secretaria de Ciência, Tecnologia e Inovação da Prefeitura de Gurupi

**Stack:** Laravel 11 • PHP 8.2 • MariaDB • Docker • TailwindCSS
