<?php

namespace MigrateToFlarum\OldPasswords\Hashers;

class Sha1Bcrypt extends Sha1
{
    protected $bcryptVerification = true;
}
