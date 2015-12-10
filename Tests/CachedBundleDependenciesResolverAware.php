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

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

use Mmoreram\SymfonyBundleDependencies\CachedBundleDependenciesResolver;

/**
 * Class CachedBundleDependenciesResolverAware.
 */
class CachedBundleDependenciesResolverAware
{
    use CachedBundleDependenciesResolver;

    /**
     * Get wrong cached bundle instances with Exception.
     *
     * @param KernelInterface $kernel Kernel
     *
     * @return Bundle[] Bundles
     */
    public function getWrongInstancesWithException(KernelInterface $kernel)
    {
        $bundles = [
            new \Mmoreram\SymfonyBundleDependencies\Tests\Bundle3(),
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle5',
        ];

        return $this->getBundleInstances(
            $kernel,
            $bundles,
            true
        );
    }

    /**
     * Get right cached bundle instances with Exception.
     *
     * @param KernelInterface $kernel Kernel
     *
     * @return Bundle[] Bundles
     */
    public function getRightInstances(KernelInterface $kernel)
    {
        $bundles = [
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle7',
        ];

        return $this->getBundleInstances(
            $kernel,
            $bundles,
            true
        );
    }

    /**
     * Get wrong cached bundle instances without Exception.
     *
     * @param KernelInterface $kernel Kernel
     *
     * @return Bundle[] Bundles
     */
    public function getWrongInstancesWithoutException(KernelInterface $kernel)
    {
        $bundles = [
            new \Mmoreram\SymfonyBundleDependencies\Tests\Bundle3(),
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle5',
        ];

        return $this->getBundleInstances(
            $kernel,
            $bundles,
            false
        );
    }
}
