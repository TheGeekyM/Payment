<?php

namespace UnitTests\Payment\Entities;

use Payment\Entities\OrderItem;
use Payment\ValueObjects\Money;
use PHPUnit\Framework\TestCase;

class OrderItemTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function test_if_values_set_correctly_in_order_item_entity(): void
    {
        $item = new OrderItem();

        $amount = new Money('177', 'EGP');
        $referenceId = substr(md5(mt_rand()), 0, 7);
        $name = 'Item ' . random_int(10, 1000);
        $category = 'category ' . random_int(10, 1000);
        $sku = '125-ed';

        $item->setTotalAmount($amount);
        $item->setReferenceId($referenceId);
        $item->setName($name);
        $item->setCategory($category);
        $item->setSku($sku);


        $this->assertEquals($amount, $item->getTotalAmount());
        $this->assertEquals($referenceId, $item->getReferenceId());
        $this->assertEquals($name, $item->getName());
        $this->assertEquals($category, $item->getCategory());
        $this->assertEquals($sku, $item->getSku());
    }
}
