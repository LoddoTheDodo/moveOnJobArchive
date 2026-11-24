Terminal 1: Elasticsearch
cd JobArchive
docker compose up -d

Terminal 2: Laravel-serveren
cd JobArchive
php artisan serve

Terminal 3: Vite/Bootstrap
cd JobArchive
npm run dev
