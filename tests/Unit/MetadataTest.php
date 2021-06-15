<?php


namespace SIMP2\Tests\Unit;

use Exception;
use SIMP2\SDK\SIMP2SDK;
use SIMP2\Tests\SDKTestCase;
use SIMP2\SDK\Enums\SIMP2Endpoint;
use Illuminate\Support\Facades\Http;
use SIMP2\SDK\Exceptions\CreateMetadataException;


class MetadataTest extends SDKTestCase
{

    /** @test */
    public function ShouldCreateMetadata()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::metadataEndpoint) => Http::response(['value' => 'test'], 200, self::headers())]);
            (new SIMP2SDK)->createMetadata('test', 'test');
            $this->assertTrue(true);
        } catch (Exception) {
            $this->assertTrue(false, 'Should not throw.');
        }
    }

    /** @test */
    public function ShouldRetrieveMetadata()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::metadataEndpoint . "/*") => Http::response([0 => ['value' => 'test']], 200, self::headers())]);
        $value = (new SIMP2SDK)->getMetadata('test');
        $this->assertNotNull($value);
        $this->assertIsString($value, 'SIMP2SDK::getMetadata, the returned value must be an string.');
        $this->assertEquals('test', $value, 'SIMP2SDK::getMetadata does not return the expected value.');
    }

    /** @test */
    public function ShouldReturnNullWhenNotFoundMetadata()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::metadataEndpoint . "/*") => Http::response([], 404, self::headers())]);
        $value = (new SIMP2SDK)->getMetadata('test');
        $this->assertNull($value);
    }

    /** @test */
    public function ShouldThrowCustomExceptionForMetadata()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::metadataEndpoint) => Http::response([], 500, self::headers())]);
        $this->expectException(CreateMetadataException::class);
        (new SIMP2SDK)->createMetadata('test', 'test');
    }
}
