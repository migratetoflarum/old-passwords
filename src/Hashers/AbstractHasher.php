<?php

namespace MigrateToFlarum\OldPasswords\Hashers;

use Illuminate\Support\Arr;

abstract class AbstractHasher
{
    protected $bcryptVerification = false;

    protected function saltBeforeAfter(string $password, array $oldPassword): string
    {
        return Arr::get($oldPassword, 'salt-before', '') . $password . Arr::get($oldPassword, 'salt-after', '');
    }

    protected function verifyPassword($password, $hashedValue)
    {
        // We don't continue if one of the values is empty
        if (strlen($password) === 0 || strlen($hashedValue) === 0) {
            return false;
        }

        if ($this->bcryptVerification) {
            return password_verify($password, $hashedValue);
        }

        return $password === $hashedValue;
    }

    abstract function check(string $password, array $oldPassword): bool;
}
