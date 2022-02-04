# Menu Builder

The menu builder aims to make building dynamic menus easier. It assumes your menu items are an array of key value pairs which
will be passed to your frontend for rendering

```php
    use Salt\Core\MenuBuilder;

    $userMenuItem = [
        'text' => "Users",
        'icon' => "IconUser",
        'route' => "admin.users",
        'viewBox' => "0 0 20 20"
    ];

    $menu = MenuBuilder::new()
        ->addItem("Admin", $userMenuItem)
        ->build();
```

## Initalizing the menu builder

To start constructing a new menu, you need to call `MenuBuilder::new()`. This will return an instance
of the `MenuBuilder` class which you can then start adding sections and items to.

You are also able to pass an entire menu array to `MenuBuilder::new()`. This can be helpful in cases
where you have an existing menu already and want to add onto it in some other location.

```php
    $existingMenuItems = MenuBuilder::new()
        ->addItem("Admin", $userMenuItem)
        ->build();

    $newMenu = MenuBuilder::new($existingMenuItems)
        ->addItem("Admin", $newMenuItem)
        ->build();

    // New menu will now have $userMenuItem and $newMenuItem in the "Admin section"

```

## Adding items to the menu

`public function addItem(string $sectionName, array $item, string $permission = null): static`

To add an item to the menu, you can use the `addItem()` method. This method takes a string `sectionName`,
which will determine what section in the menu the item is added to, If no section is found with that name,
it will add a new section. It then takes an array `$item` which will be added to the menu.

### Permissions

When the `MenuBuilder` is initialized with `MenuBuilder::new()`, it fetches the currently authenticated user.
You can pass the name of a permission as the final argument in `addItem()` to see if the user has that permission.
If they do not, then the menu item will not appear.

```php
    $menu = MenuBuilder::new()
        ->addItem('Admin', $adminMenuItem, 'view-assessments')
        ->build();

    // If the current user has the 'view-assessments' permission, the menu item will be visible for them
```

## Adding sections to the menu

`public function addSection(string $name, array ...$items): static`

You can add a new section to the menu with `addSection()`. This method requires a `name` to be passed:

```php
    $menu = MenuBuilder::new()
        ->addSection("Admin")  // ['Admin' => []]
```

You can also pass any number of `items` as to the section and they will be added to it as well. The items need
to be added as arrays:

```php
     $menu = MenuBuilder::new()
        ->addSection("Admin",
            [
                $adminMenuItem
            ],
            [
                $userMenuItem
            ]
        )
        ->build()
```

You can pass the name of the permission as the second item in the item array to exclude it if the user doesn't have that permission:

```php
     $menu = MenuBuilder::new()
        ->addSection("Admin",
            [
                $adminMenuItem,
                'view-admin-dashboard'
            ],
            [
                $userMenuItem
                'manage-users'
            ]
        )
        ->build()
```

## Add items to the menu conditionally

You don't need to build the menu all at once, you can break it up as needed in your application:

```php

$menu = MenuBuilder::new();

if ($user->hasCourses()) {
    $menu->addItem('Learn', $courseMenuItem)
};

if($library->isEnabled()) {
    $menu->addItem('Learn', $libraryMenuItem)
}

if($user->isTeacher()) {
    $menu->addSection('Insights',
        [
            $campusProgressMenu,
            'view-campus-progress'
        ],
        [
            $courseInsightMenu(),
            'view-course-progress'
        ]
    );
}

$menu->build();

```
