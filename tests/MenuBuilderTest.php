<?php

use Salt\Core\Models\Role;
use Salt\Core\Models\User;

use Salt\Core\Models\Permission;
use Salt\Core\Models\MenuBuilder;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsArray;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertNotEquals;

it('can be initialized with an empty array and the current user', function () {
    $menu = MenuBuilder::new();

    assertInstanceOf(MenuBuilder::class, $menu);
});

it('can return the array of menu items', function () {
    // Empty menu
    $menu = MenuBuilder::new()
        ->build();

    assertIsArray($menu);

    $array =  [
        "section" => [
            [
                "text" => "Assessments",
                "icon" => "IconAssessment",
                "route" => "admin.assessments",
                "viewBox" => "0 0 20 20",
            ],
            [
                "text" => "Questions",
                "icon" => "IconQuestion",
                "route" => "admin.questions",
                "viewBox" => "0 0 32 32",
            ]
        ]
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

    assertEquals(['title' => array()], $menu);
});

it('can add a item to a section', function () {
    $sectionName = "content";

    $item = [
        'text' => "Assessments",
        'icon' => "IconAssessment",
        'route' => "admin.assessments",
        'viewBox' => "0 0 20 20"
    ];

    $menu = MenuBuilder::new()
        ->addSection($sectionName)
        ->addItem($sectionName, $item)
        ->build();

    assertArrayHasKey($sectionName, $menu);
    assertEquals($item, $menu[$sectionName][0]);
});


it('can add multiple items to a section at once', function () {
    $assessmentItem =  [
        'text' => "Assessments",
        'icon' => "IconAssessment",
        'route' => "admin.assessments",
        'viewBox' => "0 0 20 20"
    ];

    $questionItem =  [
        'text' => "Questions",
        'icon' => "IconQuestion",
        'route' => "admin.questions",
        'viewBox' => "0 0 32 32"
    ];

    $menu = MenuBuilder::new()
        ->addSection(
            "section",
            [
                $assessmentItem
            ],
            [
                $questionItem
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

    $assessmentItem = [
        'text' => "Assessments",
        'icon' => "IconAssessment",
        'route' => "admin.assessments",
        'viewBox' => "0 0 20 20"
    ];

    $userItem = [
        'text' => "Users",
        'icon' => "IconUser",
        'route' => "admin.users",
        'viewBox' => "0 0 20 20"
    ];

    actingAs($user);

    $menu = MenuBuilder::new()
        ->addSection('section')
        ->addItem('section', $assessmentItem, $assessmentPermission)
        ->addItem('section', $userItem, $userPermission)
        ->build();

    assertNotEquals($menu, ['section' => [$assessmentItem, $userItem]]);
    assertEquals($menu, ['section' => [$assessmentItem]]);


    // Alternative syntax
    $menu = MenuBuilder::new()
        ->addSection(
            'section',
            [
                $assessmentItem, $assessmentPermission
            ],
            [
                $userItem, $userPermission
            ]
        )
        ->build();

    assertNotEquals($menu, ['section' => [$assessmentItem, $userItem]]);
    assertEquals($menu, ['section' => [$assessmentItem]]);
});
