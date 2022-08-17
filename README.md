# iamyukihiro/fake_auth

## Enviroment

```bash
$ php -v
PHP 8.1.0 (cli) (built: Apr 20 2022 15:35:38) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.1.0, Copyright (c) Zend Technologies
    with Zend OPcache v8.1.0, Copyright (c), by Zend Technologies
    with Xdebug v3.1.1, Copyright (c) 2002-2021, by Derick Rethans
```

```bash
$ docker -v
Docker version 20.10.7, build f0df350
```

## Installation

```bash
$ composer install
$ cp .env .env.local
$ bin/db-start
$ bin/console doctrine:database:create
$ bin/console doctrine:migration:migrate
```

### Launch the App

```bash
$ symfony server:start
```

### Connect the App DB

```bash
$ bin/db-connect
```

### Delete the App DB

```bash
$ bin/db-delete
```

## Change the OAuth

```bash
# .env.local

APP_ENV=prod # Google OAuth
APP_ENV=dev  # Fake OAuth
```

## Execute the API

### OK

```bash
# APP_ENV=dev

$ curl 'https://127.0.0.1:8000/api/chart' \
-H 'username: test@example.com'
```

### NG

#### Unauthorized

```bash
# APP_ENV=dev

$ curl 'https://127.0.0.1:8000/api/chart'
```

```bash
# APP_ENV=prod

$ curl 'https://127.0.0.1:8000/api/chart' \
-H 'username: test@example.com'
```