#!/bin/bash

composer install

docker compose build
docker compose up -d

echo "Clearing cache..."
docker exec -it php bin/console c:c

echo "Running database migrations..."
docker exec -it php bin/console doctrine:migrations:migrate --no-interaction

echo " ✔ App running at http://localhost:8000"
