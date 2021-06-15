<?php


namespace SIMP2\Tests\Unit;

use SIMP2\SDK\SIMP2SDK;
use SIMP2\Tests\SDKTestCase;
use SIMP2\SDK\Enums\SIMP2Endpoint;
use Illuminate\Support\Facades\Http;
use SIMP2\SDK\Exceptions\SavePaymentException;
use SIMP2\SDK\Exceptions\PaymentNotFoundException;
use SIMP2\SDK\Exceptions\PaymentAlreadyNotifiedException;


class NotifyPaymentTest extends SDKTestCase
{

    /** @test */
    public function ShouldThrowCustomExceptionForPayments()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::notifyPaymentEndpoint) => Http::response([], 500, self::headers())]);
        $this->expectException(SavePaymentException::class);
        try {
            (new SIMP2SDK)->notifyPayment('123');
        } catch (PaymentNotFoundException | PaymentAlreadyNotifiedException) {
            $this->assertTrue(false, 'Should not throw.');
        }
    }

    /** @test */
    public function ShouldThrowCustomExceptionFor422ErrorNotifyPayment()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::notifyPaymentEndpoint) => Http::response([], 422, self::headers())]);
        $this->expectException(SavePaymentException::class);
        try {
            (new SIMP2SDK)->notifyPayment('123');
        } catch (PaymentNotFoundException | PaymentAlreadyNotifiedException) {
            $this->assertTrue(false, 'Should not throw.');
        }
    }

    /** @test */
    public function ShouldThrowCustomExceptionForNotFoundNotifyPayment()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::notifyPaymentEndpoint) => Http::response([], 404, self::headers())]);
        $this->expectException(PaymentNotFoundException::class);
        try {
            (new SIMP2SDK)->notifyPayment('123');
        } catch (SavePaymentException | PaymentAlreadyNotifiedException) {
            $this->assertTrue(false, 'Should not throw.');
        }
    }

}
