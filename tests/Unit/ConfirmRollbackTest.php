<?php

namespace SIMP2\Tests\Unit;

use Exception;
use Illuminate\Support\Facades\Http;
use SIMP2\SDK\Enums\SIMP2Endpoint;
use SIMP2\SDK\Exceptions\PaymentNotFoundException;
use SIMP2\SDK\Exceptions\ReversePaymentException;
use SIMP2\SDK\SIMP2SDK;
use SIMP2\Tests\SDKTestCase;

class ConfirmRollbackTest extends SDKTestCase
{

    /** @test */
    public function ShouldHandleConfirmRollbackPaymentException()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::confirmRollbackEndpoint) => Http::response([], 404, self::headers())]);
        try {
            $this->expectException(PaymentNotFoundException::class);
            (new SIMP2SDK)->confirmRollbackPayment('123');
        } catch (ReversePaymentException $e) {
            $this->assertTrue(false, 'Should not throw.' . $e->getMessage());
        }
    }

    /** @test */
    public function ShouldConfirmRollbackPayment()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::confirmRollbackEndpoint) => Http::response([], 200, self::headers())]);
            $response = (new SIMP2SDK)->confirmRollbackPayment('123');
            $this->assertEquals(true, $response->successful());
        } catch (Exception) {
            $this->assertTrue(false, 'Should not throw.');
        }
    }

    /** @test */
    public function ShouldHandleConfirmRollbackPayment422Error()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::confirmRollbackEndpoint) => Http::response([], 422, self::headers())]);
            (new SIMP2SDK)->confirmRollbackPayment('123');
            $this->assertTrue(false, 'Should throw.');
        } catch (ReversePaymentException $e) {
            $this->assertEquals('Invalid request body', $e->getMessage());
        } catch (PaymentNotFoundException) {
            $this->assertTrue(false, 'Should not throw this here.');
        }
    }

    /** @test */
    public function ShouldHandleConfirmRollbackPayment500Error()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::confirmRollbackEndpoint) => Http::response([], 500, self::headers())]);
            (new SIMP2SDK)->confirmRollbackPayment('123');
            $this->assertTrue(false, 'Should throw.');
        } catch (ReversePaymentException $e) {
            $this->assertStringContainsString('Internal SIMP2 Error', $e->getMessage());
        } catch (PaymentNotFoundException) {
            $this->assertTrue(false, 'Should not throw this here.');
        }
    }
}
