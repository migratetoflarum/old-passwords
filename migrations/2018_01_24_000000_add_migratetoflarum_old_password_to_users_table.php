<?php

use Flarum\Database\Migration;

return Migration::addColumns('users', [
    'migratetoflarum_old_password' => [
        'string',
        'length' => 255,
        'nullable' => true,
    ],
]);
