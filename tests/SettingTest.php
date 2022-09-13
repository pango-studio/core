<?php

use function Pest\Laravel\assertDatabaseHas;

use function Pest\Laravel\assertDatabaseMissing;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

use Salt\Core\Models\Setting;

beforeEach(function () {
    $this->key = "test-setting";
    $this->val = "some value";

    $this->setting = Setting::add($this->key, $this->val, 'string');
});

it('can add a new setting', function () {
    assertDatabaseHas(
        'settings',
        [
            'name' => $this->key,
            'value' => $this->val,
        ]
    );
});

it('can get a settings value', function () {
    $setting = Setting::get($this->key);

    assertEquals($this->val, $setting);
});

it('can set a value for an existing setting', function () {
    $new_val = 'New value';
    Setting::set($this->key, $new_val, 'string');

    assertDatabaseHas('settings', ['value' => $new_val]);
});

it('can remove a setting', function () {
    Setting::remove($this->key);

    assertDatabaseMissing('settings', ['value' => $this->val]);
});

it('can check if a setting exists', function () {
    assertTrue(Setting::has($this->key));
    assertFalse(Setting::has('some-random-key'));
});

it('can get all settings', function () {
    $all_settings = Setting::getAllSettings();
    assertCount(1, $all_settings);

    Setting::add('new-setting', 'new-setting', 'string');
    $all_settings = Setting::getAllSettings();
    assertCount(2, $all_settings);
});
