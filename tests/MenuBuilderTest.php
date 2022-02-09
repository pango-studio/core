<?php

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEquals;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertIsArray;

use function PHPUnit\Framework\assertNotEquals;

use Salt\Core\Data\MenuItem;
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
                "viewBox" => "0 0 20 20",
                "icon" => "IconAssessment",
            ],
            [
                "label" => "Questions",
                "route" => "admin.questions",
                "viewBox" => "0 0 32 32",
                "icon" => "IconQuestion",
            ],
        ],
    ];

    // Menu pre-filled with items
    $menu = MenuBuilder::new($array)
        ->build();

    assertEquals([$array], $menu);
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
        null,
        "IconAssessment",
        "0 0 20 20",
    );

    $menu = MenuBuilder::new()
        ->addItem($sectionName, $item)
        ->build();

    assertArrayHasKey($sectionName, $menu);
    assertEquals($item, $menu[$sectionName][0]);
});


it('can add multiple items to a section at once', function () {
    $assessmentItem = new MenuItem(
        "Assessments",
        "admin.assessments",
        null,
        "IconAssessment",
        "0 0 20 20",
    );

    $questionItem = new MenuItem(
        "Questions",
        "admin.questions",
        null,
        "IconQuestion",
        "0 0 32 32",
    );

    $menu = MenuBuilder::new()
        ->addSection(
            "section",
            [
                $assessmentItem,
            ],
            [
                $questionItem,
            ]
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
        null,
        "IconAssessment",
        "0 0 20 20",
    );

    $userItem = new MenuItem(
        "Users",
        "admin.users",
        null,
        "IconUser",
        "0 0 20 20",
    );

    actingAs($user);

    $menu = MenuBuilder::new()
        ->addSection('section')
        ->addItem('section', $assessmentItem, $assessmentPermission->name)
        ->addItem('section', $userItem, $userPermission->name)
        ->build();

    assertNotEquals($menu, ['section' => [$assessmentItem, $userItem]]);
    assertEquals($menu, ['section' => [$assessmentItem]]);


    // Alternative syntax
    $menu = MenuBuilder::new()
        ->addSection(
            'section',
            [
                $assessmentItem, $assessmentPermission->name,
            ],
            [
                $userItem, $userPermission->name,
            ]
        )
        ->build();

    assertNotEquals($menu, ['section' => [$assessmentItem, $userItem]]);
    assertEquals($menu, ['section' => [$assessmentItem]]);
});
