<?php

namespace UnitTests\Payment\Doubles\Dummies\Entities;

use Payment\Entities\Customer;
use Payment\Entities\OrderItem;
use Payment\ValueObjects\Money;

class OrderItemsDummy
{
    /**
     * @throws \Exception
     */
    public static function get(): array
    {
        return [self::generateItems(), self::generateItems(), self::generateItems(), self::generateItems()];
    }

    /**
     * @throws \Exception
     */
    private static function generateItems(): OrderItem
    {
        $item = new OrderItem();
        $item->setTotalAmount(new Money('177', 'EGP'));
        $item->setReferenceId(substr(md5(mt_rand()), 0, 7));
        $item->setName('Item ' . random_int(10, 1000));
        $item->setCategory('category ' . random_int(10, 1000));

        return $item;
    }
}
