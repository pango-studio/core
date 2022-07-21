<?php

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEquals;

use function PHPUnit\Framework\assertInstanceOf;

use function PHPUnit\Framework\assertIsArray;

use function PHPUnit\Framework\assertNotEquals;
use Salt\Core\Data\MenuItem;
use Salt\Core\Data\MenuSectionItem;
use Salt\Core\Models\MenuBuilder;
use Salt\Core\Models\Permission;
use Salt\Core\Models\Role;
use Salt\Core\Models\User;

it('can be initialized with an empty array and the current user', function () {
    $menu = MenuBuilder::new();

    assertInstanceOf(MenuBuilder::class, $menu);
});

it('can return the array of menu items', function () {
    // Empty menu
    $menu = MenuBuilder::new()
        ->build();

    assertIsArray($menu);

    $array = [
        "section" => [
            [
                "label" => "Assessments",
                "route" => "admin.assessments",
                "icon" => "IconAssessment",
            ],
            [
                "label" => "Questions",
                "route" => "admin.questions",
                "icon" => "IconQuestion",
            ],
        ],
    ];

    // Menu pre-filled with items
    $menu = MenuBuilder::new($array)
        ->build();

    assertEquals([$array], $menu);
});

it('can add an item to the menu', function () {
    $item = new MenuItem("Assessments", "admin.assessments", "IconAssessment");
    $menu = MenuBuilder::new()
        ->addItem($item)
        ->build();

    assertEquals([$item], $menu);
});

it('can add a sub menu to items', function () {
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

    $menu = MenuBuilder::new()
        ->addItem($item)
        ->build();


    assertEquals($subMenu, $menu[0]->subMenu);
});

it('can add a new section to the menu', function () {
    $menu = MenuBuilder::new()
        ->addSection("title")
        ->build();

    assertEquals(['title' => []], $menu);
});

it('can add a item to a section', function () {
    $sectionName = "content";


    $item = new MenuItem(
        "Assessments",
        "admin.assessments",
        "IconAssessment",
    );

    $menu = MenuBuilder::new()
        ->addItem($item, $sectionName)
        ->build();

    assertArrayHasKey($sectionName, $menu);
    assertEquals($item, $menu[$sectionName][0]);
});


it('can add multiple items to a section at once', function () {
    $assessmentItem = new MenuItem(
        "Assessments",
        "admin.assessments",
        "IconAssessment",
    );

    $questionItem = new MenuItem(
        "Questions",
        "admin.questions",
        "IconQuestion",
    );

    $menu = MenuBuilder::new()
        ->addSection(
            "section",
            new MenuSectionItem($assessmentItem),
            new MenuSectionItem($questionItem)
        )
        ->build();

    assertEquals($assessmentItem, $menu['section'][0]);
    assertEquals($questionItem, $menu['section'][1]);
});

it("can hide a menu item if the current user doesn't have permission to view it", function () {
    $user = User::create(['name' => 'section', 'email' => 'test@test.com']);

    $assessmentPermission = Permission::factory()->create(['name' => 'view-assessments']);
    $userPermission = Permission::factory()->create(['name' => 'manage-users']);

    $role = Role::factory()->create(['name' => 'admin']);
    $role->permissions()->attach($assessmentPermission);
    $user->roles()->syncWithoutDetaching($role);

    $assessmentItem = new MenuItem(
        "Assessments",
        "admin.assessments",
        "IconAssessment",
    );

    $userItem = new MenuItem(
        "Users",
        "admin.users",
        "IconUser",
    );

    actingAs($user);

    $menu = MenuBuilder::new()
        ->addSection('section')
        ->addItem($assessmentItem, 'section', $assessmentPermission->name)
        ->addItem($userItem, 'section', $userPermission->name)
        ->build();

    assertNotEquals($menu, ['section' => [$assessmentItem, $userItem]]);
    assertEquals($menu, ['section' => [$assessmentItem]]);


    // Alternative syntax
    $menu = MenuBuilder::new()
        ->addSection(
            'section',
            new MenuSectionItem($assessmentItem, $assessmentPermission->name),
            new MenuSectionItem($userItem, $userPermission->name)
        )
        ->build();

    assertNotEquals($menu, ['section' => [$assessmentItem, $userItem]]);
    assertEquals($menu, ['section' => [$assessmentItem]]);
});

it('shows the menu items that the impersonated user is allowed to see', function () {
    $admin = User::factory()->create();
    $user = User::factory()->create();

    $assessmentPermission = Permission::factory()->create(['name' => 'view-assessments']);
    $userPermission = Permission::factory()->create(['name' => 'manage-users']);

    $adminRole = Role::factory()->create(['name' => 'admin']);
    $adminRole->permissions()->attach($assessmentPermission);
    $adminRole->permissions()->attach($userPermission);
    $admin->roles()->syncWithoutDetaching($adminRole);


    $editorRole = Role::factory()->create(['name' => 'editor']);
    $editorRole->permissions()->attach($assessmentPermission);
    $user->roles()->syncWithoutDetaching($editorRole);

    $assessmentItem = new MenuItem(
        "Assessments",
        "admin.assessments",
        "IconAssessment",
    );

    $userItem = new MenuItem(
        "Users",
        "admin.users",
        "IconUser",
    );

    actingAs($admin);

    $menu = MenuBuilder::new()
        ->addSection(
            'section',
            new MenuSectionItem($assessmentItem, $assessmentPermission->name),
            new MenuSectionItem($userItem, $userPermission->name)
        );
    $menu->build();
    // The admin user sees both menu items
    assertEquals($menu->getMenu(), ['section' => [$assessmentItem, $userItem]]);

    User::startImpersonating($user->id);

    $menu = MenuBuilder::new()
        ->addSection(
            'section',
            new MenuSectionItem($assessmentItem, $assessmentPermission->name),
            new MenuSectionItem($userItem, $userPermission->name)
        );
    $menu = $menu->build();

    // The impersonated user only sees the assessment menu item
    assertEquals($menu, ['section' => [$assessmentItem]]);
});
