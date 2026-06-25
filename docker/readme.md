# Docker PHP

To download vendor libraries

```bash
docker compose run --rm app  composer update 
```

to run testsuite

```bash
docker compose run --rm app vendor/bin/phpunit
```