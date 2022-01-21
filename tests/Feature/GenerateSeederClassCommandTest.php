<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

it(' creates a new PermissionSeeder class', function () {
    $className = "PermissionSeeder";
    // destination path
    $seederClass = app_path("database/seeders/$className.php");

    // make sure we're starting from a clean state
    if (File::exists($seederClass)) {
        unlink($seederClass);
    }

    assertFalse(File::exists($seederClass));

    // Run the make command
    Artisan::call("core:generate-seeder $className");

    // Assert a new file is created
    assertTrue(File::exists($seederClass));

    assertEquals(file_get_contents(__DIR__ . "/../../stubs/seeders/$className.php.stub"), file_get_contents($seederClass));
});

it(' creates a new RoleSeeder class', function () {
    $className = "RoleSeeder";
    // destination path
    $seederClass = app_path("database/seeders/$className.php");

    // make sure we're starting from a clean state
    if (File::exists($seederClass)) {
        unlink($seederClass);
    }

    assertFalse(File::exists($seederClass));

    // Run the make command
    Artisan::call("core:generate-seeder $className");

    // Assert a new file is created
    assertTrue(File::exists($seederClass));

    assertEquals(file_get_contents(__DIR__ . "/../../stubs/seeders/$className.php.stub"), file_get_contents($seederClass));
});

it(' creates a new RolePermissionSeeder class', function () {
    $className = "RolePermissionSeeder";
    // destination path
    $seederClass = app_path("database/seeders/$className.php");

    // make sure we're starting from a clean state
    if (File::exists($seederClass)) {
        unlink($seederClass);
    }

    assertFalse(File::exists($seederClass));

    // Run the make command
    Artisan::call("core:generate-seeder $className");

    // Assert a new file is created
    assertTrue(File::exists($seederClass));

    assertEquals(file_get_contents(__DIR__ . "/../../stubs/seeders/$className.php.stub"), file_get_contents($seederClass));
});
