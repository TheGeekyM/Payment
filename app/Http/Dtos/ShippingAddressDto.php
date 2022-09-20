<?php

namespace App\Http\Dtos;

class ShippingAddressDto
{
    private string $city;
    private string $address;
    private string $zip;
    private string $country;

    public function __construct(string $country, string $city, string $address, string $zip) {
        $this->city = $city;
        $this->address = $address;
        $this->zip = $zip;
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCity(): string
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
    public function getZip(): string
    {
        return $this->zip;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }
}
