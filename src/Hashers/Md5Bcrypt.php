<?php

namespace MigrateToFlarum\OldPasswords\Hashers;

class Md5Bcrypt extends Md5
{
    protected $bcryptVerification = true;
}
