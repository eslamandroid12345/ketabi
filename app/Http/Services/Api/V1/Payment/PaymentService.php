<?php

namespace App\Http\Services\Api\V1\Payment;

use App\Http\Requests\Api\V1\Payment\PaymentRequest;
use App\Http\Services\Api\V1\Payment\Helpers\Payable;
use App\Http\Services\Api\V1\Payment\Helpers\PaymentInvokableHelperService;
use App\Http\Services\Mutual\FileManagerService;
use App\Http\Traits\Responser;
use App\Repository\CartRepositoryInterface;
use App\Repository\PaymentRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    use Responser, Payable;

    public function __construct(
        private readonly PaymentRepositoryInterface $paymentRepository,
        private readonly PaymentInvokableHelperService $invoke,
        private readonly CartRepositoryInterface $cartRepository,
        private readonly FileManagerService $fileManager,
    )
    {
    }


    public function initiate(PaymentRequest $request) {

        DB::beginTransaction();
        try {
            $cart = $this->cartRepository->provide();

            if (!$this->cartRepository->isPayable($cart->id))
                return $this->responseFail(status:401, message: __('messages.Cart is empty'));

            $data = $request->validated();
            $data['status'] = 'pending';
            $data['amount'] = $request->amount ?? $cart->total_amount;
            $data['user_id'] = auth('api')->id();
            $data['cart_id'] = $cart->id;

            if ($request->transfer_image !== null)
                $data['transfer_image'] = $this->fileManager->handle('transfer_image', 'payments/transfer_images');

            $payment = $this->paymentRepository->create($data);

            $token = $request->token;

            return $this->invoke->{$this->methods[$data['method']]['invokable']}($payment, $cart, $token);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e);
//            return $e;
            return $this->responseFail(message: __('messages.Payment failed'));
        }
    }

    public function callback(Request $request)
    {
        return $this->invoke->callback($request);
    }

    public function query($id)
    {

        $payment = $this->paymentRepository->getById($id);

        if (!$payment->is_queried_before && $payment->user_id == auth('api')?->id()) {
            $this->paymentRepository->update($id, ['is_queried_before' => true]);
            return $this->responseSuccess(message: __('messages.Payment '. $payment->status), data: ['status' => $payment->status]);
        } else {
            return $this->responseFail(401, __('messages.You are not authorized to access this resource'));
        }
    }

    public function applePayValidation(Request $request)
    {
        $applepay_url = $request->vurl;
        $applepay_data = [
            'merchantIdentifier' => env('CLICKPAY_APPLE_PAY_MERCHANT_ID'),
            'displayName' => "Ktibe",
            'initiative' => "web",
            'initiativeContext' => env('CLICKPAY_APPLE_PAY_INITIATIVE_CONTEXT'),
        ];

        $headers = [
            'Content-Type: application/json',
        ];

        $post_params = json_encode($applepay_data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $applepay_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        curl_setopt($ch, CURLOPT_SSLCERT, asset('apple_pay/merchant-cert.crt'));
        curl_setopt($ch, CURLOPT_SSLKEY, asset('apple_pay/merchant-cert.key'));

        $result = curl_exec($ch);

        $error_num = curl_errno($ch);
        if ($error_num) {
            $error_msg = curl_error($ch);
            return ("Error response [($error_num) $error_msg], [$result]");
        }

        curl_close($ch);

        return $result;

    }

    /*
     *
     * 1- create payment record with pending status
     *
     * 2- invoke method depending on the payment method
     *      card : will activate it for now (not for production) and make the status confirmed
     *      bankTransfer : will upload the payment details and make the status being_reviewed
     *      ...
     *      ...
     *
     * 3- if the payment was confirmed, make subscription for all cart items depending on each item's details, then delete cart content
     * 4- if the payment was being_reviewed, keep cart content as it is but with soft delete until an action will be taken from dashboard
     *
     */

}
