<?php

namespace SIMP2\Tests\Unit;

use Exception;
use Illuminate\Support\Facades\Http;
use SIMP2\SDK\Enums\SIMP2Endpoint;
use SIMP2\SDK\SIMP2SDK;
use SIMP2\Tests\SDKTestCase;

class DebtTest extends SDKTestCase
{

    /** @test */
    public function ShouldReturnNullWhenDebtDoesNotExists()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::debtEndpoint . '/123') => Http::response([], 404, self::headers())]);
            $response = SIMP2SDK::getDebtInfo('123');
            $this->assertNull($response);
        } catch (Exception) {
            $this->assertTrue(false, 'Should not throw.');
        }
    }

    /** @test */
    public function ShouldReturnDebtInfo()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::debtEndpoint . '/123') => Http::response($this->debtInfoBody(), 200, self::headers())]);
            $response = SIMP2SDK::getDebtInfo('123');
            $this->assertEquals($this->expectedDebtInfoResponse(), $response);
        } catch (Exception $e) {
            $this->assertTrue(false, 'Should not throw.' . $e->getMessage());
        }
    }
}
