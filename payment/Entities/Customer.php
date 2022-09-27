<?php

namespace Payment\Entities;

use Payment\Entities\Exceptions\InvalidNameProvided;

class Customer
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $phoneNumber;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $fullName;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $ip;

    /**
     * @throws InvalidNameProvided
     */
    public function setFirstName(string $firstName): void
    {
        $name = explode(' ', $firstName);

        if (!$firstName || !isset($name[0])) {
            throw new InvalidNameProvided('Invalid first name provided');
        }

        $this->firstName = $name[0];
    }

    /**
     * @throws InvalidNameProvided
     */
    public function setLastName(string $lastName): void
    {
        $name = explode(' ', $lastName);

        if (!isset($name[1])) {
            throw new InvalidNameProvided('Invalid first name provided');
        }

        $this->lastName = $name[1];
    }


    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }
}
