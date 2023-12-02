<?php

namespace Domain\Order\Providers;

// use Illuminate\Services\Facades\Gate;
use Domain\Order\Models\Payment;
use Domain\Order\Payment\Gateways\YooKassa;
use Domain\Order\Payment\PaymentData;
use Domain\Order\Payment\PaymentSystem;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
//        PaymentSystem::provider(function (){
//            if (request()->has('unitpay')) {
//                return UnitPay();
//            }
//            return Yookassa();
//        });

        PaymentSystem::provider(function (){
            return new YooKassa(config('payment.providers.yookassa'));
        });

        PaymentSystem::onCreating(function (PaymentData $paymentData){

            return $paymentData;
        });

        PaymentSystem::onSuccess(function (Payment $payment){

            return $payment;
        });

        PaymentSystem::onError(function (string $message, Payment $payment){


        });

    }

    public function register()
    {

    }
}
