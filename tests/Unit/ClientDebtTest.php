<?php

namespace SIMP2\Tests\Unit;

use Illuminate\Support\Facades\Http;
use SIMP2\SDK\Enums\SIMP2Endpoint;
use SIMP2\SDK\Exceptions\ClientNotFound;
use SIMP2\SDK\Exceptions\SIMP2Exception;
use SIMP2\SDK\SIMP2SDK;
use SIMP2\Tests\SDKTestCase;

class ClientDebtTest extends SDKTestCase
{

    /** @test */
    public function ShouldThrowWhenDebtDoesNotExists()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::clientDataEndpoint(123)) => Http::response([], 404, self::headers())]);
            $this->expectException(ClientNotFound::class);
            $response = (new SIMP2SDK)->getDebtsOfClient('123');
            $this->assertNull($response);
        } catch (SIMP2Exception) {
            $this->assertTrue(false, 'Should not throw.');
        }
    }

    /** @test */
    public function ShouldThrowWhen500()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::clientDataEndpoint(123)) => Http::response([], 500, self::headers())]);
            $this->expectException(SIMP2Exception::class);
            $response = (new SIMP2SDK)->getDebtsOfClient('123');
            $this->assertNull($response);
        } catch (ClientNotFound) {
            $this->assertTrue(false, 'Should not throw.');
        }
    }

    /** @test */
    public function ShouldReturnDebtInfo()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::clientDataEndpoint(123)) => Http::response($this->debtInfoBody(), 200, self::headers())]);
            $response = (new SIMP2SDK)->getDebtsOfClient('123')[0];
            $this->assertEquals($this->expectedDebtInfoResponse(), $response);
        } catch (SIMP2Exception | ClientNotFound $e) {
            $this->assertTrue(false, 'Should not throw.' . $e->getMessage());
        }
    }
}
