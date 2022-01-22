# Permissions and Roles

This package includes functionality to manage your roles and permissions within the database.
You can add permissions to roles, and then assign those roles to users. You are then able to
check if the user has specific permissions or roles before allowing navigation or certain actions,
e.g when determining whether a menu item should appear:

```php
    // MenuController.php
    <?php
    ...

    if ($user->hasPermission(Permission::VIEW_INSIGHTS)) {
        $menu[__("global.place.manage")][] = self::insightsMenu();
    }
```

or whether or not the user can make a particular request:

```php
    // StorePasswordRequest.php
    <?php
    ...

    public function authorize()
    {
        return $this->user->hasPermission(Permission::MANAGE_USERS);
    }
```

## Permissions

A permission is stored in the database with a unique name, e.g 'view-admin-dashboard' or 'manage-users'. The permission can be associated with a role.
Any user who has that role will then have the associated permissions

## Roles

A role is stored in the database with a unique name and label, eg "User" and "user". Roles can be assigned to users.
A role by itself doesn't do anything until it has a number of associated permissions

## Creating new permissions and roles

You can create new permissions and roles manually and add them to your database.
The problem is when you deploy, those permissions and roles won't be on the dev/staging/production databases.
The best way to deal with this is to have `Seeder` classes which run on deployments. These seeders will populate the database
with new permissions and roles.

### Seeders

When you install the `Salt\Core` package, you won't have any seeders initially. It is recommended that you create the following
seeders and then call them in your `DatabaseSeeder` or `ProductionSeeder` class which runs on deploys:

```php
// database/seeders/PermissionSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Salt\Core\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Permission::permissionOptions() as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }
    }
}
```

```php
// database/seeders/RoleSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Salt\Core\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Role::roleOptions() as $role) {
            Role::updateOrCreate([
                'name' => $role['name'],
                'label' => $role['label']
            ]);
        }
    }
}
```

In your `DatabaseSeeder`, you would then add something like:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ...
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
    }
}

```

### Options

If you take a look at `config/core.php`, you will see the following :

```php
<?php

use Salt\Core\Options\PermissionOptions;
use Salt\Core\Options\RoleOptions;

return [
    ...
    'permissions' => PermissionOptions::$permissionsArray,
    'roles' => RoleOptions::$rolesArray
]

```

If you then go and view those files, they will contain arrays of permission and role options:

```php
// PermissionOptions.php
class PermissionOptions
{
    public const VIEW_ADMIN_DASHBOARD = 'view-admin-dashboard';
    public const MANAGE_USERS = 'manage-users';


    public static $permissionsArray = [
        self::VIEW_ADMIN_DASHBOARD,
        self::MANAGE_USERS,
    ];
}
// RoleOptions.php
class RoleOptions
{
    public const SUPER_ADMIN = [
        'name' => 'super-admin',
        'label' => 'Super Admin',
    ];
    public const ADMIN = [
        'name' => 'admin',
        'label' => 'Admin',
    ];
    public const USER = [
        'name' => 'user',
        'label' => 'User',
    ];

    public static $rolesArray = [
        self::SUPER_ADMIN,
        self::ADMIN,
        self::USER,
    ];
}
```

These are just the initial set of permissions and roles that will be called when you run the seeders.
As your application grows, these will very quickly become insufficient. The best way to handle this
is to generate your own `Options` classes and then point the config file at those instead.

The package comes with commands to do this:

-   `php artisan core:generate-options PermissionOptions`
-   `php artisan core:generate-options RoleOptions`

Running these commands will put the new options classes inside `App/Options`.
They will contain the base permissions and roles, but you are then free to add or remove as you require.

You will need to point the config values for permissions and roles to your new options:

```php
<?php

// Changed from Salt\Core\Options to App\Options
use App\Options\PermissionOptions;
use App\Options\RoleOptions;

return [
    'permissions' => PermissionOptions::$permissionsArray,
    'roles' => RoleOptions::$rolesArray
];

```

## Assigning permissions to roles

You will now have your own permissions and roles in the database. They won't be very useful yet
as the permissions are not associated with any roles. To fix this, you will need to add one more seeder, `RolePermissionSeeder`:

```php
// database/seeders/RolePermissionSeeder
<?php

namespace Database\Seeders;

use Salt\Core\Models\Role;
use Illuminate\Database\Seeder;
use Salt\Core\Models\Permission;
use App\Options\PermissionOptions;
use Salt\Core\Options\RoleOptions;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Set Super Admin permissions
        Role::where('name', RoleOptions::SUPER_ADMIN['name'])
            ->firstOrFail()
            ->permissions()
            ->sync(Permission::all());

        // Set Admin permissions
        $admin_permissions = [
            PermissionOptions::VIEW_ADMIN_DASHBOARD
        ];
        Role::where('name', RoleOptions::ADMIN['name'])
            ->firstOrFail()
            ->permissions()
            ->sync(Permission::whereIn('name', $admin_permissions)->get());
    }
}
```

Ensure that it is called in your `DatabaseSeeder`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ...
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RolePermissionSeeder::class);
    }
}

```

You can now use this seeder to define arrays of permissions that you then assign to various roles. Once you seed the databases,
your users will have the permissions associated with the roles they are linked to and you will be able to check permissions in your logic.
