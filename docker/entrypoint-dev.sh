#!/bin/sh
set -e

echo "🔧 Iniciando ambiente de desenvolvimento..."

# Instala dependências do Composer se não existir vendor
if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    echo "📦 Instalando dependências do Composer..."
    composer install --no-interaction --prefer-dist
    echo "✓ Dependências instaladas!"
fi

# Instala dependências do Node se vite não estiver disponível
if [ ! -x "node_modules/.bin/vite" ]; then
    echo "📦 Instalando dependências do Node..."
    npm install
    echo "✓ Dependências do Node instaladas!"
fi

# Builda assets do Vite se não existir manifest
if [ ! -f "public/build/manifest.json" ]; then
    echo "🎨 Buildando assets com Vite..."
    npx vite build
    echo "✓ Assets buildados!"
fi

# Gera APP_KEY se não existir ou estiver vazia
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:CHANGE_ME" ]; then
    echo "🔑 Gerando APP_KEY..."
    php artisan key:generate --no-interaction --force
    echo "✓ APP_KEY gerada!"
else
    echo "✓ APP_KEY já configurada"
fi

# Aguarda o banco de dados estar disponível
echo "⏳ Aguardando banco de dados..."
until php artisan db:show > /dev/null 2>&1; do
    echo "   Banco ainda não está pronto, tentando novamente em 2s..."
    sleep 2
done
echo "✓ Banco de dados conectado!"

# Executa migrations
echo "🗄️  Executando migrations..."
php artisan migrate --no-interaction
echo "✓ Migrations executadas!"

# Executa seed se RUN_SEED=true E banco estiver vazio
if [ "$RUN_SEED" = "true" ]; then
    # Verifica se já existem usuários no banco usando tinker (confiável)
    USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::withoutGlobalScopes()->count();" 2>/dev/null | tail -1 | tr -d '[:space:]')

    if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
        echo "🌱 Populando banco de dados..."
        php artisan db:seed --no-interaction
        echo "✓ Seed executado!"
    else
        echo "ℹ️  Banco já possui dados ($USER_COUNT usuários), pulando seed..."
    fi
fi

# Limpa caches para desenvolvimento
echo "🧹 Limpando caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
echo "✓ Caches limpos!"

# Define permissões
echo "🔐 Ajustando permissões..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
echo "✓ Permissões configuradas!"

echo "✅ Ambiente de desenvolvimento pronto!"
echo "🚀 Iniciando servidor Laravel..."

# Executa o comando passado ao container (php artisan serve)
exec "$@"
