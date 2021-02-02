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

namespace Mmoreram\SymfonyBundleDependencies;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Trait CachedBundleDependenciesResolver.
 */
trait CachedBundleDependenciesResolver
{
    use BundleDependenciesResolver {
        getBundleInstances as originalGetBundleInstances;
        resolveAndReturnBundleDependencies as originalResolveAndReturnBundleDependencies;
    }

    /**
     * Get bundle instances given the namespace stack.
     *
     * @param KernelInterface $kernel
     * @param array           $bundles
     *
     * @return Bundle[]
     */
    protected function getBundleInstances(
        KernelInterface $kernel,
        array $bundles
    ): array {
        return $this->originalGetBundleInstances(
            $kernel,
            $bundles
        );
    }

    /**
     * Resolve all bundle dependencies and return them all in a single array.
     *
     * @param KernelInterface $kernel
     * @param array           $bundles
     *
     * @return Bundle[]|string[]
     */
    protected function resolveAndReturnBundleDependencies(
        KernelInterface $kernel,
        array $bundles
    ) {
        $cacheFile = $kernel->getCacheDir().'/kernelDependenciesStack.php';
        if (!is_dir($kernel->getCacheDir())) {
            mkdir($kernel->getCacheDir(), 0777, true);
        }
        if (file_exists($cacheFile)) {
            return include $cacheFile;
        }

        $bundleStack = $this
            ->originalResolveAndReturnBundleDependencies(
                $kernel,
                $bundles
            );

        $this->cacheBuiltBundleStack(
            $bundleStack,
            $cacheFile
        );

        return $bundleStack;
    }

    /**
     * Cache the built bundles stack.
     * Only bundles stack with string definitions are allowed to be cached.
     * Otherwise, will throw an exception.
     *
     * @param Bundle[]|string[] $bundleStack
     * @param string            $cacheFile
     *
     * @throws BundleStackNotCacheableException Bundles not cacheable
     */
    protected function cacheBuiltBundleStack(
        array $bundleStack,
        string $cacheFile
    ) {
        foreach ($bundleStack as $bundle) {
            if (is_object($bundle)) {
                $kernelNamespace = get_class($this);
                $bundleNamespace = get_class($bundle);
                throw new BundleStackNotCacheableException($kernelNamespace, $bundleNamespace);
            }
        }

        file_put_contents(
            $cacheFile,
            '<?php return '.var_export($bundleStack, true).';'
        );
    }
}
