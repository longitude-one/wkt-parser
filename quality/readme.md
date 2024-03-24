# Some tips to use quality tools

## PHP Linters: Php-CS-Fixer and PHP-Stan 

```bash
docker exec geo-parser COMMAND
```
Example:
```bash
docker exec geo-parser composer install --working-dir=quality/php-cs-fixer
```

## Php-cs-fixer

To install PHP-CS-Fixer, run this command:
```bash
docker exec geo-parser composer update --working-dir=quality/php-cs-fixer
```

To test all files:
```bash
docker exec geo-parser quality/php-cs-fixer/vendor/bin/php-cs-fixer fix --config=quality/php-cs-fixer/.php-cs-fixer.php --dry-run --allow-risky=yes
```
To fix all files:
```bash
docker exec geo-parser quality/php-cs-fixer/vendor/bin/php-cs-fixer fix --config=quality/php-cs-fixer/.php-cs-fixer.php --allow-risky=yes
```

## PhpStan

To install PHP-Stan, run this command:

```bash
docker exec geo-parser composer update --working-dir=quality/php-stan
```

To test files:
```bash
docker exec geo-parser quality/php-stan/vendor/bin/phpstan analyse --configuration=quality/php-stan/php-stan.neon lib tests --error-format=table --no-progress --no-interaction --no-ansi --level=9 --memory-limit=256M
```

To add a file at exception baseline:
```bash
docker exec geo-parser quality/php-stan/vendor/bin/phpstan analyse --configuration=quality/php-stan/php-stan.neon lib tests --error-format=table --no-progress --no-interaction --no-ansi --level=9 --generate-baseline quality/php-stan/phpstan-baseline.neon
```

## PHP Mess Detector

To install PHP-Mess-Detector, run this command:

```bash
docker exec geo-parser composer update --working-dir=quality/php-mess-detector
```

To test files:
```bash
docker exec geo-parser quality/php-mess-detector/vendor/bin/phpmd lib text quality/php-mess-detector/ruleset.xml
docker exec geo-parser quality/php-mess-detector/vendor/bin/phpmd tests text quality/php-mess-detector/test-ruleset.xml
```


## YAML Linter

To test all yaml files in config:

```bash
docker exec geo-parser php bin/console lint:yaml config/ --parse-tags
```
