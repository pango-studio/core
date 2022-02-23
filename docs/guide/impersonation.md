# Impersonation

As our applications become more dynamic in terms of what is available to users and how it is displayed, it becomes helpful to be able to 'see' what they see.
This is especially helpful when providing technical support. The UI that you have as an admin may not be the same as what the user is seeing,
due to having different roles and permissions, or due to the relationships that user has to other models in your app.

This package provides the ability to start an impersonation session. Once you are in this session, the user model that gets passed around is no longer yours,
it's the impersonated users. This allows you to experience the application as they would.

## Setup

The first thing you'll need to do is ensure that the user model passed to `config/core.php` is the model you are using in your app:

```php
// config/core.php
return [
    ...
    'user' => \App\Models\User::class // default is  \Salt\Core\Models\User::class
];
```

You then need to add the `HasImpersonation` trait on your user model

```php

use Salt\Core\Traits\HasImpersonation;

class User extends Authenticatable
{
    use HasImpersonation;
```

## Starting an impersonation session

To start an impersonation session, simply call `User::startImpersonation($id)`. `$id` must be the ID of the user you are impersonating.
You generally want to call this via a protected API route - `/admin/impersonate/` or something similar.

## Stopping an impersonation session

To stop impersonating the user, call `User::stopImpersonating()`. You don't need to pass the ID here as you can only be impersonating one user at a time.

## The current user facade

The easiest way to make sure you are fetching the impersonated user is by using the current user facade. By calling `CurrentUser::get()` you will
return the model for the authenticated user. However, if there is an active impersonation session it will return the model for the impersonated user instead.

```php

    public function getUserName()
    {
        $user = CurrentUser::get();
        return $user->name
    }

    $this->getUserName() // returns the name of the authenticated user

    User::startImpersonating($impersonatedUser->id);

    $this->getUserName() // retuns the name of the impersonated user
```

If you have some logic in your code that must always return the model of the authenticated user, you can disable impersonation with `CurrentUser::disableImpersonation()->get()`:

```php

    public function getUserName()
    {
        $user = CurrentUser::disableImpersonation()->get();
        return $user->name
    }

    $this->getUserName() // returns the name of the authenticated user

    User::startImpersonating($impersonatedUser->id);

    $this->getUserName() // still returns the name of the authenticated user
```
