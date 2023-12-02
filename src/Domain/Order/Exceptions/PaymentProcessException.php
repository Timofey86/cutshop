<?php

namespace Domain\Order\Exceptions;

class PaymentProcessException extends \Exception
{

    public static function paymentNotFound():self
    {
        return new self('Payment not found');
    }
}
