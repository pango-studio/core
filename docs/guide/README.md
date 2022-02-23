# Salt Core Package Configuration

Once you have installed this package, it is recommended that you start by passing your user model to the package by setting it as the value of 'user' in the core config file. If you
intend to use the User model provided by this package directly, you can skip this step as its already set as the default.

```php
// config/core.php
return [
    ...
    'user' => \App\Models\User::class // default is  \Salt\Core\Models\User::class
];
```

Next up, its a good idea to start configuring [permissions and roles](/guide/permissions-roles.html). You will need to add some seeders and most likely you will also need setup your own role and permission options.

Once you have completed these steps, read through the sections in this guide to see how you can make the most of the features in this package.
