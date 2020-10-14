<?php

namespace AfRedirectIfEmpty\Tests;

use AfRedirectIfEmpty\AfRedirectIfEmpty as Plugin;
use Shopware\Components\Test\Plugin\TestCase;

class PluginTest extends TestCase
{
    protected static $ensureLoadedPlugins = [
        'AfRedirectIfEmpty' => []
    ];

    public function testCanCreateInstance()
    {
        /** @var Plugin $plugin */
        $plugin = Shopware()->Container()->get('kernel')->getPlugins()['AfRedirectIfEmpty'];

        $this->assertInstanceOf(Plugin::class, $plugin);
    }
}
