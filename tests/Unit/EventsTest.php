<?php

namespace SIMP2\Tests\Unit;

use SIMP2\SDK\SIMP2SDK;
use SIMP2\Tests\SDKTestCase;
use SIMP2\SDK\Enums\LogLevel;
use SIMP2\Tests\TestableSIMP2SDK;
use SIMP2\SDK\Enums\SIMP2Endpoint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use SIMP2\SDK\Enums\TypeDescription;


class EventsTest extends SDKTestCase
{

    /** @test */
    public function ShouldRespectLogLevel()
    {
        $debug = TestableSIMP2SDK::testShouldLog(LogLevel::Debug(), LogLevel::Info());
        $notice = TestableSIMP2SDK::testShouldLog(LogLevel::Notice(), LogLevel::Info());
        $info = TestableSIMP2SDK::testShouldLog(LogLevel::Info(), LogLevel::Info());
        $alert = TestableSIMP2SDK::testShouldLog(LogLevel::Alert(), LogLevel::Info());
        $warning = TestableSIMP2SDK::testShouldLog(LogLevel::Warning(), LogLevel::Info());
        $error = TestableSIMP2SDK::testShouldLog(LogLevel::Error(), LogLevel::Info());
        $critical = TestableSIMP2SDK::testShouldLog(LogLevel::Critical(), LogLevel::Info());

        $this->assertFalse($debug);
        $this->assertFalse($notice);
        $this->assertTrue($info);
        $this->assertTrue($alert);
        $this->assertTrue($warning);
        $this->assertTrue($error);
        $this->assertTrue($critical);
    }

    /** @test */
    public function ShouldFallbackToDefaultLogLevel()
    {
        $debug = TestableSIMP2SDK::testFallbackShouldLog(LogLevel::Debug(), -1);
        $notice = TestableSIMP2SDK::testFallbackShouldLog(LogLevel::Notice(), -1);
        $info = TestableSIMP2SDK::testFallbackShouldLog(LogLevel::Info(), -1);
        $alert = TestableSIMP2SDK::testFallbackShouldLog(LogLevel::Alert(), -1);
        $warning = TestableSIMP2SDK::testFallbackShouldLog(LogLevel::Warning(), -1);
        $error = TestableSIMP2SDK::testFallbackShouldLog(LogLevel::Error(), -1);
        $critical = TestableSIMP2SDK::testFallbackShouldLog(LogLevel::Critical(), -1);

        $this->assertTrue($debug);
        $this->assertTrue($notice);
        $this->assertTrue($info);
        $this->assertTrue($alert);
        $this->assertTrue($warning);
        $this->assertTrue($error);
        $this->assertTrue($critical);
    }

    /** @test */
    public function ShouldFireAnInfoEvent()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::logInfoEndpoint) => Http::response($this->clientBody(), 200, self::headers())]);
        Log::shouldReceive('critical')->never();
        SIMP2SDK::infoEvent('123', '', null, TypeDescription::ClientNotFound(), LogLevel::Critical(), 1);
    }

    /** @test */
    public function ShouldLogWhenAnInfoEventFailed()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::logInfoEndpoint) => Http::response($this->clientBody(), 500, self::headers())]);
        Log::shouldReceive('critical')->once();
        SIMP2SDK::infoEvent('123', '', null, TypeDescription::ClientNotFound(), LogLevel::Critical(), 1);
    }

    /** @test */
    public function ShouldFireAnErrorEvent()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::logErrorEndpoint) => Http::response($this->clientBody(), 200, self::headers())]);
        Log::shouldReceive('critical')->never();
        SIMP2SDK::errorEvent('123', '', null, TypeDescription::ClientNotFound(), LogLevel::Critical(), 1);
    }

    /** @test */
    public function ShouldLogWhenAnErrorEventFailed()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::logErrorEndpoint) => Http::response($this->clientBody(), 500, self::headers())]);
        Log::shouldReceive('critical')->once();
        SIMP2SDK::errorEvent('123', '', null, TypeDescription::ClientNotFound(), LogLevel::Critical(), 1);
    }
}
