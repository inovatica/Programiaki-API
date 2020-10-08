## Programiaki

Inicjalizacja

```bash

git clone https://repo.inovatica.com/laravel/5_5/bootstrap.git
cd bootstrap/
chmod -R 777 storage/
chmod -R 777 bootstrap/cache
cp .env.example .env
composer install
./artisan key:generate
npm install
npm run dev

```
uzupełnij połączenie z bazą danych w .env a następnie 

```bash
./artisan migrate:fresh --seed
```

Aby uploadowane pliki były dostępne z poziomu przeglądarki:

```bash
./artisan storage:link
```



Init Passport'a

```
composer install
php artisan vendor:publish --tag=passport-components
php artisan passport:client --personal
```