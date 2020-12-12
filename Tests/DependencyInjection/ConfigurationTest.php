<?php

namespace Flasher\Toastr\Symfony\Tests\DependencyInjection;

use Flasher\Prime\Tests\TestCase;
use Flasher\Toastr\Symfony\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    public function testDefaultConfig()
    {
        $config = $this->process(array());

        $expected = array(
            'scripts'                  => array(
                'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js',
                '/bundles/flashertoastr/flasher-toastr.js',
            ),
            'styles'                   => array(
                'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css',
            ),
            'options' =>array(
                        'closeButton'       => true,
                        'closeClass'        => 'toast-close-button',
                        'closeDuration'     => 300,
                        'closeEasing'       => 'swing',
                        'closeHtml'         => '<button><i class="icon-off"></i></button>',
                        'closeMethod'       => 'fadeOut',
                        'closeOnHover'      => true,
                        'containerId'       => 'toast-container',
                        'debug'             => false,
                        'escapeHtml'        => false,
                        'extendedTimeOut'   => 10000,
                        'hideDuration'      => 1000,
                        'hideEasing'        => 'linear',
                        'hideMethod'        => 'fadeOut',
                        'iconClass'         => 'toast-info',
                        'iconClasses'       => array(
                            'error'   => 'toast-error',
                            'info'    => 'toast-info',
                            'success' => 'toast-success',
                            'warning' => 'toast-warning',
                        ),
                        'messageClass'      => 'toast-message',
                        'newestOnTop'       => false,
                        'onHidden'          => null,
                        'onShown'           => null,
                        'positionClass'     => 'toast-top-right',
                        'preventDuplicates' => false,
                        'progressBar'       => true,
                        'progressClass'     => 'toast-progress',
                        'rtl'               => false,
                        'showDuration'      => 300,
                        'showEasing'        => 'swing',
                        'showMethod'        => 'fadeIn',
                        'tapToDismiss'      => true,
                        'target'            => 'body',
                        'timeOut'           => 5000,
                        'titleClass'        => 'toast-title',
                        'toastClass'        => 'toast',
                    )
        );

        $this->assertSame($expected, $config);
    }

    /**
     * Processes an array of configurations and returns a compiled version.
     *
     * @param array $configs An array of raw configurations
     *
     * @return array A normalized array
     */
    private function process($configs)
    {
        $processor = new Processor();

        return $processor->processConfiguration(new Configuration(), $configs);
    }
}
