<?php

namespace MigrateToFlarum\OldPasswords\Hashers;

use Illuminate\Support\Arr;

class Sha1Double extends AbstractHasher
{
    function check(string $password, array $oldPassword): bool
    {
        return $this->verifyPassword(sha1($this->saltBeforeAfter(sha1($password), $oldPassword)), Arr::get($oldPassword, 'password'));
    }
}
