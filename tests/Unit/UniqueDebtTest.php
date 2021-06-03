<?php

namespace SIMP2\Tests\Unit;

use Illuminate\Support\Facades\Http;
use SIMP2\SDK\Enums\SIMP2Endpoint;
use SIMP2\SDK\Exceptions\PaymentNotFoundException;
use SIMP2\SDK\Exceptions\SIMP2Exception;
use SIMP2\SDK\SIMP2SDK;
use SIMP2\Tests\SDKTestCase;

class UniqueDebtTest extends SDKTestCase
{

    /** @test */
    public function ShouldReturnNullWhenDebtDoesNotExists()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::debtUniqueEndpoint . '123') => Http::response([], 404, self::headers())]);
            $this->expectException(PaymentNotFoundException::class);
            SIMP2SDK::getSubdebt('123');
        } catch (SIMP2Exception) {
            $this->fail('SIMP2 Exception not expected');
        }
    }

    /** @test */
    public function ShouldHandleSIMP2Error()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::debtUniqueEndpoint . '123') => Http::response([], 500, self::headers())]);
            $this->expectException(SIMP2Exception::class);
            SIMP2SDK::getSubdebt('123');
        } catch (PaymentNotFoundException) {
            $this->fail('PaymentNotFoundException not expected');
        }
    }

    /** @test */
    public function ShouldReturnDebtWithOnlyOneSubdebt()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::debtUniqueEndpoint . '123') => Http::response($this->uniqueDebtResponse(), 200, self::headers())]);
            $debt = SIMP2SDK::getSubdebt('123');
            $this->assertEquals($this->expectedDebtUnique(), $debt, 'La deuda no es igual a la esperada');
        } catch (PaymentNotFoundException | SIMP2Exception) {
            $this->fail('PaymentNotFoundException not expected');
        }
    }
}
