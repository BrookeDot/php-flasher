<?php

namespace Flasher\Notyf\Laravel\ServiceProvider;

use Flasher\Notyf\Laravel\FlasherNotyfServiceProvider;
use Flasher\Notyf\Laravel\ServiceProvider\Providers\ServiceProviderInterface;

final class ServiceProviderManager
{
    private $provider;

    /**
     * @var ServiceProviderInterface[]
     */
    private $providers = array(
        'Flasher\Notyf\Laravel\ServiceProvider\Providers\Laravel4',
        'Flasher\Notyf\Laravel\ServiceProvider\Providers\Laravel',
        'Flasher\Notyf\Laravel\ServiceProvider\Providers\Lumen',
    );

    private $notifyServiceProvider;

    public function __construct(FlasherNotyfServiceProvider $notifyServiceProvider)
    {
        $this->notifyServiceProvider = $notifyServiceProvider;
    }

    public function boot()
    {
        $provider = $this->resolveServiceProvider();

        $provider->publishConfig($this->notifyServiceProvider);
        $provider->mergeConfigFromNotyf();
    }

    public function register()
    {
        $provider = $this->resolveServiceProvider();
        $provider->registerServices();
    }

    /**
     * @return ServiceProviderInterface
     */
    private function resolveServiceProvider()
    {
        if ($this->provider instanceof ServiceProviderInterface) {
            return $this->provider;
        }

        foreach ($this->providers as $providerClass) {
            $provider = new $providerClass($this->notifyServiceProvider->getApplication());

            if ($provider->shouldBeUsed()) {
                return $this->provider = $provider;
            }
        }

        throw new \InvalidArgumentException('Service Provider not found.');
    }
}
