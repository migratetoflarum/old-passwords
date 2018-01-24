<?php

namespace MigrateToFlarum\OldPasswords\Hashers;

use Illuminate\Support\Arr;

class Md5 extends AbstractHasher
{
    function check(string $password, array $oldPassword): bool
    {
        return $this->verifyPassword(md5($this->saltBeforeAfter($password, $oldPassword)), Arr::get($oldPassword, 'password'));
    }
}
