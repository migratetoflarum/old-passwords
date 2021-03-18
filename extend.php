<?php

namespace MigrateToFlarum\OldPasswords;

use Flarum\Extend;

return [
    (new Extend\Auth())
        ->addPasswordChecker('old-passwords', CheckPassword::class),
];
