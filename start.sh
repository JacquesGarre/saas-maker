#!/bin/bash

GREEN='\033[0;32m'
NC='\033[0m' 

composer install

docker compose build
docker compose up -d

echo ""
echo " ${GREEN}➜ ${NC} Clearing cache..."
docker exec -it php bin/console c:c

echo ""
echo " ${GREEN}➜ ${NC} Running database migrations..."
docker exec -it php bin/console doctrine:migrations:migrate --no-interaction

echo ""
echo " ${GREEN}➜ ${NC} Installing front-end dependencies..."
cd angular && npm install

echo ""
echo " ${GREEN}➜ ${NC} Api running at ${GREEN}http://localhost:8000${NC}"
echo " ${GREEN}➜ ${NC} Mailhog running at ${GREEN}http://localhost:8025${NC}"


