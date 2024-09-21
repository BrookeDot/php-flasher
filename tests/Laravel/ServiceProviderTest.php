<?php

declare(strict_types=1);

namespace Flasher\Tests\Laravel;

use Flasher\Laravel\Middleware\FlasherMiddleware;
use Flasher\Laravel\Middleware\SessionMiddleware;
use Flasher\Prime\FlasherInterface;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use PHPUnit\Framework\Attributes\DataProvider;

final class ServiceProviderTest extends TestCase
{
    /**
     * @param class-string<object> $class
     */
    #[DataProvider('servicesDataProvider')]
    public function testContainerContainServices(string $service, string $class): void
    {
        $this->assertTrue($this->app->bound($service));
        $this->assertInstanceOf($class, $this->app->make($service));
    }

    /**
     * @param class-string<object> $expectedClass
     */
    #[DataProvider('flasherAdapterProvider')]
    public function testFlasherCanCreateServicesFromAlias(string $alias, string $expectedClass): void
    {
        /** @var FlasherInterface $flasher */
        $flasher = $this->app->make('flasher');

        $adapter = $flasher->use($alias);
        $this->assertInstanceOf($expectedClass, $adapter);
    }

    public function testMiddlewareIsRegistered(): void
    {
        $this->assertTrue($this->hasMiddleware(FlasherMiddleware::class));
        $this->assertTrue($this->hasMiddleware(SessionMiddleware::class));
    }

    #[DefineEnvironment('disableInjectAssets')]
    public function testFlasherMiddlewareDisabled(): void
    {
        $this->assertFalse($this->app->bound(FlasherMiddleware::class));
        $this->assertFalse($this->hasMiddleware(FlasherMiddleware::class));
    }

    #[DefineEnvironment('disableFlashBag')]
    public function testSessionMiddlewareDisabled(): void
    {
        $this->assertFalse($this->app->bound(SessionMiddleware::class));
        $this->assertFalse($this->hasMiddleware(SessionMiddleware::class));
    }

    private function hasMiddleware(string $middleware): bool
    {
        $kernel = $this->app->make(\Illuminate\Foundation\Http\Kernel::class);
        $middlewares = $kernel->getMiddlewareGroups()['web'];

        return \in_array($middleware, $middlewares);
    }

    /**
     * @return iterable<int, array<string>>
     */
    public static function servicesDataProvider(): iterable
    {
        yield ['flasher', \Flasher\Prime\Flasher::class];
        yield ['flasher.noty', \Flasher\Noty\Prime\Noty::class];
        yield ['flasher.notyf', \Flasher\Notyf\Prime\Notyf::class];
        yield ['flasher.sweetalert', \Flasher\SweetAlert\Prime\SweetAlert::class];
        yield ['flasher.toastr', \Flasher\Toastr\Prime\Toastr::class];
        yield [FlasherMiddleware::class, FlasherMiddleware::class];
        yield [SessionMiddleware::class, SessionMiddleware::class];
    }

    /**
     * @return iterable<int, array<string>>
     */
    public static function flasherAdapterProvider(): iterable
    {
        yield ['noty', \Flasher\Noty\Prime\Noty::class];
        yield ['notyf', \Flasher\Notyf\Prime\Notyf::class];
        yield ['sweetalert', \Flasher\SweetAlert\Prime\SweetAlert::class];
        yield ['toastr', \Flasher\Toastr\Prime\Toastr::class];
    }

    protected function disableInjectAssets(Application $app): void
    {
        $app['config']->set('flasher.inject_assets', false);
    }

    protected function disableFlashBag(Application $app): void
    {
        $app['config']->set('flasher.flash_bag', false);
    }
}
