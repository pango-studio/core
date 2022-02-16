<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Salt\Core\Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('');

/**
 * Set the currently logged in user for the application.
 */
function actingAs($user, string $driver = null): TestCase
{
    return test()->actingAs($user, $driver);
}
