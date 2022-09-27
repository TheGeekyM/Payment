<?php

namespace UnitTests\Payment\Doubles\Dummies\Entities;

use Payment\Entities\Customer;

class CustomerDummy
{
    public static function get(): Customer
    {
        $customer = new Customer();
        $customer->setPhoneNumber('01033333333333');
        $customer->setLastName('Mohamed Emad');
        $customer->setFirstName('Mohamed Emad');
        $customer->setId('1235');
        $customer->setEmail('user@user.com');
        $customer->setIp('127.0.0.1');

        return $customer;
    }
}
