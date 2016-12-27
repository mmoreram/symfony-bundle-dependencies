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

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Trait BundleDependenciesResolver.
 */
trait BundleDependenciesResolver
{
    /**
     * Get bundle instances given the namespace stack.
     *
     * @param KernelInterface $kernel
     * @param array           $bundles
     *
     * @return BundleInterface[]
     */
    protected function getBundleInstances(
        KernelInterface $kernel,
        array $bundles
    ) : array {
        $bundleStack = $this
            ->resolveAndReturnBundleDependencies(
                $kernel,
                $bundles
            );

        $builtBundles = [];
        foreach ($bundleStack as $bundle) {
            $builtBundles[] = $this
                ->getBundleDefinitionInstance($bundle);
        }

        return $builtBundles;
    }

    /**
     * Resolve all bundle dependencies and return them all in a single array.
     *
     * @param KernelInterface $kernel
     * @param array           $bundles
     *
     * @return BundleInterface[]|string[]
     */
    protected function resolveAndReturnBundleDependencies(
        KernelInterface $kernel,
        array $bundles
    ) : array {
        $bundleStack = [];
        $visitedBundles = [];
        $this
            ->resolveBundleDependencies(
                $kernel,
                $bundleStack,
                $visitedBundles,
                $bundles
            );

        return $bundleStack;
    }

    /**
     * Resolve bundle dependencies.
     *
     * Given a set of already loaded bundles and a set of new needed bundles,
     * build new dependencies and fill given array of loaded bundles.
     *
     * @param KernelInterface $kernel
     * @param array           $bundleStack
     * @param array           $visitedBundles
     * @param array           $bundles
     */
    private function resolveBundleDependencies(
        KernelInterface $kernel,
        array &$bundleStack,
        array &$visitedBundles,
        array $bundles
    ) {
        $bundles = array_reverse($bundles);

        foreach ($bundles as $bundle) {

            /*
             * Each visited node is prioritized and placed at the beginning.
             */
            $this
                ->prioritizeBundle(
                    $bundleStack,
                    $bundle
                );
        }

        foreach ($bundles as $bundle) {
            $bundleNamespace = $this->getBundleDefinitionNamespace($bundle);
            /*
             * If have already visited this bundle, continue. One bundle can be
             * processed once.
             */
            if (isset($visitedBundles[$bundleNamespace])) {
                continue;
            }

            $visitedBundles[$bundleNamespace] = true;
            $bundleNamespaceObj = new \ReflectionClass($bundleNamespace);
            if ($bundleNamespaceObj->implementsInterface(DependentBundleInterface::class)) {

                /**
                 * @var DependentBundleInterface
                 */
                $bundleDependencies = $bundleNamespace::getBundleDependencies($kernel);

                $this->resolveBundleDependencies(
                    $kernel,
                    $bundleStack,
                    $visitedBundles,
                    $bundleDependencies
                );
            }
        }
    }

    /**
     * Given the global bundle stack and a bundle definition, considering this
     * bundle definition as an instance or a namespace, prioritize this bundle
     * inside this stack.
     *
     * To prioritize a bundle means that must be placed in the beginning of the
     * stack. If already exists, then remove the old entry just before adding it
     * again.
     *
     * @param array                  $bundleStack
     * @param BundleInterface|string $elementToPrioritize
     */
    private function prioritizeBundle(
        array &$bundleStack,
        $elementToPrioritize
    ) {
        $elementNamespace = $this->getBundleDefinitionNamespace($elementToPrioritize);
        foreach ($bundleStack as $bundlePosition => $bundle) {
            $bundleNamespace = $this->getBundleDefinitionNamespace($bundle);

            if ($elementNamespace == $bundleNamespace) {
                unset($bundleStack[$bundlePosition]);
            }
        }
        array_unshift($bundleStack, $elementToPrioritize);
    }

    /**
     * Given a bundle instance or a namespace, return its namespace.
     *
     * @param BundleInterface|string $bundle
     *
     * @return string
     */
    private function getBundleDefinitionNamespace($bundle) : string
    {
        return ltrim(is_object($bundle)
            ? get_class($bundle)
            : $bundle, ' \\');
    }

    /**
     * Given a bundle instance or a namespace, return the instance.
     * Each bundle is instanced with the Kernel as the first element of the
     * construction, by default.
     *
     * @param BundleInterface|string $bundle
     *
     * @return BundleInterface
     *
     * @throws BundleDependencyException Is not a BundleInterface implementation
     */
    private function getBundleDefinitionInstance($bundle) : BundleInterface
    {
        if (!is_object($bundle)) {
            $bundle = new $bundle($this);
        }

        if (!$bundle instanceof BundleInterface) {
            throw new BundleDependencyException(get_class($bundle));
        }

        return $bundle;
    }
}
