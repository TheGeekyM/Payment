<?php

namespace App\Http\Services\Paymob;

use App\Http\Dtos\PaymentAssemblerDto;

class PaymobRequestBuilder
{
    public function build(PaymentAssemblerDto $paymentAssemblerDto): array
    {
        # order items
        $orderItemArray = [];
        foreach ($paymentAssemblerDto->getOrderDto()->getItems() as $item) {
            $orderItem = new OrderItem();
            $orderItem->setName($item->getTitle());
            $orderItem->setQuantity(1);
            $orderItem->setType($item->getCategory());
            $orderItem->setSku('SKU-123');
            $orderItem->setTotalAmount(new Money($item->getPrice(),  $paymentAssemblerDto->getOrderDto()->getCurrency()));
            $orderItem->setReferenceId($item->getId());
            $orderItemArray[] = $orderItem;
        }

        # billing address
        $billing = new Address();
        $billing->setFirstName($paymentAssemblerDto->getCustomerDto()->getName());
        $billing->setLastName($paymentAssemblerDto->getCustomerDto()->getName());
        $billing->setLine1($paymentAssemblerDto->getShippingAddressDto()->getAddress());
        $billing->setCity($paymentAssemblerDto->getShippingAddressDto()->getCity());
        $billing->setPhoneNumber($paymentAssemblerDto->getCustomerDto()->getPhone());
        $billing->setCountryCode('SA');

        # consumer
        $consumer = new Customer();
        $consumer->setFullName($paymentAssemblerDto->getCustomerDto()->getName());
        $consumer->setFirstName($paymentAssemblerDto->getCustomerDto()->getName());
        $consumer->setLastName($paymentAssemblerDto->getCustomerDto()->getName());
        $consumer->setEmail($paymentAssemblerDto->getCustomerDto()->getEmail());
        $consumer->setPhoneNumber($paymentAssemblerDto->getCustomerDto()->getPhone());

        # merchant urls
        $merchantUrl = new MerchantUrl();
        $merchantUrl->setSuccessUrl(url(config('tabby.callback_url')));
        $merchantUrl->setFailureUrl(url(config('tabby.callback_url')));
        $merchantUrl->setCancelUrl(url(config('tabby.callback_url')));
        $merchantUrl->setNotificationUrl(url(config('tabby.callback_url')));

        $order = new Order();
        $order->setOrderReferenceId($paymentAssemblerDto->getOrderDto()->getReferenceId());
        $order->setLocale($paymentAssemblerDto->getCustomerDto()->getLanguage());
        $order->setAmount(new Money($paymentAssemblerDto->getOrderDto()->getAmount(), $paymentAssemblerDto->getOrderDto()->getCurrency()));
        $order->setCountryCode("SA");
        $order->setPaymentType('PAY_BY_INSTALMENTS');
        $order->setDescription('order description');
        $order->setTaxAmount(new Money(0.00, $paymentAssemblerDto->getOrderDto()->getCurrency()));
        $order->setShippingAmount(new Money(0.00, $paymentAssemblerDto->getOrderDto()->getCurrency()));
        $order->setMerchantUrl($merchantUrl);
        $order->setConsumer($consumer);
        $order->setBillingAddress($billing);
        $order->setItems($orderItemArray);

        return $this->createCheckoutRequest($order);
    }

    /**
     * @param PaymentAssemblerDto $paymentAssemblerDto
     * @param string $token
     * @return array
     */
    public function buildOrderRegistration(PaymentAssemblerDto $paymentAssemblerDto, string $token): array
    {
        return [
            'auth_token' => $token,
            "amount_cents" => $paymentAssemblerDto->getOrderDto()->getAmount(),
            "delivery_needed" => "false",
            'currency' => $paymentAssemblerDto->getOrderDto()->getCurrency(),
            'notify_user_with_email' => TRUE,
            "items" => [
                [
                    "name" => "ASC1515",
                    "amount_cents" => $paymentAssemblerDto->getOrderDto()->getAmount(),
                    "description" => "Smart Watch",
                    "quantity" => "1"
                ]
            ],
        ];
    }

    /**
     * @param PaymentAssemblerDto $paymentAssemblerDto
     * @param string $token
     * @param int $orderId
     * @param int $integrationId
     * @return array
     */
    public function buildPaymentKeyRequest(PaymentAssemblerDto $paymentAssemblerDto, string $token, int $orderId, int $integrationId): array
    {
        return [
            'auth_token' => $token,
            "amount_cents" => $paymentAssemblerDto->getOrderDto()->getAmount(),
            'currency' => $paymentAssemblerDto->getOrderDto()->getCurrency(),
            'order_id' => $orderId,
            "expiration" => 3600,
            "integration_id" => $integrationId,
            "lock_order_when_paid" => "false",
            "billing_data" => [
                "first_name" => $paymentAssemblerDto->getCustomerDto()->getName(),
                "email" => $paymentAssemblerDto->getCustomerDto()->getEmail(),
                "apartment" => "NA",
                "floor" => "NA",
                "street" => $paymentAssemblerDto->getShippingAddressDto()->getAddress(),
                "building" => "NA",
                "phone_number" => $paymentAssemblerDto->getCustomerDto()->getPhone(),
                "shipping_method" => "NA",
                "postal_code" => $paymentAssemblerDto->getShippingAddressDto()->getZip(),
                "city" => $paymentAssemblerDto->getShippingAddressDto()->getCity(),
                "country" => "NA",
                "last_name" => "Nicolas",
                "state" => "NA"
            ]
        ];
    }
}
