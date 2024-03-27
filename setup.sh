#!/bin/bash

if [[ "$OSTYPE" == "darwin"* ]]; then
    SED_EXT='-i '' '
else
    SED_EXT='-i'
fi

# Setup .env file
if [ ! -f .env ]; then
    cp .env.example .env
    echo ".env file created from .env.example"
fi

if [ -f .env ]; then
    sed $SED_EXT 's/DB_DATABASE=db-name/DB_DATABASE=url_shortener/g' .env
    sed $SED_EXT 's/DB_TEST_DATABASE=db-test-name/DB_TEST_DATABASE=url_shortener_test/g' .env
    sed $SED_EXT 's/DB_PASSWORD=password/DB_PASSWORD=root_password123/g' .env
    sed $SED_EXT 's/DB_TEST_PASSWORD=testpassword/DB_TEST_PASSWORD=root_password123/g' .env
    sed $SED_EXT 's/SAFE_BROWSING_API_KEY=api_key/SAFE_BROWSING_API_KEY=AIzaSyCd6WM-hmRQ7_QS62dBPeZW5AjCUhcPnos/g' .env
fi

if [ ! -f .env.testing ]; then
    cp .env.testing.example .env.testing
    echo ".env.testing file created from .env.testing.example"
fi

if [ -f .env.testing ]; then
    sed $SED_EXT 's/DB_DATABASE=db-name/DB_DATABASE=url_shortener_test/g' .env.testing
    sed $SED_EXT 's/DB_PASSWORD=password/DB_PASSWORD=test_password123/g' .env.testing
    sed $SED_EXT 's/SAFE_BROWSING_API_KEY=api_key/SAFE_BROWSING_API_KEY=AIzaSyCd6WM-hmRQ7_QS62dBPeZW5AjCUhcPnos/g' .env.testing
fi

make up

echo "Waiting for containers to be fully up and running..."
sleep 10

echo "Installing Composer dependencies..."
docker-compose exec app composer install

echo "Generating Laravel key..."
docker-compose exec app php artisan key:generate

echo "Generating Laravel migration..."
docker-compose exec app php artisan migrate

# Npm install
echo "Running npm install..."
npm install

npm run dev

echo "Setup complete."

echo "Go to https://localhost:8000 in order to run the application."