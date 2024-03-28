# iamyukihiro/fake_auth

## Enviroment

```bash
$ docker -v
Docker version 25.0.3, build 4debf41
```

## Installation

```bash
$ cp .env .env.local
$ bin/dev/compose
$ bin/dev/open
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