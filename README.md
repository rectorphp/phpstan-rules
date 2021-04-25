# Rector PHPStan Rules

PHPStan rules for Rector and projects that maintain Rector rulesets.

## Install

```bash
composer require phpstan/rector-phpstan-rules --dev
```

Do you use [PHPStan extension installer](https://github.com/phpstan/extension-installer)? The config is added automatically.

If not, add it to `phpstan.neon`:

```yaml
includes:
    - vendor/rector/rector-phpstan-rules/config/config.neon
```
