<?php

/*
 * This file is part of the php-formatter package
 *
 * Copyright (c) 2014 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace Mmoreram\SymfonyBundleDependencies\Tests;

use PHPUnit_Framework_TestCase;

/**
 * Class CachedBundleDependenciesResolverTest.
 */
class CachedBundleDependenciesResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test cached resolver with wrong dependencies and exception thrown.
     *
     * @expectedException \Mmoreram\SymfonyBundleDependencies\BundleStackNotCacheableException
     */
    public function testCachedResolverWithWrongInstancedWithException()
    {
        $kernel = $this->prophesize('Symfony\Component\HttpKernel\KernelInterface');
        $kernel->getCacheDir()->willReturn(dirname(__FILE__));

        $bundleDependenciesResolver = new CachedBundleDependenciesResolverAware();
        $bundleDependenciesResolver->getWrongInstancesWithException(
            $kernel->reveal()
        );
    }

    /**
     * Test cached resolver with wrong dependencies and exception not thrown.
     */
    public function testCachedResolverWithWrongInstancedWithoutException()
    {
        $kernel = $this->prophesize('Symfony\Component\HttpKernel\KernelInterface');
        $kernel->getCacheDir()->willReturn(dirname(__FILE__));
        $cacheFile = dirname(__FILE__) . '/kernelDependenciesStack.php';

        @unlink($cacheFile);
        $bundleDependenciesResolver = new CachedBundleDependenciesResolverAware();
        $bundleDependenciesResolver->getWrongInstancesWithoutException(
            $kernel->reveal()
        );

        $this->assertFalse(file_exists($cacheFile));
    }

    /**
     * Test cached resolver with right dependencies.
     */
    public function testCachedResolverWithRightInstancedWithException()
    {
        $kernel = $this->prophesize('Symfony\Component\HttpKernel\KernelInterface');
        $kernel->getCacheDir()->willReturn(dirname(__FILE__));
        $cacheFile = dirname(__FILE__) . '/kernelDependenciesStack.php';

        @unlink($cacheFile);
        $bundleDependenciesResolver = new CachedBundleDependenciesResolverAware();
        $bundles = $bundleDependenciesResolver->getRightInstances(
            $kernel->reveal()
        );

        $this->assertInstanceOf(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle6',
            $bundles[0]
        );

        $this->assertInstanceOf(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle7',
            $bundles[1]
        );

        $this->assertEquals([
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle6',
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle7',
        ], include $cacheFile);
        @unlink($cacheFile);
    }
}
