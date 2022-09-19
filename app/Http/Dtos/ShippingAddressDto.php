<?php

namespace App\Http\Dtos;

class ShippingAddressDto
{
    private string $city;
    private string $address;
    private string $zip;

    public function __construct(string $city, string $address, string $zip) {
        $this->city = $city;
        $this->address = $address;
        $this->zip = $zip;
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
}
