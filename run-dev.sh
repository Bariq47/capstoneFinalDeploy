docker compose down -v
docker builder prune -f
docker compose build --no-cache
docker compose up -d

sleep 10

docker exec capstone-backend php artisan key:generate --force
docker exec capstone-frontend php artisan key:generate --force

docker exec capstone-backend php artisan migrate --force
docker exec capstone-backend php artisan migrate:fresh --seed --force

# docker exec capstone-backend php artisan optimize
# docker exec capstone-frontend php artisan optimize

docker compose exec frontend php artisan optimize:clear


echo "DEPLOY SUCCESS"
