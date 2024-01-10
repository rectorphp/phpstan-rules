# Rector PHPStan Rules

## Deprecated - switch to `symplify/phpstan-rules`

This package is deprecated, as it was barely used and even little maintained. We've moved to more live and maintained package symplify/phpstan-rules to give it more love:

<br>

Flip packages:

```hash
composer remove rector/phpstan-rules
composer require phpstan/phpstan-rules --dev
```

Update your `phpstan.neon`:

```diff
# phpstan.neon
-    - vendor/rector/phpstan-rules/config/rector-rules.neon
+    - vendor/phpstan/phpstan-rules/config/rector-rules.neon
```

PHPStan rules for Rector and projects that maintain Rector rulesets.

## Install

```bash
composer require rector/phpstan-rules --dev
```
