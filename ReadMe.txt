Kræver: Docker Desktop (kørende), PHP, Composer, Git

1. Hent projektet
   git clone https://github.com/LoddoTheDodo/moveOnJobArchive.git
   cd moveOnJobArchive\JobArchive

2. Start Elasticsearch
   docker compose up -d
   (vent ca. 1 minut, test med: curl.exe http://localhost:9200)

3. Opsæt Laravel
   composer install
   copy .env.example .env
   php artisan key:generate

4. Tilføj i .env
   ELASTICSEARCH_HOST=http://localhost:9200

5. Opret index og fyld Elasticsearch
   php artisan app:create-archived-job-index
   php artisan app:index-archived-jobs

DAGLIG BRUG
   docker compose up -d     start
   docker compose stop      stop (data beholdes)
   docker compose down -v   fjern container OG data