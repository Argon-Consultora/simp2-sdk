<?php

namespace SIMP2\Tests\Unit;

use Exception;
use Illuminate\Support\Facades\Http;
use SIMP2\SDK\Enums\SIMP2Endpoint;
use SIMP2\SDK\Exceptions\PaymentNotFoundException;
use SIMP2\SDK\Exceptions\SavePaymentException;
use SIMP2\SDK\SIMP2SDK;
use SIMP2\Tests\SDKTestCase;

class ConfirmPaymentTest extends SDKTestCase
{

    /** @test */
    public function ShouldConfirmPayment()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::confirmPaymentEndpoint) => Http::response([], 200, self::headers())]);
            $response = (new SIMP2SDK)->confirmPayment('123456789');
            $this->assertEquals(true, $response->successful());
        } catch (Exception) {
            $this->assertTrue(false, 'Should not throw.');
        }
    }

    /** @test */
    public function ShouldConfirmPaymentHandleNotFoundPayment()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::confirmPaymentEndpoint) => Http::response([], 404, self::headers())]);
            $this->expectException(PaymentNotFoundException::class);
            $response = (new SIMP2SDK)->confirmPayment('123456789');
            $this->assertEquals(true, $response->successful());
        } catch (SavePaymentException) {
            $this->assertTrue(false, 'Should not throw.');
        }
    }

    /** @test */
    public function ShouldConfirmPaymentHandle422()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::confirmPaymentEndpoint) => Http::response([], 422, self::headers())]);
            $response = (new SIMP2SDK)->confirmPayment('123456789');
            $this->assertEquals(true, $response->successful());
        } catch (PaymentNotFoundException) {
            $this->assertTrue(false, 'Should not throw.');
        } catch (SavePaymentException $e) {
            $this->assertEquals('Invalid request body', $e->getMessage());
        }
    }

    /** @test */
    public function ShouldConfirmPaymentHandle500()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::confirmPaymentEndpoint) => Http::response([], 500, self::headers())]);
            $response = (new SIMP2SDK)->confirmPayment('123456789');
            $this->assertEquals(true, $response->successful());
        } catch (PaymentNotFoundException) {
            $this->assertTrue(false, 'Should not throw.');
        } catch (SavePaymentException $e) {
            $this->assertStringContainsString('HTTP request returned status code 500', $e->getMessage());
        }
    }
}
