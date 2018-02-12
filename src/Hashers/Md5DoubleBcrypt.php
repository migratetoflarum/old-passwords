<?php

namespace MigrateToFlarum\OldPasswords\Hashers;

class Md5DoubleBcrypt extends Md5Double
{
    protected $bcryptVerification = true;
}
