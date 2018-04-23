# Old Passwords extension by MigrateToFlarum

[![Build status](https://travis-ci.org/migratetoflarum/old-passwords.svg?branch=master)](https://travis-ci.org/migratetoflarum/old-passwords) [![MIT license](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/migratetoflarum/old-passwords/blob/master/LICENSE.md) [![Latest Stable Version](https://img.shields.io/packagist/v/migratetoflarum/old-passwords.svg)](https://packagist.org/packages/migratetoflarum/old-passwords) [![Total Downloads](https://img.shields.io/packagist/dt/migratetoflarum/old-passwords.svg)](https://packagist.org/packages/migratetoflarum/old-passwords) [![Donate](https://img.shields.io/badge/paypal-donate-yellow.svg)](https://www.paypal.me/clarkwinkelmann)

This extension allows your users to continue to login with their passwords from a previous platform that was using a different hashing algorithm than Flarum.

## Installation

**This extension requires PHP7 or above**

Use [Bazaar](https://discuss.flarum.org/d/5151-flagrow-bazaar-the-extension-marketplace) or install manually:

```bash
composer require migratetoflarum/old-passwords
```

## Updating

```bash
composer update migratetoflarum/old-passwords
php flarum migrate
php flarum cache:clear
```

## Documentation

This extension is meant to be used alongside a migration script. There are no settings accessible from the UI (you still need to keep the extension enabled for it to work !)

The migrations add a `migratetoflarum_old_password` column to your `users` table, which can contain old credentials hashed with different algorithms than bcrypt.

This column must contain a valid JSON-serialized object as described below or `null` to not provide an old password.

Once a user was correctly identified via an old password, the password is re-hashed with bcrypt, stored in Flarum `password` field and the `migratetoflarum_old_password` column is set to `null`.

If you somehow manage to have both a bcrypt-hashed `password` and `migratetoflarum_old_password` value in the database for a user, then the user will be able to login with either password. The password used will override the value of `password` and `migratetoflarum_old_password` will be set to `null`.

## Compatible hashings

Don't hesitate to open an issue or a PR to suggest a new hashing method. More will be added soon.

While some of these options might be convenient for testing purposes or other shenanigans, some can put your old password's users at risk in case of a breach. These options are labelled with **/!\ Insecure**. Just as the whole extension, use these at your own risks !

### Plain

**/!\ Insecure**: you can directly salt and hash plain text passwords with bcrypt and store them in `password` instead.

Example:

```json
{"type":"plain","password":"correcthorsebatterystaple"}
```

### Bcrypt

It probably doesn't make sense to store a bcrypt hash here instead of the `password` column, but it is possible.

Example (password = `bcrypt(correcthorsebatterystaple)`):

```json
{"type":"bcrypt","password":"$2y$10$pUdywYeC2WZxZROQK0SPIu7x58OdO/aLxKnHRlfB8lni0aS6EEWdu"}
```

### Phpass

Reads portable and bcrypt hashes created with Phpass.

In order to use this type you need to install the [`hautelook/phpass`](https://packagist.org/packages/hautelook/phpass) package:

```bash
composer require hautelook/phpass:^1.1
```

Example (password = `portablehash(correcthorsebatterystaple)`):

```json
{"type":"phpass","password":"$P$Bdjwj4JGIZcMz02HOu69ULVYMPOMK5."}
```

### MD5

**/!\ Insecure**: with or without a salt MD5 stays weak. Consider using the `md5-bcrypt` option below.

Example (password = `md5(correcthorsebatterystaple)`):

```json
{"type":"md5","password":"e9f5bd2bae1c70770ff8c6e6cf2d7b76"}
```

Example with salt before the password (password = `md5(12345678correcthorsebatterystaple)`):

```json
{"type":"md5","password":"eefda52fc6b3747b14b563cef9c95062","salt-before":"12345678"}
```

Example with salt after the password (password = `md5(correcthorsebatterystaple12345678)`):

```json
{"type":"md5","password":"72d4f016727f69dcfb736fee65b079c8","salt-after":"12345678"}
```

### MD5-Bcrypt

This is the preferred method to import MD5 hashes.
You have to run every old MD5 password hash through bcrypt and store the resulting value in Flarum.

Example (password = `bcrypt(md5(correcthorsebatterystaple))`):

```json
{"type":"md5-bcrypt","password":"$2y$10$WTM5g/fgvJULmERFBpuv1.zqupDwav0/orAot5gWTpZ0xSCkW6tkq"}
```

Example (password = `bcrypt(md5(12345678correcthorsebatterystaple))`):

```json
{"type":"md5-bcrypt","password":"$2y$10$WTM5g/fgvJULmERFBpuv1.zqupDwav0/orAot5gWTpZ0xSCkW6tkq","salt-before":"12345678"}
```

You can use salts the same way as described for MD5.

## MD5-Double

**/!\ Insecure**: consider using the `md5-double-bcrypt` option below.

Same as MD5, but the password is hashed a first time before the salt is added.

Example (password = `md5(12345678 + md5(correcthorsebatterystaple))`):

```json
{"type":"md5-double","password":"75ed2cf45b78dfaa65915d83b73cee9b","salt-before":"12345678"}
```

You can use salts the same way as described for MD5.

## MD5-Double-Bcrypt

Same as MD5-Double, with an extra bcrypt layer.

Example (password = `bcrypt(md5(12345678 + md5(correcthorsebatterystaple)))`):

```json
{"type":"md5-double-bcrypt","password":"$2y$10$aws79gtmfZzV8/ikoJSCyuIVLDKlStBRvNDdJqAr1r6k4ZYjZmcC2","salt-before":"12345678"}
```

### SHA1

**/!\ Insecure**: with or without a salt SHA1 stays weak. Consider using the `sha1-bcrypt` option below.

Example (password = `sha1(correcthorsebatterystaple)`):

```json
{"type":"sha1","password":"bfd3617727eab0e800e62a776c76381defbc4145"}
```

You can use salts the same way as described for MD5.

### SHA1-Bcrypt

This is the preferred method to import SHA1 hashes.
You have to run every old SHA1 password hash through bcrypt and store the resulting value in Flarum.

Example (password = `bcrypt(sha1(correcthorsebatterystaple))`):

```json
{"type":"sha1","password":"$2y$10$b.K9J5Cc7FBJxtuy/hL/vuypT/2vn5jM42M6vpCFIKBfz9n.HAG2a"}
```

You can use salts the same way as described for MD5.

### SHA1-Double

**/!\ Insecure**: consider using the `sha1-double-bcrypt` option below.

Same as `md5-double` for sha1.

### SHA1-Double-Bcrypt

Same as `md5-double-bcrypt` for sha1.

## A MigrateToFlarum extension

This is a free extension by MigrateToFlarum, an online forum migration tool (launching soon).
Follow us on Twitter for updates https://twitter.com/MigrateToFlarum

Need a custom Flarum extension ? [Contact Clark Winkelmann !](https://clarkwinkelmann.com/flarum)

## Links

- [Flarum Discuss post](https://discuss.flarum.org/d/8631-old-passwords)
- [Source code on GitHub](https://github.com/migratetoflarum/old-passwords)
- [Report an issue](https://github.com/migratetoflarum/old-passwords/issues)
- [Download via Packagist](https://packagist.org/packages/migratetoflarum/old-passwords)
