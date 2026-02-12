# 🐳 Docker - Sistema de Tickets

Estrutura Docker para produção do Sistema de Tickets Laravel.

## 📋 Pré-requisitos

- Docker 20.10+
- Docker Compose 2.0+

## 🚀 Deploy em Produção

### 1. Preparar ambiente

```bash
# Copiar arquivo de ambiente
cp .env.docker.example .env
# OU para produção:
# cp .env.production.example .env

# Editar variáveis de ambiente
nano .env
```

**Variáveis obrigatórias:**
- `APP_KEY` - Gerar com: `php artisan key:generate --show`
- `DB_PASSWORD` - Senha segura para banco
- `DB_ROOT_PASSWORD` - Senha root do banco
- `APP_URL` - URL da aplicação (padrão: http://localhost:8000)
- `MAIL_*` - Credenciais de email (opcional, pode usar log)
- `RUN_SEED` - `true` para popular banco automaticamente, `false` para não (padrão: false)

### 2. Build e iniciar containers

```bash
# Build das imagens
docker-compose build --no-cache

# Iniciar serviços
docker-compose up -d

# Verificar logs
docker-compose logs -f app
```

### 3. Primeira execução

```bash
# Popular banco de dados (APENAS primeira vez)
docker-compose exec app php artisan db:seed

# Verificar status dos containers
docker-compose ps
```

## 🛠️ Comandos Úteis

### Gerenciamento de Containers

```bash
# Parar todos os serviços
docker-compose stop

# Reiniciar serviço específico
docker-compose restart app

# Ver logs em tempo real
docker-compose logs -f app

# Acessar shell do container
docker-compose exec app sh

# Remover tudo (CUIDADO: apaga volumes!)
docker-compose down -v
```

### Artisan Commands

```bash
# Executar migrations
docker-compose exec app php artisan migrate

# Limpar cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Ver status da fila
docker-compose exec app php artisan queue:monitor

# Rodar testes
docker-compose exec app php artisan test
```

### Backup do Banco

```bash
# Criar backup
docker-compose exec db mysqldump -u tickets -p tickets > backup.sql

# Restaurar backup
docker-compose exec -T db mysql -u tickets -p tickets < backup.sql
```

## 🏗️ Arquitetura

```
┌─────────────────────────────────────────┐
│          Container: app                 │
│  ┌─────────┐  ┌────────┐  ┌──────────┐ │
│  │  Nginx  │  │PHP-FPM │  │  Queue   │ │
│  │  :80    │→ │  :9000 │  │  Worker  │ │
│  └─────────┘  └────────┘  └──────────┘ │
└────────┬────────────────────────┬───────┘
         │                        │
    ┌────▼─────┐            ┌────▼─────┐
    │   DB     │            │  Redis   │
    │ MariaDB  │            │  Cache   │
    │  :3306   │            │  :6379   │
    └──────────┘            └──────────┘
```

### Serviços

- **app**: Aplicação Laravel (Nginx + PHP-FPM + Queue Worker)
- **db**: MariaDB 11.2 para dados
- **redis**: Cache, sessões e filas

## 🔒 Segurança em Produção

### ✅ Configurações Aplicadas

- PHP `expose_php = Off`
- Headers de segurança no Nginx
- OPCache habilitado
- Logs apropriados
- Permissões restritas

### ⚠️ Checklist Pré-Deploy

**Para uso local (desenvolvimento/homologação):**
- [ ] `APP_KEY` gerada e única
- [ ] `DB_PASSWORD` e `DB_ROOT_PASSWORD` definidas
- [ ] `APP_URL=http://localhost:8000`
- [ ] Porta 8000 disponível

**Para produção em servidor:**
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] Senhas fortes em `DB_PASSWORD` e `DB_ROOT_PASSWORD`
- [ ] `APP_URL` com domínio real
- [ ] Configurar firewall (portas 80/443)
- [ ] SSL/TLS configurado (usar reverse proxy como Traefik/Caddy)
- [ ] Backup automatizado do banco

## 📊 Monitoramento

### Logs

```bash
# Laravel
docker-compose exec app tail -f storage/logs/laravel.log

# Nginx
docker-compose logs -f app | grep nginx

# Queue Worker
docker-compose exec app tail -f storage/logs/worker.log
```

### Métricas

```bash
# Status dos containers
docker stats

# Uso de disco
docker system df

# Verificar saúde dos serviços
docker-compose ps
```

## 🔄 Atualização da Aplicação

```bash
# 1. Pull do código
git pull origin main

# 2. Rebuild dos containers
docker-compose build app

# 3. Atualizar sem downtime
docker-compose up -d --no-deps --build app

# 4. Executar migrations
docker-compose exec app php artisan migrate --force

# 5. Limpar cache
docker-compose exec app php artisan optimize:clear
docker-compose exec app php artisan optimize
```

## 🆘 Troubleshooting

### Container não inicia

```bash
# Ver logs detalhados
docker-compose logs app

# Verificar configuração
docker-compose config
```

### Erro de permissão

```bash
# Ajustar permissões
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Banco não conecta

```bash
# Verificar saúde do banco
docker-compose exec db mysqladmin ping -h localhost

# Testar conexão
docker-compose exec app php artisan db:show
```

### Queue não processa

```bash
# Ver logs do worker
docker-compose exec app tail -f storage/logs/worker.log

# Reiniciar workers
docker-compose restart app
```

## 📝 Notas

- Os volumes persistem dados mesmo após `docker-compose down`
- Para reset completo: `docker-compose down -v` (apaga volumes!)
- Queue workers reiniciam automaticamente a cada 1 hora
- Logs do Laravel em: `storage/logs/laravel.log`
- Supervisor gerencia PHP-FPM, Nginx e Queue Workers

## 🌐 Produção com HTTPS

Para HTTPS, recomenda-se usar um reverse proxy:

### Opção 1: Traefik

```yaml
# Adicionar ao docker-compose.yml
traefik:
  image: traefik:v2.10
  command:
    - "--providers.docker=true"
    - "--entrypoints.web.address=:80"
    - "--entrypoints.websecure.address=:443"
    - "--certificatesresolvers.letsencrypt.acme.email=seu@email.com"
  ports:
    - "80:80"
    - "443:443"
```

### Opção 2: Caddy

```bash
# Instalar Caddy no host
caddy reverse-proxy --from seudominio.com.br --to localhost:8000
```
