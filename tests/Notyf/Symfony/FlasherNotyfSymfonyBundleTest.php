<?php

declare(strict_types=1);

namespace Flasher\Tests\Notyf\Symfony;

use Flasher\Notyf\Prime\NotyfPlugin;
use Flasher\Notyf\Symfony\FlasherNotyfSymfonyBundle;
use Flasher\Symfony\Support\PluginBundle;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

final class FlasherNotyfSymfonyBundleTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private FlasherNotyfSymfonyBundle $flasherNotyfBundle;

    protected function setUp(): void
    {
        parent::setUp();

        $this->flasherNotyfBundle = new FlasherNotyfSymfonyBundle();
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(PluginBundle::class, $this->flasherNotyfBundle);
    }

    public function testCreatePlugin(): void
    {
        $this->assertInstanceOf(NotyfPlugin::class, $this->flasherNotyfBundle->createPlugin());
    }

    public function testGetConfigurationFileReturnsExpectedPath(): void
    {
        $expectedPath = $this->flasherNotyfBundle->getPath().'/Resources/config/config.yaml';

        $this->assertSame($expectedPath, $this->flasherNotyfBundle->getConfigurationFile());
    }
}
