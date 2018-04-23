<?php

namespace MigrateToFlarum\OldPasswords\Hashers;

use Hautelook\Phpass\PasswordHash;
use Illuminate\Support\Arr;
use MigrateToFlarum\OldPasswords\Exceptions\MissingDependencyException;

class Phpass extends AbstractHasher
{
    function check(string $password, array $oldPassword): bool
    {
        if (!class_exists(PasswordHash::class)) {
            throw new MissingDependencyException('You need to install hautelook/phpass in order to check phpass hashes');
        }

        $passwordHasher = new PasswordHash(8, false);

        return $passwordHasher->CheckPassword($password, Arr::get($oldPassword, 'password'));
    }
}
