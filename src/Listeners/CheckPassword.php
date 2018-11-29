<?php

namespace MigrateToFlarum\OldPasswords\Listeners;

use Flarum\User\Event\CheckingPassword;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use MigrateToFlarum\OldPasswords\Hashers\HasherFactory;

class CheckPassword
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(CheckingPassword::class, [$this, 'checkPassword']);
    }

    public function checkPassword(CheckingPassword $event)
    {
        if (!is_null($event->user->migratetoflarum_old_password) && !empty($event->password)) {
            if (!empty($event->user->password)) {
                /**
                 * @var $hasher Hasher
                 */
                $hasher = app(Hasher::class);

                // If there is both a password and an old password, we'll check both of them
                // We first start with the Flarum one, and if it is correct we remove the old one from the database
                if ($hasher->check($event->password, $event->user->password)) {
                    $event->user->migratetoflarum_old_password = null;
                    $event->user->save();

                    return true;
                }
            }

            /**
             * @var $factory HasherFactory
             */
            $factory = app(HasherFactory::class);
            $oldPassword = json_decode($event->user->migratetoflarum_old_password, true);
            $checker = $factory->createHasher($oldPassword);

            // If the password matches the old password, we re-hash it with Flarum hasher and remove the old one from the database
            if ($checker->check($event->password, $oldPassword)) {
                // Password is automatically hashed by Flarum
                $event->user->password = $event->password;
                $event->user->migratetoflarum_old_password = null;
                $event->user->save();

                return true;
            }

            // If the password doesn't match we prevent login
            return false;
        }

        // If there's no value for the old password, we let the normal login process continue
        return null;
    }
}
