<?php

namespace App\Http\Entities;

class Address
{
    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var mixed
     */
    private mixed $countryCode;

    /**
     * @var mixed
     */
    private mixed $phoneNumber;

    /**
     * @var mixed
     */
    private mixed $city;

    /**
     * @var string
     */
    private string $line1;

    public function setLine1(string $line1): void
    {
        $this->line1 = $line1;
    }


    public function setCity(mixed $city): void
    {
        $this->city = $city;
    }

    public function setPhoneNumber(mixed $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function setCountryCode(mixed $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return mixed
     */
    public function getCountryCode(): mixed
    {
        return $this->countryCode;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber(): mixed
    {
        return $this->phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getCity(): mixed
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getLine1(): string
    {
        return $this->line1;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $name = explode(' ', $lastName);
        $this->lastName = $name[1] ?? 'John';
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $name = explode(' ', $firstName);
        $this->firstName = $name[0] ?? 'John';
    }


}
