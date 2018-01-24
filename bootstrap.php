<?php

namespace MigrateToFlarum\OldPasswords;

use Illuminate\Contracts\Events\Dispatcher;

return function (Dispatcher $events) {
    $events->subscribe(Listeners\CheckPassword::class);
};
