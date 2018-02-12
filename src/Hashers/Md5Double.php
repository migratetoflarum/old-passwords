<?php

namespace MigrateToFlarum\OldPasswords\Hashers;

use Illuminate\Support\Arr;

class Md5Double extends AbstractHasher
{
    function check(string $password, array $oldPassword): bool
    {
        return $this->verifyPassword(md5($this->saltBeforeAfter(md5($password), $oldPassword)), Arr::get($oldPassword, 'password'));
    }
}
