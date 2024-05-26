<?php

declare(strict_types=1);

namespace Flasher\Tests\Toastr\Laravel;

use Flasher\Toastr\Laravel\FlasherToastrServiceProvider;
use Flasher\Toastr\Prime\ToastrPlugin;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class FlasherToastrServiceProviderTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private MockInterface&Application $app;
    private FlasherToastrServiceProvider $serviceProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = \Mockery::mock(Application::class);
        $this->serviceProvider = new FlasherToastrServiceProvider($this->app);
    }

    public function testCreatePlugin(): void
    {
        $this->assertInstanceOf(ToastrPlugin::class, $this->serviceProvider->createPlugin());
    }

    public function testRegister(): void
    {
        $this->app->expects()->make('config')->andReturns($configMock = \Mockery::mock(Repository::class));
        $configMock->expects('get')->andReturns([]);
        $configMock->expects('set');

        $this->app->expects('configurationIsCached')->never();

        $this->serviceProvider->register();
        $this->addToAssertionCount(1);
    }

    public function testBoot(): void
    {
        $this->app->expects()->make('config')->andReturns($configMock = \Mockery::mock(Repository::class));
        $configMock->expects('get')->andReturns([]);
        $configMock->expects('set');

        $this->app->expects('singleton');
        $this->app->expects('alias');
        $this->app->expects('extend');

        $this->serviceProvider->register();
        $this->serviceProvider->boot();
        $this->addToAssertionCount(1);
    }

    public function testGetConfigurationFile(): void
    {
        $expectedPath = $this->getResourcesPathFromServiceProvider();
        $this->assertStringEndsWith('/Resources/config.php', $this->serviceProvider->getConfigurationFile());
        $this->assertStringContainsString($expectedPath, $this->serviceProvider->getConfigurationFile());
    }

    private function getResourcesPathFromServiceProvider(): string
    {
        $reflection = new \ReflectionClass(FlasherToastrServiceProvider::class);
        $method = $reflection->getMethod('getResourcesDir');
        $method->setAccessible(true);

        /** @var string $string */
        $string = $method->invoke($this->serviceProvider);

        return rtrim($string, '/').'/';
    }
}
