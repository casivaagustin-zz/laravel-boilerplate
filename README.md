# How to get up and running

## Before start

You need docker installed in your machine
You need PHP 7 or higher in your machine

## Quick install

Clone the Repo

```
git clone git@github.com:casivaagustin/laravel-boilerplate.git
```

Go to the laravel folder and copy the .env.local as .env

```
cd laravel
cp .env.local .env
```

Go into the laradock directory

```
cd laradock
```

Copy the env example and customize the file if is needed

```
cp env-example .env
docker-compose up -d nginx mysql phpmyadmin redis workspace \n
```

Once it's done run composer into the laravel folder

```
cd ..
composer install
php artisan migrate
```

That's it go to http://localhost
