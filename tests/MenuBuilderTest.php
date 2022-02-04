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
    $menu = MenuBuilder::new()
        ->build();

    assertIsArray($menu);
});

it('can add a new section to the menu', function () {
    $menu = MenuBuilder::new()
        ->addSection("title")
        ->build();

    assertEquals(['title' => array()], $menu);
});

it('can add a link to a section', function () {
    $sectionName = "content";

    $link = [
        'text' => "Assessments",
        'icon' => "IconAssessment",
        'route' => "admin.assessments",
        'viewBox' => "0 0 20 20"
    ];

    $menu = MenuBuilder::new()
        ->addSection($sectionName)
        ->addLink($sectionName, $link)
        ->build();

    assertArrayHasKey($sectionName, $menu);
    assertEquals($link, $menu[$sectionName][0]);
});


it('can add multiple links to a section at once', function () {
    $assessmentLink =  [
        'text' => "Assessments",
        'icon' => "IconAssessment",
        'route' => "admin.assessments",
        'viewBox' => "0 0 20 20"
    ];

    $questionLink =  [
        'text' => "Questions",
        'icon' => "IconQuestion",
        'route' => "admin.questions",
        'viewBox' => "0 0 32 32"
    ];

    $menu = MenuBuilder::new()
        ->addSection(
            "section",
            [
                $assessmentLink
            ],
            [
                $questionLink
            ]
        )
        ->build();

    assertEquals($assessmentLink, $menu['section'][0]);
    assertEquals($questionLink, $menu['section'][1]);
});

it("can hide a menu item if the current user doesn't have permission to view it", function () {
    $user = User::create(['name' => 'section', 'email' => 'test@test.com']);

    $assessmentPermission = Permission::factory()->create(['name' => 'view-assessments']);
    $userPermission = Permission::factory()->create(['name' => 'manage-users']);

    $role = Role::factory()->create(['name' => 'admin']);
    $role->permissions()->attach($assessmentPermission);
    $user->roles()->syncWithoutDetaching($role);

    $assessmentLink = [
        'text' => "Assessments",
        'icon' => "IconAssessment",
        'route' => "admin.assessments",
        'viewBox' => "0 0 20 20"
    ];

    $userLink = [
        'text' => "Users",
        'icon' => "IconUser",
        'route' => "admin.users",
        'viewBox' => "0 0 20 20"
    ];

    actingAs($user);

    $menu = MenuBuilder::new()
        ->addSection('section')
        ->addLink('section', $assessmentLink, $assessmentPermission)
        ->addLink('section', $userLink, $userPermission)
        ->build();

    assertNotEquals($menu, ['section' => [$assessmentLink, $userLink]]);
    assertEquals($menu, ['section' => [$assessmentLink]]);


    // Alternative syntax
    $menu = MenuBuilder::new()
        ->addSection(
            'section',
            [
                $assessmentLink, $assessmentPermission
            ],
            [
                $userLink, $userPermission
            ]
        )
        ->build();

    assertNotEquals($menu, ['section' => [$assessmentLink, $userLink]]);
    assertEquals($menu, ['section' => [$assessmentLink]]);
});
