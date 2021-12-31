# Twig Stack Extension

[![Composer Package](https://img.shields.io/packagist/v/filhocodes/twig-stack-extension)](https://packagist.org/packages/filhocodes/twig-stack-extension)
[![Composer Package Downloads](https://img.shields.io/packagist/dt/filhocodes/twig-stack-extension)](https://packagist.org/packages/filhocodes/twig-stack-extension)
[![Build Status](https://github.com/filhocodes/twig-stack-extension/actions/workflows/tests.yml/badge.svg)](https://github.com/filhocodes/twig-stack-extension/actions)
[![Coverage Status](https://coveralls.io/repos/github/filhocodes/twig-stack-extension/badge.svg)](https://coveralls.io/github/filhocodes/twig-stack-extension)

This extension allows you to define a section of a "base template" that will
receive contents from all the other pages, either via `include`, `embed` or
`extends`.

The reason behind this package is that `phive/twig-extensions-deferred` doesn't
really have the features I wanted. For those who doesn't know, this extension
allows you to say that a block in your template will be evaluated at the end of
the template, and not while it is parsing. It also serves as a learning project,
since it is my first Twig Extension.

This project is also HEAVILY related to the package `aygon/twig-stack`. It's
kinda like a refactor of their code into Twig 2.*, with my opinionated choices.

## CHANGELOG v2.0.0

Version 2.0.0 enables support for both PHP 8.0 and PHP 8.1, while removing
support for PHP 7.3 and bellow. If you are still using those old PHP versions,
you will need to install the version 1.0.0 of this package.

## Installation

You can install this package via composer:

```bash
composer require filhocodes/twig-stack-extension
```

If you are using Symfony, you should be able to configure it by adding a entry
into `services.yaml`:

```yaml
services:
    FilhoCodes\TwigStackExtension\TwigStackExtension:
        class: 'FilhoCodes\TwigStackExtension\TwigStackExtension'
        tags: ['twig.extension']
```

In either way, you can always add the extension directly into your Twig
environment:

```php
use FilhoCodes\TwigStackExtension\TwigStackExtension;

/** @var $twig \Twig\Environment */
$twig->addExtension(new TwigStackExtension());
```

## Usage

In your base template, define a stack using `{% stack %}`

```twig
<html lang="en">
<head>
  <title>My template</title>
  {% stack styles %}
</head>
<body>
  {% stack scripts %}
</body>
</body>
</html>
```

Then, in files that either extend your template, or are included by any other,
you may use `{% push %}` and `{% prepend %}` to add contents to the end and the
beginning of the stack, respectively.

```twig
{% extends 'base.twig.html' %}

{% push scripts %}
<script></script>
{% endpush %}
```

```twig
{# include 'partial.twig.html' #}

{% push styles %}
<link />
{% endpush %}
```

To prevent duplicated code, like in files that are included or embed multiple
times you can add an "id" to the block declaration in order to the extension to
identify the block as unique. Just keep in mind that we will not verify the
contents of said block, neither if it is a `push` or `prepend`. So watch out for
possible collision-naming bugs.

```twig
{# embed 'component.twig.html' #}

{# The following contents will be rendered only once #}
{% push scripts component_definition_script %}
<script>
  MyPlugin = { /* ... */ }
</script>
{% endpush %}

{# The following contents will be rendered as many times as this file is embed,
   probably each with some different initialization logic... #}
{% push scripts %}
<script>
  MyPlugin.init();
</script>
{% endpush %}
```
