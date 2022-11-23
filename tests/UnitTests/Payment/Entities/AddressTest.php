<?php

namespace UnitTests\Payment\Entities;

use Payment\Entities\Address;
use Payment\Entities\Exceptions\InvalidNameProvided;
use PHPUnit\Framework\TestCase;
use UnitTests\Payment\Doubles\Dummies\Entities\BillingAddressDummy;

class AddressTest extends TestCase
{
    /**
     * @throws InvalidNameProvided
     */
    public function test_if_values_set_correctly_in_address_entity(): void
    {
        $address = new Address();
        $address->setZipCode('0000');
        $address->setAddress('Elbahr st. Salah Eldin');
        $address->setFirstName('John Doe');
        $address->setLastName('John Doe');
        $address->setCity('Cairo');
        $address->setCountry('Egypt');
        $address->setCountryCode('021033333333333');
        $address->setPhoneNumber('021033333333333');

        $this->assertEquals('0000', $address->getZipCode());
        $this->assertEquals('Elbahr st. Salah Eldin', $address->getAddress());
        $this->assertEquals('John', $address->getFirstName());
        $this->assertEquals('Doe', $address->getLastName());
        $this->assertEquals('Cairo', $address->getCity());
        $this->assertEquals('Egypt', $address->getCountry());
        $this->assertEquals('02', $address->getCountryCode());
        $this->assertEquals('021033333333333', $address->getPhoneNumber());
    }

    /**
     * @throws InvalidNameProvided
     */
    public function test_throw_exception_when_passing_an_empty_name(): void
    {
        $this->expectException(InvalidNameProvided::class);

        $address = new Address();
        $address->setFirstName('');
    }

    /**
     * @throws InvalidNameProvided
     */
    public function test_throw_exception_when_passing_an_first_name_only(): void
    {
        $this->expectException(InvalidNameProvided::class);

        $address = new Address();
        $address->setLastName('Mohamed');
    }
}
