<?php

namespace MigrateToFlarum\OldPasswords;

use Flarum\Extend;
use Flarum\User\Event\CheckingPassword;

return [
    (new Extend\Event())
        ->listen(CheckingPassword::class, Listeners\CheckPassword::class),
];
