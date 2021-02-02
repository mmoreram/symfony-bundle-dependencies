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

use Mmoreram\SymfonyBundleDependencies\BundleDependenciesResolver;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class BundleDependenciesResolverAware.
 */
class BundleDependenciesResolverAware
{
    use BundleDependenciesResolver;

    /**
     * Get bundle instances.
     *
     * @param KernelInterface $kernel
     *
     * @return Bundle[]
     */
    public function getInstancesTest1(KernelInterface $kernel): array
    {
        $bundles = [
            new Bundle3(),
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle5',
        ];

        return $this->getBundleInstances(
            $kernel,
            $bundles
        );
    }

    /**
     * Get bundle instances.
     *
     * @param KernelInterface $kernel
     *
     * @return Bundle[]
     */
    public function getInstancesTest2(KernelInterface $kernel): array
    {
        $bundles = [
            new Bundle1(),
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle2',
        ];

        return $this->getBundleInstances(
            $kernel,
            $bundles
        );
    }

    /**
     * Get bundle instances with a non-bundle dependency.
     *
     * @param KernelInterface $kernel
     *
     * @return Bundle[]
     */
    public function getInstancesTestNotBundle(KernelInterface $kernel): array
    {
        $bundles = [
            new Bundle1(),
            Bundle8::class,
        ];

        return $this->getBundleInstances(
            $kernel,
            $bundles
        );
    }
}
