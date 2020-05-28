## Project setup 

### Create .env file

Create .env based on the .env.template.

Generate SSH keys by running these commands:
```bash
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

Copy passphrase and past it to your .env file to the `JWT_PASSPHRASE` variable.

Add a value for `NGINX_PUBLIC_PORT` variable for the next step.

### Start containers
```bash
docker-compose up -d --build
```

### To run symfony commands use
```bash
docker-compose exec php bin/console
```

## Task manager usage

1. Go to `/api/doc` url to see the full list of available actions.
2. Create a user using the `POST​/api​/users` endpoint.
3. Get JWT token for the user on the `/api/login_check` endpoint with `username` and `password`.
4. Pass the token to the Authorize section in "Bearer ***your token***" format.


## Running unit tests
```bash
./vendor/bin/phpunit tests
