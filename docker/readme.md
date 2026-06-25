# Docker PHP

## To download vendor libraries

```bash
docker compose run --rm app  composer update 
```

## to run testsuite

```bash
docker compose run --rm app vendor/bin/phpunit
```

## To run testsuite with a specific doctrine/lexer version

```bash
docker compose run --rm app composer --prefer-stable require doctrine/lexer:^4.0.x-dev --with-all-dependencies
docker compose run --rm app vendor/bin/phpunit
```
