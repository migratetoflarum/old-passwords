<?php

namespace MigrateToFlarum\OldPasswords\Hashers;

use Illuminate\Support\Arr;

class Kmd5 extends AbstractHasher
{
    /**
     * Unclassified Newsboard hash
     * Based on https://github.com/splitbrain/dokuwiki/blob/fe5b5b2a3afab46acfb4124abcb9cc73412ee07a/inc/PassHash.php#L348-L367
     * @param string $password
     * @param array $oldPassword
     * @return bool
     */
    function check(string $password, array $oldPassword): bool
    {
        $oldHashWithSalt = Arr::get($oldPassword, 'password');

        $salt = substr($oldHashWithSalt, 16, 2);

        $hash1 = strtolower(md5($salt . md5($password)));
        $hash2 = substr($hash1, 0, 16) . $salt . substr($hash1, 16);

        return $hash2 === $oldHashWithSalt;
    }
}
