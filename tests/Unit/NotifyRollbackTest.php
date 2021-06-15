<?php

namespace SIMP2\Tests\Unit;


use SIMP2\SDK\SIMP2SDK;
use SIMP2\Tests\SDKTestCase;
use Exception;
use SIMP2\SDK\Enums\SIMP2Endpoint;
use Illuminate\Support\Facades\Http;
use SIMP2\SDK\Exceptions\ReversePaymentException;
use SIMP2\SDK\Exceptions\PaymentNotFoundException;


class NotifyRollbackTest extends SDKTestCase
{

    /** @test */
    public function ShouldHandleRollbackPaymentException()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::notifyRollbackEndpoint) => Http::response([], 404, self::headers())]);
        try {
            $this->expectException(PaymentNotFoundException::class);
            (new SIMP2SDK)->notifyRollbackPayment('123');
        } catch (ReversePaymentException $e) {
            $this->assertTrue(false, 'Should not throw.'. $e->getMessage());
        }
    }

    /** @test */
    public function ShouldNotifyRollbackPayment()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::notifyRollbackEndpoint) => Http::response([], 200, self::headers())]);
            $response = (new SIMP2SDK)->notifyRollbackPayment('123');
            $this->assertEquals(true, $response->successful());
        } catch (Exception) {
            $this->assertTrue(false, 'Should not throw.');
        }
    }

    /** @test */
    public function ShouldHandleNotifyRollbackPayment422Error()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::notifyRollbackEndpoint) => Http::response([], 422, self::headers())]);
            (new SIMP2SDK)->notifyRollbackPayment('123');
            $this->assertTrue(false, 'Should throw.');
        } catch (ReversePaymentException $e) {
            $this->assertEquals('Invalid request body', $e->getMessage());
        } catch (PaymentNotFoundException) {
            $this->assertTrue(false, 'Should not throw this here.');
        }
    }

    /** @test */
    public function ShouldHandleNotifyRollbackPayment500Error()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::notifyRollbackEndpoint) => Http::response([], 500, self::headers())]);
            (new SIMP2SDK)->notifyRollbackPayment('123');
            $this->assertTrue(false, 'Should throw.');
        } catch (ReversePaymentException $e) {
            $this->assertStringContainsString('Internal SIMP2 Error', $e->getMessage());
        } catch (PaymentNotFoundException) {
            $this->assertTrue(false, 'Should not throw this here.');
        }
    }
}
