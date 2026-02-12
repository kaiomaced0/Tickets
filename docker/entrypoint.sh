#!/bin/sh
set -e

echo "🚀 Iniciando aplicação Laravel..."

# Aguarda banco de dados estar pronto
echo "⏳ Aguardando banco de dados..."
until php artisan db:show > /dev/null 2>&1; do
    echo "   Banco ainda não está pronto, aguardando..."
    sleep 2
done

echo "✓ Banco de dados conectado!"

# Executa migrations
echo "🔄 Executando migrations..."
php artisan migrate --force --no-interaction

# Executa seed se variável RUN_SEED=true
if [ "$RUN_SEED" = "true" ]; then
    echo "🌱 Populando banco de dados..."
    php artisan db:seed --force --no-interaction
    echo "✓ Seed executado!"
fi

# Otimizações de cache
echo "⚡ Otimizando aplicação..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Cria link simbólico do storage (se não existir)
if [ ! -L "/var/www/html/public/storage" ]; then
    echo "🔗 Criando link simbólico do storage..."
    php artisan storage:link
fi

# Garante permissões corretas
echo "🔐 Ajustando permissões..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "✅ Aplicação pronta!"
echo ""

# Executa comando passado como argumento
exec "$@"
