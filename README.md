[![Latest Version on Packagist](https://img.shields.io/packagist/v/salt/core.svg?style=flat-square)](https://packagist.org/packages/salt/core)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/pango-studio/salt-core/run-tests?label=tests)](https://github.com/pango-studio/salt-core/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/pango-studio/salt-core/Check%20&%20fix%20styling?label=code%20style)](https://github.com/pango-studio/salt-core/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/salt/core.svg?style=flat-square)](https://packagist.org/packages/salt/core)

[Netlify - Package Documentation Home](https://salt-core-package.netlify.app/)

This package houses core logic, templates and components used by Laravel applications built by the Salt team.

It has the following features:

-   [Custom collection traits](https://salt-core-package.netlify.app/guide/collection-traits.html)
-   [A Mandrill mail sender service](https://salt-core-package.netlify.app/guide/mail.html)
-   [A dynamic menu builder](https://salt-core-package.netlify.app/guide/menu-builder.html)
-   [Permissions and roles](https://salt-core-package.netlify.app/guide/permissions-roles.html)
-   [Settings](https://salt-core-package.netlify.app/guide/settings.html)

## Installation

You can install the package via composer:

```bash
composer require salt/core
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="core-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="core-config"
```

This is the contents of the published config file:

````php
return [
    'mail' => array(
        'mandrill' => array(
            'key' => env('MANDRILL_KEY', ''),
            'template' => env('MANDRILL_TEMPLATE', '')
        )
    ),
    'permissions' => \Salt\Core\Options\PermissionOptions::$permissionsArray,
    'roles' => \Salt\Core\Options\RoleOptions::$rolesArray,
    'url' => env('APP_URL', ''),
    'user' => \Salt\Core\Models\User::class
];


## Documentation

[View the documentation for this package here](https://salt-core-package.netlify.app/)

## Testing

```bash
composer test
````

## Releasing a new version

To release a new version, first create a tag on the `main` branch with the new version number. E.g "1.0.1":

```
git tag -a 1.0.1 -m "Release version 1.0.1"
```

Then push that tag up to GitHub:

```
git push origin 1.0.1
```

A new version will automatically be created on packagist which will then be available for installation.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Salt](https://github.com/salt)
-   [All Contributors](../../contributors)
