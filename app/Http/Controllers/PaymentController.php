<?php

namespace App\Http\Controllers;

use App\Http\Validations\PaymentValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use JetBrains\PhpStorm\NoReturn;
use Payment\Dtos\CustomerDto;
use Payment\Dtos\ItemDto;
use Payment\Dtos\OrderDto;
use Payment\Dtos\PaymentAssemblerDto;
use Payment\Dtos\PaymentDto;
use Payment\Dtos\ShippingAddressDto;
use Payment\Entities\Exceptions\InvalidTotalOrderAmountException;
use Payment\Enums\PaymentGateways;
use Payment\Enums\PaymentMethods;
use Payment\Services\PaymentService;

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
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException|InvalidTotalOrderAmountException
     */
    public function pay(Request $request): JsonResponse
    {
        $this->validate($request, PaymentValidation::rules());

        [$paymentRequest, $customerRequest, $orderRequest, $shippingAddressRequest] =
            [$request->get('payment'), $request->get('customer'), $request->get('order'), $request->get('shipping_address')];

        $paymentGateway = constant(PaymentGateways::class . "::" . $paymentRequest['gateway']);
        $paymentMethod = constant(PaymentMethods::class . "::" . $paymentRequest['method']);

        $paymentDto = new PaymentDto($paymentGateway, $paymentMethod);

        $itemsDto = collect($orderRequest['items'])->transform(function ($item) {
            return new ItemDto($item['reference_id'], $item['title'], $item['price'], $item['sku'], $item['quantity'],
                $item['category']);
        })->toArray();

        $OrderDto = new OrderDto(
            $orderRequest['amount'], $orderRequest['currency'], $orderRequest['reference_id'], $itemsDto
        );

        $customerDto = new CustomerDto(
            $customerRequest['id'], $customerRequest['name'], $customerRequest['email'],
            $customerRequest['ip'], $customerRequest['language'], $customerRequest['phone'],
        );

        $shippingAddressDto = new ShippingAddressDto(
            $shippingAddressRequest['country'], $shippingAddressRequest['city'],
            $shippingAddressRequest['address'], $shippingAddressRequest['zip'],
        );

        $paymentDto = new PaymentAssemblerDto($paymentDto, $OrderDto, $customerDto, $shippingAddressDto);

        $response = $this->paymentService->initTransaction($paymentDto);

        return response()->json(['data' => ['url' => $response->getUrl(), 'params' => $response->getParams()]]);
    }

    /**
     * @param Request $request
     * @param string $paymentGateway
     * @return JsonResponse
     */
    public function serverCallback(Request $request, string $paymentGateway): JsonResponse
    {
        $paymentGateway = constant(PaymentGateways::class . '::' . $paymentGateway);
        $response = $this->paymentService->processServerResponse($request->all(), $paymentGateway);

        return response()->json([
            'data' => [
                'status' => $response->getStatus()->name,
                'reference_id' => $response->getReferenceId(),
                'order_id' => $response->getOrderId(),
                'data' => $response->getData()
            ]]);
    }

    /**
     * @param Request $request
     * @param string $paymentGateway
     */
    #[NoReturn] public function clientCallback(Request $request, string $paymentGateway): void
    {
        $paymentGateway = constant(PaymentGateways::class . '::' . $paymentGateway);
        $url = $this->paymentService->processClientResponse($request->all(), $paymentGateway);

        header('Location: ' . $url);
        exit();
    }
}
