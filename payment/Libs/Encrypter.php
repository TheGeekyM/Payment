<?php

namespace Payment\Libs;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Contracts\Encryption\StringEncrypter;
use Illuminate\Support\Facades\Log;
use JsonException;
use Payment\Contracts\EncrypterInterface;
use RuntimeException;

class Encrypter implements EncrypterInterface
{
    /**
     * The encryption key.
     *
     * @var string
     */
    private string $key;

    /**
     * The algorithm used for encryption.
     *
     * @var string
     */
    private string $cipher;

    /**
     * The supported cipher algorithms and their properties.
     *
     * @var array
     */
    private static array $supportedCiphers = [
        'aes-128-cbc' => ['size' => 16, 'aead' => TRUE],
        'aes-256-cbc' => ['size' => 32, 'aead' => TRUE],
        'aes-128-gcm' => ['size' => 16, 'aead' => TRUE],
        'aes-256-gcm' => ['size' => 32, 'aead' => TRUE],
    ];

    /**
     * Create a new encrypter instance.
     *
     * @param string $key
     * @param string $cipher
     * @return void
     *
     * @throws \RuntimeException
     */
    public function __construct(string $key, string $cipher = 'aes-256-gcm')
    {
        if (!static::supported($key, $cipher)) {
            $ciphers = implode(', ', array_keys(self::$supportedCiphers));

            throw new RuntimeException("Unsupported cipher or incorrect key length. Supported ciphers are: {$ciphers}.");
        }

        $this->key = $key;
        $this->cipher = $cipher;
    }

    /**
     * Determine if the given key and cipher combination is valid.
     *
     * @param string $key
     * @param string $cipher
     * @return bool
     */
    public static function supported(string $key, string $cipher): bool
    {
        if (!isset(self::$supportedCiphers[strtolower($cipher)])) {
            return FALSE;
        }

        return strlen($key) === self::$supportedCiphers[strtolower($cipher)]['size'];
    }

    /**
     * Create a new encryption key for the given cipher.
     *
     * @param string $cipher
     * @return string
     * @throws \Exception
     */
    public static function generateKey(string $cipher)
    {
        return random_bytes(self::$supportedCiphers[strtolower($cipher)]['size'] ?? 32);
    }

    /**
     * Encrypt the given value.
     *
     * @param mixed $value
     * @param bool $serialize
     * @return string
     *
     * @throws EncryptException|\Exception
     */
    public function encrypt(mixed $value, bool $serialize = TRUE): string
    {
        $iv = random_bytes(openssl_cipher_iv_length(strtolower($this->cipher)));

        $value = \openssl_encrypt(
            $serialize ? serialize($value) : $value,
            strtolower($this->cipher), $this->key, 0, $iv, $tag
        );

        if ($value === FALSE) {
            throw new EncryptException('Could not encrypt the data.');
        }

        $iv = base64_encode($iv);
        $tag = base64_encode($tag ?? '');

        $mac = self::$supportedCiphers[strtolower($this->cipher)]['aead']
            ? '' // For AEAD-algorithms, the tag / MAC is returned by openssl_encrypt...
            : $this->hash($iv, $value);

        $json = json_encode(compact('iv', 'value', 'mac', 'tag'), JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new EncryptException('Could not encrypt the data.');
        }

        return base64_encode($json);
    }

    /**
     * Encrypt a string without serialization.
     *
     * @param string $value
     * @return string
     *
     * @throws EncryptException|\Exception
     */
    public function encryptString(string $value): string
    {
        return $this->encrypt($value, FALSE);
    }

    /**
     * Decrypt the given value.
     *
     * @param string $payload
     * @param bool $unserialize
     * @return mixed
     *
     * @throws DecryptException|JsonException
     */
    public function decrypt(mixed $payload, bool $unserialize = TRUE)
    {
        $payload = $this->getJsonPayload($payload);

        $iv = base64_decode($payload['iv']);

        $this->ensureTagIsValid(
            $tag = empty($payload['tag']) ? NULL : base64_decode($payload['tag'])
        );

        // Here we will decrypt the value. If we are able to successfully decrypt it
        // we will then unserialize it and return it out to the caller. If we are
        // unable to decrypt this value we will throw out an exception message.
        $decrypted = \openssl_decrypt(
            $payload['value'], strtolower($this->cipher), $this->key, 0, $iv, $tag ?? ''
        );

        if ($decrypted === FALSE) {
            throw new DecryptException('Could not decrypt the data.');
        }

        return $unserialize ? unserialize($decrypted) : $decrypted;
    }

    /**
     * Decrypt the given string without unserialization.
     *
     * @param string $payload
     * @return string
     *
     * @throws DecryptException
     */
    public function decryptString(mixed $payload): string
    {
        return $this->decrypt($payload, FALSE);
    }

    /**
     * Create a MAC for the given value.
     *
     * @param string $iv
     * @param mixed $value
     * @return string
     */
    protected function hash($iv, $value)
    {
        return hash_hmac('sha256', $iv . $value, $this->key);
    }

    /**
     * Get the JSON array from the given payload.
     *
     * @param string $payload
     * @return array
     *
     * @throws DecryptException
     * @throws JsonException
     */
    protected function getJsonPayload($payload)
    {
        $payload = json_decode(base64_decode($payload), TRUE, 512, JSON_THROW_ON_ERROR);

        // If the payload is not valid JSON or does not have the proper keys set we will
        // assume it is invalid and bail out of the routine since we will not be able
        // to decrypt the given value. We'll also check the MAC for this encryption.
        if (!$this->validPayload($payload)) {
            throw new DecryptException('The payload is invalid.');
        }

        if (!self::$supportedCiphers[strtolower($this->cipher)]['aead'] && !$this->validMac($payload)) {
            throw new DecryptException('The MAC is invalid.');
        }

        return $payload;
    }

    /**
     * Verify that the encryption payload is valid.
     *
     * @param mixed $payload
     * @return bool
     */
    protected function validPayload($payload)
    {
        return is_array($payload) && isset($payload['iv'], $payload['value'], $payload['mac']) &&
            strlen(base64_decode($payload['iv'], TRUE)) === openssl_cipher_iv_length(strtolower($this->cipher));
    }

    /**
     * Determine if the MAC for the given payload is valid.
     *
     * @param array $payload
     * @return bool
     */
    protected function validMac(array $payload)
    {
        return hash_equals(
            $this->hash($payload['iv'], $payload['value']), $payload['mac']
        );
    }

    /**
     * Ensure the given tag is a valid tag given the selected cipher.
     *
     * @param string $tag
     * @return void
     */
    protected function ensureTagIsValid($tag)
    {
        if (self::$supportedCiphers[strtolower($this->cipher)]['aead'] && strlen($tag) !== 16) {
            throw new DecryptException('Could not decrypt the data.');
        }

        if (!self::$supportedCiphers[strtolower($this->cipher)]['aead'] && is_string($tag)) {
            throw new DecryptException('Unable to use tag because the cipher algorithm does not support AEAD.');
        }
    }

    /**
     * Get the encryption key that the encrypter is currently using.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
}
