<?php

namespace UnitTests\Payment\Entities;

use Payment\Entities\Address;
use Payment\Entities\Customer;
use Payment\Entities\Exceptions\InvalidNameProvided;
use PHPUnit\Framework\TestCase;

class CustomerTest  extends TestCase
{
    /**
     * @throws InvalidNameProvided
     */
    public function test_if_values_set_correctly_in_customer_entity(): void
    {
        $customer = new Customer();
        $customer->setPhoneNumber('01033333333333');
        $customer->setLastName('Mohamed Emad');
        $customer->setFirstName('Mohamed Emad');
        $customer->setId('1235');
        $customer->setEmail('user@user.com');
        $customer->setIp('127.0.0.1');

        $this->assertEquals('01033333333333', $customer->getPhoneNumber());
        $this->assertEquals('Mohamed Emad', $customer->getFullName());
        $this->assertEquals('Mohamed', $customer->getFirstName());
        $this->assertEquals('Emad', $customer->getLastName());
        $this->assertEquals('1235', $customer->getId());
        $this->assertEquals('user@user.com', $customer->getEmail());
        $this->assertEquals('127.0.0.1', $customer->getIp());
    }

    /**
     * @throws InvalidNameProvided
     */
    public function test_throw_exception_when_passing_an_empty_name(): void
    {
        $this->expectException(InvalidNameProvided::class);

        $customer = new Customer();
        $customer->setFirstName('');
    }

    /**
     * @throws InvalidNameProvided
     */
    public function test_throw_exception_when_passing_an_first_name_only(): void
    {
        $this->expectException(InvalidNameProvided::class);

        $customer = new Customer();
        $customer->setLastName('Mohamed');
    }
}
