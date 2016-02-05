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

use Mmoreram\SymfonyBundleDependencies\Resolver\BundleDependenciesResolver;

/**
 * Class BundleDependenciesResolverTest.
 */
class BundleDependenciesResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test resolver1.
     */
    public function testResolver1()
    {
        $kernel = $this->mockKernel();
        $resolver = new BundleDependenciesResolver($kernel->reveal());

        $bundles = $resolver->resolve([
            new \Mmoreram\SymfonyBundleDependencies\Tests\Bundle3(),
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle5',
        ]);

        $this->assertBundleOrder(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle1',
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle3',
            $bundles
        );

        $this->assertBundleOrder(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle2',
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle3',
            $bundles
        );

        $this->assertBundleOrder(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle4',
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle3',
            $bundles
        );

        $this->assertBundleOrder(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle2',
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle1',
            $bundles
        );

        $this->assertBundleOrder(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle5',
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle1',
            $bundles
        );
    }

    /**
     * Test resolver.
     */
    public function testResolver2()
    {
        $kernel = $this->mockKernel();
        $resolver = new BundleDependenciesResolver($kernel->reveal());

        $bundles = $resolver->resolve([
            new \Mmoreram\SymfonyBundleDependencies\Tests\Bundle1(),
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle2',
        ]);

        $this->assertBundleOrder(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle5',
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle1',
            $bundles
        );

        $this->assertBundleOrder(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle2',
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle1',
            $bundles
        );
    }

    /**
     * Check two bundles are in correct order of loading
     *
     * @param string $after   This bundle should be loaded after the one in $before
     * @param string $before  This bundle should be loaded before the one in $after
     * @param array  $bundles Array of bundles
     */
    private function assertBundleOrder($after, $before, array $bundles)
    {
        $bundleClasses = array_map('get_class', $bundles);

        $this->assertTrue(
            array_search($after, $bundleClasses) < array_search($before, $bundleClasses),
            sprintf(
                'Bundle "%s" should be loaded after "%s", but it is not',
                $after,
                $before
            )
        );
    }

    /**
     * Mock a kernel
     *
     * @return \Prophecy\Prophecy\ObjectProphecy|\Symfony\Component\HttpKernel\KernelInterface
     */
    private function mockKernel()
    {
        return $this->prophesize('Symfony\Component\HttpKernel\KernelInterface');
    }
}
