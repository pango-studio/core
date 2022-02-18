# Menu Builder

The menu builder aims to make building dynamic menus easier. It allows you to

-   Create a menu, add items to it and return the menu as an array
-   Divide the menu into sections
-   Add multiple levels of submenus
-   Hide specific menu items from users who don't have permission to access them (hence the 'dynamic' part)

## Initalizing the menu builder

To start constructing a new menu, you need to call `MenuBuilder::new()`. This will return an instance
of the `MenuBuilder` class which you can then start adding sections and items to.

You are also able to pass an entire menu array to `MenuBuilder::new()`. This can be helpful in cases
where you have an existing menu already and want to add onto it in some other location.

## Menu items

### `MenuItem` data type

Here's the constructor for the `MenuItem` data type:

```php
    /**
     * @param string $label The label of the menu item
     * @param string $route The name of the Laravel route which the menu item links to
     * @param string|null $icon The name of the icon to be shown next to the label
     * @param string $viewBox The viewbox of the icon component
     * @param MenuItem[]|null $subMenu
     */
    public function __construct(
        public string $label,
        public string $route,
        public string|null $icon = null,
        public string $viewBox = "0 0 32 32",
        public array|null $subMenu = null
    ) {
    }
```

You can create a new menu item either by using `new MenuItem()` like this:

```php
    $item = new MenuItem(
        "Users",
        "admin.users",
        "IconUser",
    );
```

or in an array format:

```php
    $item = MenuItem::from([
        "label" => "Users",
        "route" => "admin.users",
        "icon" => "IconUser",
    ]);
```

This is done using the [spatie/laravel-data](https://github.com/spatie/laravel-data) package so you can view those docs for more info.

### Adding items to the menu

`public function addItem(MenuItem $item, string $sectionName = null, string $permission = null): static`

To add an item to the menu, you can use the `addItem()` method. You need to pass a `MenuItem` data object as the first argument.

```php
    use Salt\Core\MenuBuilder;

    $userMenuItem = new MenuItem(
        "Manage users",
        "admin.users",
        "IconUser",
    );

    $menu = MenuBuilder::new()
        ->addItem($userMenuItem)
        ->build();

//  $menu => [
//     [
//        "label" => "Manage users",
//        "route" => "admin.users",
//        "icon" => "IconUsers",
//     ]
//  ]

```

If you just add the menu item, it will be added directly to the menu. However if you add a section name as the second argument, it will place the item in a sub array which has the given name as the index.

```php

$menu = MenuBuilder::new()
    ->addItem($adminMenuItem, "Admin")
    ->build();

// $menu => [
//    "Admin" => [
//         [
//            "label" => "Manage users",
//            "route" => "admin.users",
//            "icon" => "IconUsers",
//         ]
//     ]
//  ]
```

Finally, you can add the name of a permission as the third argument. If the current user does not have that permission, then the menu item will not show up for them.

```php
    $menu = MenuBuilder::new()
        ->addItem($adminMenuItem, 'Admin', 'view-assessments')
        ->build();

    // If the current user has the 'view-assessments' permission,
    // the menu item will be visible for them
```

### Adding submenus

A `MenuItem` can have a submenu, which itself takes in an array of `MenuItem`s. Each of those could then also have their own submenus, so you can technically have any number of nested submenus if you wanted to.

You can add the submenu as a property:

```php
    $menuItem = new MenuItem(
        "Manage users",
        "admin.users",
        "IconUser",
        [
            new MenuItem("Archived users", "admin.users.archived"),
            new MenuItem("Add new user", "admin.users.create"),
            new MenuItem("Import new users", "admin.users.import"),
        ]

    )
```

Or use the `addSubMenu()` method:

```php
    $item = new MenuItem(
        "Users",
        "admin.users",
        "IconUser",
    );

    $subMenu = [
        new MenuItem("Archived users", "admin.users.archived"),
        new MenuItem("Add new user", "admin.users.create"),
        new MenuItem("Import new users", "admin.users.import"),
    ];

    $item->addSubMenu(
        ...$subMenu
    );
```

## Menu sections

If you want to group menu items together, you can add a section and then place the items in that instead of adding them directly to the root of the menu.

### Adding sections to the menu

`public function addSection(string $name, MenuSectionItem ...$items): static`

You can add a new section to the menu with `addSection()`. This method requires a `name` to be passed:

```php
    $menu = MenuBuilder::new()
        ->addSection("Admin")  // ['Admin' => []]
```

An empty section is not very useful though. You can pass any number of `MenuSectionitem`s as to the section and they will be added to it as well:

```php
     $menu = MenuBuilder::new()
        ->addSection(
            "Admin",
            new MenuSectionItem($userMenuItem),
            new MenuSectionItem($settingsMenuItem)
        )
        ->build()
```

The `MenuSectionItem` data type takes two arguments, `$data` and `$permission`:

```php
/**
     * @param MenuItem $data The menu item to be added to the section
     * @param string|null $permission The permission that the user needs in order to view the menu item
     */
    public function __construct(
        public MenuItem $data,
        public ?string $permission = null
    ) {
    }
```

`$data` must be a `MenuItem`. `$permission` is optional - if added, then it will determine whether or not that item can be shown to the current user based on their permissions

```php
     $menu = MenuBuilder::new()
        ->addSection(
            "section",
            new MenuSectionItem($userMenuItem, 'manage-users'),
            new MenuSectionItem($settingsMenuItem, 'manage-settings')
        )
        ->build()
```

## Add items to the menu conditionally

You don't need to build the menu all at once, you can break it up as needed in your application:

```php

$menu = MenuBuilder::new();

if ($user->hasCourses()) {
    $menu->addItem($courseMenuItem, "Learn")
};

if($library->isEnabled()) {
    $menu->addItem($libraryMenuItem, "Learn")
}

if($user->isTeacher()) {
    $menu->addSection('Insights',
        new MenuSectionItem($campusProgressMenu,'view-campus-progress'),
        new MenuSectionItem($courseInsightMenu,'view-course-progress')
    );
}

$menu->build();

```
