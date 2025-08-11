git clone https://github.com/JanisKaucis/BankAPITask.git

cd BankAPITask 

composer install  
copy .env.example .env  
open .env and set database settings  
open docker-compose.yml and set database settings equal to .env  
docker-compose up -d --build  
docker exec -it laravel_app php artisan key:generate  
docker exec -it laravel_app php artisan migrate  
docker exec -it laravel_app php artisan db:seed  
