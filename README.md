Astina Translation Bundle
=========================

DoctrineLoader to store translations in the database plus simple admin UI to edit translation messages.

## Installation

### Step 1: Add to composer.json

```json
"require":  {
    "astina/translation-bundle":"dev-master",
}
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Astina\Bundle\TranslationBundle\AstinaTranslationBundle(),
    );
}
```

### Step 3: Configure the bundle

Bundle configuration:

```yml
# app/config/config.yml
astina_translation:
    domains: [ messages ]
    locales: [ de, fr, it, en ]
    admin:
        layout_template: ::translation_layout.html.twig
```

Routing configuration (if admin UI is needed):

```yml
# app/config/routing.yml
astina_translation:
    resource: "@AstinaTranslationBundle/Resources/config/routing.yml"
    prefix:   /admin/translations
```

## Usage

You can add/update messages using the translation repository service:

```php
$repo = $container->get('astina_translation.repository.translation');
$repo->set('messages', 'foo', 'fr', 'foux');
```

**Note**: after adding/changing translations, the cache needs to be cleared.

If you have added the routing config, you can access `/admin/translations` to edit messages stored in the database. The cache is automatically cleared after saving.