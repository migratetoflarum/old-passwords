<?php

namespace MigrateToFlarum\OldPasswords\Tests;

use Hautelook\Phpass\PasswordHash;
use MigrateToFlarum\OldPasswords\Hashers\HasherFactory;
use PHPUnit\Framework\TestCase;

class HasherTest extends TestCase
{
    protected function checkPassword(string $password, array $oldPassword): bool
    {
        /**
         * @var $factory HasherFactory
         */
        $factory = new HasherFactory();
        $hasher = $factory->createHasher($oldPassword);

        return $hasher->check($password, $oldPassword);
    }

    protected function assertHasherChecks(string $password, array $oldPassword)
    {
        $this->assertTrue($this->checkPassword($password, $oldPassword), "$password should match " . json_encode($oldPassword));
    }

    protected function assertHasherDoesntCheck(string $password, array $oldPassword)
    {
        $this->assertFalse($this->checkPassword($password, $oldPassword), "$password should match " . json_encode($oldPassword));
    }

    protected function bcrypt(string $value): string
    {
        return password_hash($value, PASSWORD_BCRYPT);
    }

    public function test_empty_values()
    {
        $this->assertHasherDoesntCheck('', [
            'type' => 'bcrypt',
            'password' => $this->bcrypt(''),
        ]);

        $this->assertHasherDoesntCheck('', [
            'type' => 'md5',
            'password' => '',
        ]);

        $this->assertHasherDoesntCheck('', [
            'type' => 'plain',
            'password' => '',
        ]);
    }

    public function test_bcrypt()
    {
        $this->assertHasherChecks('test', [
            'type' => 'bcrypt',
            'password' => $this->bcrypt('test'),
        ]);
    }

    public function test_md5()
    {
        $this->assertHasherChecks('test', [
            'type' => 'md5',
            'password' => md5('test'),
        ]);

        $this->assertHasherChecks('test', [
            'type' => 'md5',
            'password' => md5('usernametest'),
            'salt-before' => 'username',
        ]);

        $this->assertHasherChecks('test', [
            'type' => 'md5',
            'password' => md5('testusername'),
            'salt-after' => 'username',
        ]);
    }

    public function test_md5_bcrypt()
    {
        $this->assertHasherChecks('test', [
            'type' => 'md5-bcrypt',
            'password' => $this->bcrypt(md5('test')),
        ]);

        $this->assertHasherChecks('test', [
            'type' => 'md5-bcrypt',
            'password' => $this->bcrypt(md5('usernametest')),
            'salt-before' => 'username',
        ]);
    }

    public function test_md5_double()
    {
        $this->assertHasherChecks('test', [
            'type' => 'md5-double',
            'password' => md5(md5('test')),
        ]);

        $this->assertHasherChecks('test', [
            'type' => 'md5-double',
            'password' => md5('username' . md5('test')),
            'salt-before' => 'username',
        ]);
    }

    public function test_md5_double_bcrypt()
    {
        $this->assertHasherChecks('test', [
            'type' => 'md5-double-bcrypt',
            'password' => $this->bcrypt(md5(md5('test'))),
        ]);

        $this->assertHasherChecks('test', [
            'type' => 'md5-double-bcrypt',
            'password' => $this->bcrypt(md5('username' . md5('test'))),
            'salt-before' => 'username',
        ]);
    }

    public function test_phpass()
    {
        $this->assertHasherChecks('test', [
            'type' => 'phpass',
            'password' => (new PasswordHash(8, false))->HashPassword('test'),
        ]);

        $this->assertHasherChecks('test', [
            'type' => 'phpass',
            'password' => (new PasswordHash(8, true))->HashPassword('test'),
        ]);
    }

    public function test_plain()
    {
        $this->assertHasherChecks('test', [
            'type' => 'plain',
            'password' => 'test',
        ]);
    }

    public function test_sha1()
    {
        $this->assertHasherChecks('test', [
            'type' => 'sha1',
            'password' => sha1('test'),
        ]);

        $this->assertHasherChecks('test', [
            'type' => 'sha1',
            'password' => sha1('usernametest'),
            'salt-before' => 'username',
        ]);
    }

    public function test_sha1_bcrypt()
    {
        $this->assertHasherChecks('test', [
            'type' => 'sha1-bcrypt',
            'password' => $this->bcrypt(sha1('test')),
        ]);

        $this->assertHasherChecks('test', [
            'type' => 'sha1-bcrypt',
            'password' => $this->bcrypt(sha1('usernametest')),
            'salt-before' => 'username',
        ]);
    }

    public function test_sha1_double()
    {
        $this->assertHasherChecks('test', [
            'type' => 'sha1-double',
            'password' => sha1(sha1('test')),
        ]);

        $this->assertHasherChecks('test', [
            'type' => 'sha1-double',
            'password' => sha1('username' . sha1('test')),
            'salt-before' => 'username',
        ]);
    }

    public function test_sha1_double_bcrypt()
    {
        $this->assertHasherChecks('test', [
            'type' => 'sha1-double-bcrypt',
            'password' => $this->bcrypt(sha1(sha1('test'))),
        ]);

        $this->assertHasherChecks('test', [
            'type' => 'sha1-double-bcrypt',
            'password' => $this->bcrypt(sha1('username' . sha1('test'))),
            'salt-before' => 'username',
        ]);
    }
}
