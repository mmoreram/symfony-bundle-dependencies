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
     * @var string
     *
     * Throw Exception
     */
    private $throwException;

    /**
     * @var string
     *
     * Error type
     */
    private $errorType;

    /**
     * Get bundle instances given the namespace stack.
     *
     * @param KernelInterface $kernel         Kernel
     * @param array           $bundles        Bundles defined by instances or namespaces
     * @param bool            $throwException Throw Exception if not cacheable
     * @param int             $errorType      Error type
     *
     * @return Bundle[] Bundle instances
     */
    protected function getBundleInstances(
        KernelInterface $kernel,
        array $bundles,
        $throwException = false,
        $errorType = E_USER_WARNING
    ) {
        $this->throwException = $throwException;
        $this->errorType = $errorType;

        return $this->originalGetBundleInstances(
            $kernel,
            $bundles
        );
    }

    /**
     * Resolve all bundle dependencies and return them all in a single array.
     *
     * @param KernelInterface $kernel  Kernel
     * @param array           $bundles Bundles defined by instances or namespaces
     *
     * @return Bundle[]|string[] Bundle definitions
     */
    protected function resolveAndReturnBundleDependencies(
        KernelInterface $kernel,
        array $bundles
    ) {
        $cacheFile = $kernel->getCacheDir() . '/kernelDependenciesStack.php';
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
     * Otherwise, will throw a notice.
     *
     * @param Bundle[]|string[] $bundleStack Bundle stack
     * @param string            $cacheFile   Cache file
     *
     * @throws BundleStackNotCacheableException Bundles not cacheable
     */
    protected function cacheBuiltBundleStack(
        array $bundleStack,
        $cacheFile
    ) {
        foreach ($bundleStack as $bundle) {
            if (is_object($bundle)) {
                $kernelNamespace = get_class($this);
                $bundleNamespace = get_class($bundle);
                if ($this->throwException) {
                    throw new BundleStackNotCacheableException(
                        $kernelNamespace,
                        $bundleNamespace
                    );
                } else {
                    @trigger_error(
                        sprintf(
                            'Bundle stack of kernel %s cannot be cached because bundle %s is defined as an object instance instead of a namespace',
                            $kernelNamespace,
                            $bundleNamespace
                        ),
                        $this->errorType
                    );
                }

                return;
            }
        }

        file_put_contents(
            $cacheFile,
            '<?php return ' . var_export($bundleStack, true) . ';'
        );
    }
}
