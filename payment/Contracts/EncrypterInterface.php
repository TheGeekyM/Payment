<?php

namespace Payment\Contracts;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;

interface EncrypterInterface
{
    /**
     * Encrypt the given value.
     *
     * @param mixed $value
     * @param bool $serialize
     * @return string
     *
     * @throws EncryptException
     */
    public function encrypt(mixed $value, bool $serialize = true);

    /**
     * Decrypt the given value.
     *
     * @param string $payload
     * @param bool $unserialize
     * @return mixed
     *
     * @throws DecryptException
     */
    public function decrypt(mixed $payload, bool $unserialize = TRUE);

    /**
     * Get the encryption key that the encrypter is currently using.
     *
     * @return string
     */
    public function getKey();
}
