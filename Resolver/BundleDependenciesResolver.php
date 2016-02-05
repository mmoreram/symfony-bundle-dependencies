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

namespace Mmoreram\SymfonyBundleDependencies\Resolver;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

use Mmoreram\SymfonyBundleDependencies\DependentBundleInterface;

/**
 * Class BundleDependenciesResolver
 *
 * @author Berny Cantos <be@rny.cc>
 */
class BundleDependenciesResolver
{
    /**
     * @var KernelInterface
     *
     * Kernel where bundles will be loaded.
     */
    private $kernel;

    /**
     * @var string[]
     *
     * Temporary list of dependencies already matched.
     */
    private $dependencies = [];

    /**
     * Constructor
     *
     * @param KernelInterface $kernel Kernel where bundles will be loaded.
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Resolve bundle dependencies and return bundles in load order
     *
     * @param Bundle[]|string[] $bundles List of bundles to resolve
     *
     * @return Bundle[]
     */
    public function resolve(array $bundles)
    {
        $this->dependencies = [];
        $dependencies = $this->indexByNamespace($bundles);
        $dependencies = $this->resolveDependencies($dependencies);
        $this->dependencies = [];

        return $this->instanceBundles($dependencies);
    }

    /**
     * Index each bundle by its namespace
     *
     * @param Bundle[]|string[] $bundles
     *
     * @return Bundle[]|string[]
     */
    private function indexByNamespace(array $bundles)
    {
        $namespaces = [];
        foreach ($bundles as $bundle) {
            if (!is_string($bundle)) {
                $namespace = get_class($bundle);
            } else {
                $namespace = $bundle;
            }
            $namespace = trim($namespace, '\\');
            $namespaces[$namespace] = $bundle;
        }

        return $namespaces;
    }

    /**
     * Resolves dependencies
     *
     * @param Bundle[]|string[] $bundles
     *
     * @return Bundle[]|string[]
     */
    private function resolveDependencies(array $bundles)
    {
        $result = [ [], ];
        foreach ($bundles as $bundleName => $bundle) {
            if ($this->dependsOn($bundleName)) {
                continue;
            }

            $dependencies = $this->extractDependencies($bundleName, $bundle);
            if (!empty($dependencies)) {
                $result[] = $dependencies;
            }
        }

        return call_user_func_array('array_merge', $result);
    }

    /**
     * Create instances of bundles
     *
     * Kernel is passed as first parameter on instantiation.
     * Should you need other parameters, return instance from `getBundleDependencies` instead.
     *
     * @param array $bundles
     *
     * @return array
     */
    private function instanceBundles(array $bundles)
    {
        $kernel = $this->kernel;

        foreach ($bundles as $key => $bundle) {
            if (is_string($bundle)) {
                $bundles[$key] = new $bundle($kernel);
            }
        }

        return array_values($bundles);
    }

    /**
     * Add bundle to dependency list
     *
     * @param string $bundleName
     *
     * @return bool true if the dependency already existed, false otherwise
     */
    private function dependsOn($bundleName)
    {
        if (array_key_exists($bundleName, $this->dependencies)) {
            return true;
        }

        $this->dependencies[$bundleName] = true;

        return false;
    }

    /**
     * Get bundle, with its dependencies when conditions are met
     *
     * @param string $bundleName
     * @param Bundle|string $bundle
     *
     * @return Bundle[]|string[]
     */
    private function extractDependencies($bundleName, $bundle)
    {
        $dependencies = [];
        if ($this->isADependentBundle($bundleName)) {
            /** @var DependentBundleInterface $bundleName */
            $dependencies = $bundleName::getBundleDependencies($this->kernel);

            if (!empty($dependencies)) {
                $dependencies = $this->indexByNamespace($dependencies);
                $dependencies = $this->resolveDependencies($dependencies);
            }
        }

        $dependencies[] = $bundle;

        return $dependencies;
    }

    /**
     * Check if a bundle may have dependencies via interfaces
     * Keeps BC from old version
     *
     * @param string $bundleName
     *
     * @return bool
     */
    private function isADependentBundle($bundleName)
    {
        if (is_a($bundleName, 'Mmoreram\SymfonyBundleDependencies\DependentBundleInterface', true)) {
            return true;
        }

        // Keep BC from older version
        $oldClass = 'Elcodi\Bundle\CoreBundle\Interfaces\DependentBundleInterface';
        if (class_exists($oldClass) && is_a($bundleName, $oldClass, true)) {
            return true;
        }

        return false;
    }
}
