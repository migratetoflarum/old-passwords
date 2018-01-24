<?php

namespace MigrateToFlarum\OldPasswords\Hashers;

use Illuminate\Support\Arr;

class Bcrypt extends AbstractHasher
{
    protected $bcryptVerification = true;

    function check(string $password, array $oldPassword): bool
    {
        return $this->verifyPassword($password, Arr::get($oldPassword, 'password'));
    }
}
