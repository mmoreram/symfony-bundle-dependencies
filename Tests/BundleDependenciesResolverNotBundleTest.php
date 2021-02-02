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

use Mmoreram\SymfonyBundleDependencies\BundleDependencyException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * Class BundleDependenciesResolverNotBundleTest.
 */
class BundleDependenciesResolverNotBundleTest extends TestCase
{
    use ProphecyTrait;

    /**
     * Test cached resolver with wrong dependencies and exception thrown.
     */
    public function testCachedResolverWithWrongInstancedWithException()
    {
        $kernel = $this->prophesize('Symfony\Component\HttpKernel\KernelInterface');
        $kernel->getCacheDir()->willReturn(dirname(__FILE__));

        $bundleDependenciesResolver = new BundleDependenciesResolverAware();
        $this->expectException(BundleDependencyException::class);
        $bundleDependenciesResolver->getInstancesTestNotBundle(
            $kernel->reveal()
        );
    }
}
