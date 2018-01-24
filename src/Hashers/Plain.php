<?php

namespace MigrateToFlarum\OldPasswords\Hashers;

use Illuminate\Support\Arr;

class Plain extends AbstractHasher
{
    function check(string $password, array $oldPassword): bool
    {
        return $this->verifyPassword($password, Arr::get($oldPassword, 'password'));
    }
}
