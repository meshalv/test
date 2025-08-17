<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Registration;
use App\Services\LuckyService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LuckyServiceTest extends TestCase
{
    use RefreshDatabase;

    protected LuckyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LuckyService();
    }

    public function testGenerateLucky()
    {
        $registration = Registration::factory()->create();
        $result = $this->service->generateLucky($registration);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('number', $result);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('amount', $result);
        $this->assertArrayHasKey('created_at', $result);
        $this->assertContains($result['result'], ['Win', 'Lose']);
        if ($result['number'] % 2 === 0) {
            $this->assertEquals('Win', $result['result']);
        } else {
            $this->assertEquals('Lose', $result['result']);
        }
    }

    public function testGenerateLuckyPercent()
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('calcPercent');

        $this->assertEquals(0.70, $method->invoke($this->service, 901));
        $this->assertEquals(0.50, $method->invoke($this->service, 601));
        $this->assertEquals(0.30, $method->invoke($this->service, 301));
        $this->assertEquals(0.10, $method->invoke($this->service, 100));
    }

    public function testRegenerateLinkThrowsExceptionIfInactive()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot regenerate: link inactive or expired');

        $registration = Registration::factory()->create([
            'is_active' => false,
            'expired_at' => now()->subDay(),
        ]);

        $this->service->regenerateLink($registration);
    }
}
