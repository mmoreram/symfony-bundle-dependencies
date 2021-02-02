<?php

/*
 * This file is part of the php-formatter package
 *
 * Copyright (c) >=2014 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Mmoreram\SymfonyBundleDependencies\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * Class BundleDependenciesResolverTest.
 */
class BundleDependenciesResolverTest extends TestCase
{
    use ProphecyTrait;

    /**
     * Test resolver1.
     */
    public function testResolver1()
    {
        $kernel = $this->prophesize('Symfony\Component\HttpKernel\KernelInterface');
        $bundleDependenciesResolver = new BundleDependenciesResolverAware();
        $bundles = $bundleDependenciesResolver->getInstancesTest1($kernel->reveal());

        $this->assertInstanceOf(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle1',
            $bundles[0]
        );

        $this->assertInstanceOf(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle2',
            $bundles[1]
        );

        $this->assertInstanceOf(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle4',
            $bundles[2]
        );

        $this->assertInstanceOf(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle5',
            $bundles[3]
        );
        $this->assertEquals('A', $bundles[3]->getValue());

        $this->assertInstanceOf(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle3',
            $bundles[4]
        );
    }

    /**
     * Test resolver.
     */
    public function testResolver2()
    {
        $kernel = $this->prophesize('Symfony\Component\HttpKernel\KernelInterface');
        $bundleDependenciesResolver = new BundleDependenciesResolverAware();
        $bundles = $bundleDependenciesResolver->getInstancesTest2($kernel->reveal());

        $this->assertInstanceOf(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle1',
            $bundles[0]
        );

        $this->assertInstanceOf(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle2',
            $bundles[1]
        );

        $this->assertInstanceOf(
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle5',
            $bundles[2]
        );
    }
}
