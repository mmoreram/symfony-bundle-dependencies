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

namespace Mmoreram\SymfonyBundleDependencies;

/**
 * Trait BundleDependenciesResolver
 */
trait BundleDependenciesResolver
{
    /**
     * Get bundle instances given the namespace stack
     *
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel  Kernel
     * @param array                                         $bundles Bundles defined by instances or namespaces
     *
     * @return \Symfony\Component\HttpKernel\Bundle\Bundle[] Bundle instances
     */
    protected function getBundleInstances(
        \Symfony\Component\HttpKernel\KernelInterface $kernel,
        array $bundles
    ) {
        $resolver = new Resolver\BundleDependenciesResolver($kernel);

        return $resolver->resolve($bundles);
    }
}
