<?php

namespace App\Http\Controllers;

use App\Http\Dtos\CreditDto;
use App\Http\Dtos\CustomerDto;
use App\Http\Dtos\ItemDto;
use App\Http\Dtos\OrderDto;
use App\Http\Dtos\PaymentDto;
use App\Http\Dtos\PaymentAssemblerDto;
use App\Http\Dtos\ShippingAddressDto;
use App\Http\Enums\PaymentGateways;
use App\Http\Enums\PaymentMethods;
use App\Http\Services\PaymentService;
use App\Http\Validations\PaymentValidation;
use App\Http\Validations\TabbyValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /**
     * @var PaymentService
     */
    private PaymentService $paymentService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * @throws ValidationException
     */
    public function pay(Request $request): JsonResponse
    {
        $paymentRequest = $request->get('payment');
        $customerRequest = $request->get('customer');
        $orderRequest = $request->get('order');
        $shippingAddressRequest = $request->get('shipping_address');

        $this->validate($request, PaymentValidation::rules());


        $paymentGateway = constant(PaymentGateways::class . "::" . $paymentRequest['gateway']);
        $paymentMethod = constant(PaymentMethods::class . "::" . $paymentRequest['method']);

        $paymentDto = new PaymentDto($paymentGateway, $paymentMethod);

        $itemsDto = collect($orderRequest['items'])->transform(function ($item) {
            return new ItemDto($item['id'], $item['title'], $item['sku'], $item['price'], $item['quantity'], $item['category']);
        })->toArray();

        $OrderDto = new OrderDto(
            $orderRequest['amount'],
            $orderRequest['currency'],
            $orderRequest['reference_id'],
            $itemsDto
        );

//        $CreditDto = new CreditDto(
//            $customerRequest['card_number'],
//            $customerRequest['expiry_date'],
//            $customerRequest['card_security_code']
//        );

        $customerDto = new CustomerDto(
            $customerRequest['id'],
            $customerRequest['name'],
            $customerRequest['email'],
            $customerRequest['ip'],
            $customerRequest['language'],
            $customerRequest['phone'],
        );

        $shippingAddressDto = new ShippingAddressDto(
            $shippingAddressRequest['country'],
            $shippingAddressRequest['city'],
            $shippingAddressRequest['address'],
            $shippingAddressRequest['zip'],
        );

        $paymentDto = new PaymentAssemblerDto($paymentDto, $OrderDto, $customerDto, $shippingAddressDto);

        $response = $this->paymentService->initTransaction($paymentDto);

        return response()->json(['data' => ['url' => $response->getUrl(), 'params' => $response->getParams()]]);
    }

    public function callback(Request $request, string $paymentGateway): JsonResponse
    {
        $paymentGateway = constant(PaymentGateways::class . '::' . $paymentGateway);
        $response = $this->paymentService->processResponse($request->all(), $paymentGateway);

        return response()->json(['data' => [
            'status' => $response->getStatus()->name,
            'reference_id' => $response->getReferenceId(),
            'order_id' => $response->getOrderId(),
            'data' => $response->getData()
        ]]);
    }
}
