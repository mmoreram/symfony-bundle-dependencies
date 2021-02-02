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

use Mmoreram\SymfonyBundleDependencies\CachedBundleDependenciesResolver;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class CachedBundleDependenciesResolverAware.
 */
class CachedBundleDependenciesResolverAware
{
    use CachedBundleDependenciesResolver;

    /**
     * Get wrong cached bundle instances with Exception.
     *
     * @param KernelInterface $kernel
     *
     * @return Bundle[]
     */
    public function getWrongInstancesWithException(KernelInterface $kernel): array
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
     * Get right cached bundle instances with Exception.
     *
     * @param KernelInterface $kernel
     *
     * @return Bundle[]
     */
    public function getRightInstances(KernelInterface $kernel): array
    {
        $bundles = [
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle7',
        ];

        return $this->getBundleInstances(
            $kernel,
            $bundles
        );
    }
}
