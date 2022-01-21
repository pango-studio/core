<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

it(' creates a new PermissionOptions class', function () {
    $className = "PermissionOptions";
    // destination path
    $optionsClass = app_path("database/options/$className.php");

    // make sure we're starting from a clean state
    if (File::exists($optionsClass)) {
        unlink($optionsClass);
    }

    assertFalse(File::exists($optionsClass));

    // Run the make command
    Artisan::call("core:generate-options $className");

    // Assert a new file is created
    assertTrue(File::exists($optionsClass));

    assertEquals(file_get_contents(__DIR__ . "/../../stubs/options/$className.php.stub"), file_get_contents($optionsClass));
});

it(' creates a new RoleOptions class', function () {
    $className = "RoleOptions";
    // destination path
    $optionsClass = app_path("database/options/$className.php");

    // make sure we're starting from a clean state
    if (File::exists($optionsClass)) {
        unlink($optionsClass);
    }

    assertFalse(File::exists($optionsClass));

    // Run the make command
    Artisan::call("core:generate-options $className");

    // Assert a new file is created
    assertTrue(File::exists($optionsClass));

    assertEquals(file_get_contents(__DIR__ . "/../../stubs/options/$className.php.stub"), file_get_contents($optionsClass));
});
