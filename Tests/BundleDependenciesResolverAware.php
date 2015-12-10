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

use Mmoreram\SymfonyBundleDependencies\BundleDependenciesResolver;

/**
 * Class BundleDependenciesResolverAware.
 */
class BundleDependenciesResolverAware
{
    use BundleDependenciesResolver;

    /**
     * Get bundle instances.
     *
     * @param KernelInterface $kernel Kernel
     *
     * @return Bundle[] Bundles
     */
    public function getInstancesTest1(KernelInterface $kernel)
    {
        $bundles = [
            new \Mmoreram\SymfonyBundleDependencies\Tests\Bundle3(),
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
     * @param KernelInterface $kernel Kernel
     *
     * @return Bundle[] Bundles
     */
    public function getInstancesTest2(KernelInterface $kernel)
    {
        $bundles = [
            new \Mmoreram\SymfonyBundleDependencies\Tests\Bundle1(),
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle2',
        ];

        return $this->getBundleInstances(
            $kernel,
            $bundles
        );
    }
}
