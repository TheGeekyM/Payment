<?php

namespace UnitTests\Payment\Doubles\Dummies\Entities;

use Payment\Entities\Address;

class BillingAddressDummy
{
    public static function get(): Address
    {
        $address = new Address();
        $address->setZipCode('0000');
        $address->setAddress('Elbahr st. Salah Eldin');
        $address->setFirstName('John Doe');
        $address->setCity('Cairo');
        $address->setCountry('Egypt');
        $address->setCountryCode('002');
        $address->setLastName('Mohamed Emad');
        $address->setPhoneNumber('01033333333333');

        return $address;
    }
}
