# Settings

You can use the `Setting` model found in this package to manage key-value pairs of settings stored in the database.
These are helpful as they allow end-user administrators to make changes that affect how the app works without requiring
a developer to make changes to the code and deploy.

## Add a new setting

`Setting::add($key, $value, $type)`

The allowed types are `string`, `int|integer`, `bool|boolean`.

```php
<?php

Setting::add('test-setting', 'some value', 'string');

```

## Get the value for a setting

`Setting::get($key)`

```php
<?php

Setting::get('test-setting'); // Returns 'some value'
```

## Set a value for a setting

This will set a new value for an existing setting based on the provided `$key`.
If the setting is not found, it will create it instead

`Setting::set($key, $value, $type)`

```php
<?php

Setting::set('test-setting', 'some other value', 'string');

Setting::get('test-setting'); // Now returns 'some other value'
```

## Remove a setting

`Setting::remove($key)`

```php
<?php

Setting::remove('test-setting');
```

## Check if a setting exists

Returns `true` if a setting matching the provided `$key` is found, otherwise returns `false`

`Setting::has($key)`

```php
<?php

Setting::has('test-setting'); // returns true

Setting::has('another-setting'); // returns false

```

## Get all settings

Returns a collection of all settings

`Setting::getAllSettings()`
