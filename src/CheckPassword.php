<?php

namespace MigrateToFlarum\OldPasswords;

use Flarum\User\User;
use Illuminate\Contracts\Hashing\Hasher;
use MigrateToFlarum\OldPasswords\Hashers\HasherFactory;

class CheckPassword
{
    protected $flarumHasher;
    protected $oldHasherFactory;

    public function __construct(Hasher $flarumHasher, HasherFactory $oldHasherFactory)
    {
        $this->flarumHasher = $flarumHasher;
        $this->oldHasherFactory = $oldHasherFactory;
    }

    public function __invoke(User $user, string $password): ?bool
    {
        if (is_null($user->migratetoflarum_old_password) || empty($password)) {
            return null;
        }

        if (!empty($user->password)) {
            // If there is both a password and an old password, we'll check both of them
            // We first start with the Flarum one, and if it is correct we remove the old one from the database
            if ($this->flarumHasher->check($password, $user->password)) {
                $user->migratetoflarum_old_password = null;
                $user->save();

                // It's redundant to return true here since Flarum's standard checker will also return true,
                // But this allows exiting the function early
                return true;
            }
        }

        $oldPassword = json_decode($user->migratetoflarum_old_password, true);
        $checker = $this->oldHasherFactory->createHasher($oldPassword);

        // If the password matches the old password, we re-hash it with Flarum hasher and remove the old one from the database
        if ($checker->check($password, $oldPassword)) {
            // Password is automatically hashed by Flarum
            $user->password = $password;
            $user->migratetoflarum_old_password = null;
            $user->save();

            return true;
        }

        return null;
    }
}
