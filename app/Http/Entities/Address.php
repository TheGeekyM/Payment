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
     * @var mixed
     */
    private mixed $country;

    /**
     * @var string
     */
    private string $address;

    /**
     * @var string
     */
    private string $zip_code;

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }


    public function setCity(mixed $city): void
    {
        $this->city = $city;
    }

    public function setPhoneNumber(mixed $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function setCountryCode(mixed $country): void
    {
        $this->countryCode = strtoupper(substr($country, 0 , 2));
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
    public function getAddress(): string
    {
        return $this->address;
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

    /**
     * @return mixed
     */
    public function getCountry(): mixed
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry(mixed $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->zip_code;
    }

    /**
     * @param string $zip_code
     */
    public function setZipCode(string $zip_code): void
    {
        $this->zip_code = $zip_code;
    }
}
