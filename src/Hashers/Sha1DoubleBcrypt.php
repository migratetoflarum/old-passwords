<?php

namespace MigrateToFlarum\OldPasswords\Hashers;

class Sha1DoubleBcrypt extends Sha1Double
{
    protected $bcryptVerification = true;
}
