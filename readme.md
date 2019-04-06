# Municipalities search
[![Laravel app](https://laravel.com/)

This repo contains a simple application for importing, displaying and searching data of Slovak municipalities.

## Requirements

- [PHP](https://www.php.net/) >= 5.6
- [Mysql](https://www.mysql.com/)


## Basic install

Clone GitHub repo for this project locally.
```console
	git clone https://github.com/tonnas/municipalities.git
```

Cd into project directory.
```console
	cd municipalities
```

Install Composer Dependencies
```console
	composer install
``` 

Create an empty mysql database for this application

If .env file does not exist, create a copy of .env.example file
```console
   cp .env.example .env
```

Permissions!

In the .env file fill in the `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` options to match the credentials of the database you just created. This will allow us to run migrations and seed the database in the next step.


Once your credentials are in the .env file, now you can migrate your database.
```console
	php artisan migrate
```


Import data to database from [e-obce.sk](https://www.e-obce.sk/). This may take several minutes.
```console
	php artisan data:import
```

###Have fun!