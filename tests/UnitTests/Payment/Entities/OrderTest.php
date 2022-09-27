<?php

namespace UnitTests\Payment\Entities;

use Exception;
use Payment\Entities\Address;
use Payment\Entities\Customer;
use Payment\Entities\Exceptions\AmountIsLessThanZeroException;
use Payment\Entities\Exceptions\InvalidTotalOrderAmountException;
use Payment\Entities\Order;
use Payment\Entities\OrderItem;
use Payment\Enums\PaymentGateways;
use Payment\Enums\PaymentMethods;
use Payment\ValueObjects\Money;
use PHPUnit\Framework\TestCase;
use UnitTests\Payment\Doubles\Dummies\Entities\BillingAddressDummy;
use UnitTests\Payment\Doubles\Dummies\Entities\CustomerDummy;
use UnitTests\Payment\Doubles\Dummies\Entities\OrderItemsDummy;

class OrderTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_create_an_order_instance(): void
    {
        $billing = BillingAddressDummy::get();
        $customer = CustomerDummy::get();
        $items = OrderItemsDummy::get();
        $amount = new Money('708.0', 'EGP');
        $shipping = new Money('10.50', 'EGP');
        $tax = new Money('10.50', 'EGP');
        $discount = new Money('10.50', 'EGP');
        $referenceId = substr(md5(mt_rand()), 0, 7);

        $order = new Order();
        $order->setPaymentGateway(PaymentGateways::paymob);
        $order->setPaymentMethod(PaymentMethods::visa);
        $order->setReferenceId($referenceId);
        $order->setAmount($amount);
        $order->setShippingAmount($shipping);
        $order->setTaxAmount($tax);
        $order->setDiscountAmount($discount);
        $order->setLocale('ar');

        $order->setItems($items);
        $order->setBillingAddress($billing);
        $order->setConsumer($customer);

        $this->assertEquals(PaymentGateways::paymob, $order->getPaymentGateway());
        $this->assertEquals(PaymentMethods::visa, $order->getPaymentMethod());
        $this->assertEquals($referenceId, $order->getReferenceId());
        $this->assertEquals($billing, $order->getBilling());
        $this->assertEquals($customer, $order->getConsumer());
        $this->assertEquals($items, $order->getItems());
        $this->assertEquals($amount, $order->getAmount());
        $this->assertEquals($shipping, $order->getShippingAmount());
        $this->assertEquals($discount, $order->getDiscountAmount());
        $this->assertEquals($tax, $order->getTaxAmount());
        $this->assertEquals('ar', $order->getLocale());
    }

    /**
     * @throws Exception
     */
    public function test_throw_Invalid_total_items_amount_if_it_not_equal_to_total_order_amount(): void
    {
        $this->expectException(InvalidTotalOrderAmountException::class);

        $items = OrderItemsDummy::get();
        $amount = new Money('100.0', 'EGP');

        $order = new Order();
        $order->setAmount($amount);
        $order->setItems($items);

        $order->calculateTotalAmount();
    }

    /**
     * @dataProvider subAmountsProvider
     * @throws Exception
     */
    public function test_order_total_amount_is_equal_to_order_total_amount(int $itemsTotal1, int $itemsTotal2, int $itemsTotal3, int $orderTotal): void
    {
        $items = [];
        $item = new OrderItem();
        $item->setTotalAmount(new Money($itemsTotal1, 'EGP'));
        $item->setReferenceId(substr(md5(mt_rand()), 0, 7));
        $item->setName('Item ' . random_int(10, 1000));
        $item->setCategory('category ' . random_int(10, 1000));
        $items[] = $item;

        $item = new OrderItem();
        $item->setTotalAmount(new Money($itemsTotal2, 'EGP'));
        $item->setReferenceId(substr(md5(mt_rand()), 0, 7));
        $item->setName('Item ' . random_int(10, 1000));
        $item->setCategory('category ' . random_int(10, 1000));
        $items[] = $item;

        $item = new OrderItem();
        $item->setTotalAmount(new Money($itemsTotal3, 'EGP'));
        $item->setReferenceId(substr(md5(mt_rand()), 0, 7));
        $item->setName('Item ' . random_int(10, 1000));
        $item->setCategory('category ' . random_int(10, 1000));
        $items[] = $item;


        $amount = new Money($orderTotal, 'EGP');

        $shipping = new Money('00.00', 'EGP');
        $tax = new Money('00.00', 'EGP');
        $discount = new Money('00.00', 'EGP');

        $order = new Order();
        $order->setAmount($amount);
        $order->setItems($items);
        $order->setShippingAmount($shipping);
        $order->setTaxAmount($tax);
        $order->setDiscountAmount($discount);

        $this->assertEquals($orderTotal, $order->calculateTotalAmount()->amount());
    }

    /**
     * @dataProvider amountsProvider
     * @throws Exception
     */
    public function test_order_total_amount_after_shipping_and_tax_and_discount(int $shipping, int $tax, int $discount, int $orderTotal): void
    {
        $items = OrderItemsDummy::get();
        $amount = new Money(708.0, 'EGP');

        $order = new Order();
        $order->setAmount($amount);
        $order->setItems($items);

        $order = new Order();
        $order->setAmount($amount);
        $order->setItems($items);

        $order->setShippingAmount(new Money($shipping, 'EGP'));
        $order->setTaxAmount(new Money($tax, 'EGP'));
        $order->setDiscountAmount(new Money($discount, 'EGP'));

        $this->assertEquals($orderTotal, $order->calculateTotalAmount()->amount());
    }

    /**
     * @throws Exception
     */
    public function test_throw_exception_if_order_total_amount_is_less_than_discount_amount(): void
    {
        $this->expectException(AmountIsLessThanZeroException::class);

        $items = OrderItemsDummy::get();
        $amount = new Money(708.0, 'EGP');

        $order = new Order();
        $order->setAmount($amount);
        $order->setItems($items);

        $order = new Order();
        $order->setAmount($amount);
        $order->setItems($items);

        $order->setShippingAmount(new Money(1, 'EGP'));
        $order->setTaxAmount(new Money(1, 'EGP'));
        $order->setDiscountAmount(new Money(10000, 'EGP'));
        $order->calculateTotalAmount();
    }

    public function subAmountsProvider(): array
    {
        return [
            [160, 250, 317, 727],
            [66377, 45452, 15364, 127193],
            [2, 842, 5494, 6338],
            [6294, 55, 21848, 28197],
        ];
    }

    public function amountsProvider(): array
    {
        return [
            [160, 250, 317, 801],
            [52160, 2250, 6317, 48801],
            [51160, 2505, 387, 53986],
            [16540, 5250, 457, 22041],
        ];
    }
}
