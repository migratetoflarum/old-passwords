<?php

namespace MigrateToFlarum\OldPasswords\Hashers;

use Illuminate\Support\Arr;
use MigrateToFlarum\OldPasswords\Exceptions\InvalidOldPasswordTypeException;

class HasherFactory
{
    protected $types = [
        'bcrypt' => Bcrypt::class,
        'kmd5' => Kmd5::class,
        'md5' => Md5::class,
        'md5-bcrypt' => Md5Bcrypt::class,
        'md5-double' => Md5Double::class,
        'md5-double-bcrypt' => Md5DoubleBcrypt::class,
        'phpass' => Phpass::class,
        'plain' => Plain::class,
        'sha1' => Sha1::class,
        'sha1-bcrypt' => Sha1Bcrypt::class,
        'sha1-double' => Sha1Double::class,
        'sha1-double-bcrypt' => Sha1DoubleBcrypt::class,
    ];

    public function createHasher(array $oldPassword): AbstractHasher
    {
        $type = Arr::get($oldPassword, 'type');
        $class = Arr::get($this->types, $type);

        if (!$class) {
            throw new InvalidOldPasswordTypeException("$type is not a registered password type");
        }

        return new $class;
    }
}
