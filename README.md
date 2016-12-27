# Symfony Bundle Dependencies

[![Build Status](https://travis-ci.org/mmoreram/symfony-bundle-dependencies.svg?branch=master)](https://travis-ci.org/mmoreram/symfony-bundle-dependencies)

> The minimum requirements of this bundle is **PHP 7.1** and **Symfony 3.2** 
> because the bundle is using features on both versions. If you're not using
> them yet, I encourage you to do it.

This package provides a very simple way of adding dependencies between Symfony
Bundles. Composer defines these definitions in a very soft layer, only
downloading these dependent packages. Bundles should as well force other
Bundles to be instanced in the application to comply with Dependency Injection
dependencies.

* [For your bundle](#for-your-bundle)
* [For your kernel](#for-your-kernel)
* [Performance](#performance)
* [The order](#the-order)

## For your Bundle

If you want your bundles to provide this feature, then is as simple as make your
bundles implement an interface. That simple.

Take in account that this addition will only provide compatibility with projects
using this project, and will not affect anyway projects not using it.

``` php
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Mmoreram\SymfonyBundleDependencies\DependentBundleInterface;

/**
 * My Bundle
 */
class MyBundle extends Bundle implements DependentBundleInterface
{
    /**
     * Create instance of current bundle, and return dependent bundle namespaces
     *
     * @return array Bundle instances
     */
    public static function getBundleDependencies(KernelInterface $kernel)
    {
        return [
            'Another\Bundle\AnotherBundle',
            'My\Great\Bundle\MyGreatBundle',
            // ...
        ];
    }
}
```

Maybe one of your bundle dependencies need an specific value in the constructor.
Well, this is a very very weird case, and you should definitely avoid it, but
you can do it by adding the instance instead of the namespace.

``` php
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Mmoreram\SymfonyBundleDependencies\DependentBundleInterface;

/**
 * My Bundle
 */
class MyBundle extends Bundle implements DependentBundleInterface
{
    /**
     * Create instance of current bundle, and return dependent bundle namespaces
     *
     * @return array Bundle instances
     */
    public static function getBundleDependencies(KernelInterface $kernel)
    {
        return [
            'Another\Bundle\AnotherBundle',
            'My\Great\Bundle\MyGreatBundle',
            new \Even\Another\Bundle\EvenAnotherBundle('some-value'),
        ];
    }
}
```

By default, all bundles defined as their namespace are instanced with the kernel
object as first parameter, so doing something like that doesn't really have
sense at all.

``` php
use Mmoreram\SymfonyBundleDependencies\DependentBundleInterface;

/**
 * My Bundle
 */
class MyBundle implements DependentBundleInterface
{
    /**
     * Create instance of current bundle, and return dependent bundle namespaces
     *
     * @return array Bundle instances
     */
    public static function getBundleDependencies(KernelInterface $kernel)
    {
        return [
            new \Even\Another\Bundle\EvenAnotherBundle($this),
        ];
    }
}
```

As you will see later, using instances instead of names will remove the
possibility of using cache in the final project.

## For your Kernel

In your project, you should be able to resolve all these dependencies. This is
why this package offers you as well a way of doing that in your kernel.

``` php
use Symfony\Component\HttpKernel\Kernel;
use Mmoreram\SymfonyBundleDependencies\BundleDependenciesResolver;

/**
 * Class AppKernel
 */
class AppKernel extends Kernel
{
    use BundleDependenciesResolver;

    /**
     * Register application bundles
     *
     * @return array Array of bundles instances
     */
    public function registerBundles()
    {
        return $this->getBundleInstances([
            '\My\Bundle\MyBundle',
        ]);
    }
}
```

In that case, you can pass as well instances of bundles instead of strings.

``` php
use Symfony\Component\HttpKernel\Kernel;
use Mmoreram\SymfonyBundleDependencies\BundleDependenciesResolver;

/**
 * Class AppKernel
 */
class AppKernel extends Kernel
{
    use BundleDependenciesResolver;

    /**
     * Register application bundles
     *
     * @return array Array of bundles instances
     */
    public function registerBundles()
    {
        return $this->getBundleInstances([
            new \My\Bundle\MyBundle($this),
        ]);
    }
}
```

## Performance

As you may see, resolving dependencies can penalize a lot your website
performance. Each time your Kernel is booted, all dependencies are resolved once
and again, and this has no sense at all.

This package offers you as well a cache layer, reducing to 0 from the second
time your Kernel is booted and until your next deployment (cache file is stored
in Kernel cache folder).

One simple change to your code. That easy.

``` php
use Mmoreram\SymfonyBundleDependencies\CachedBundleDependenciesResolver;

/**
 * Class AppKernel
 */
class AppKernel
{
    use CachedBundleDependenciesResolver;

    /**
     * Register application bundles
     *
     * @return array Array of bundles instances
     */
    public function registerBundles()
    {
        return $this->getBundleInstances([
            '\My\Bundle\MyBundle',
        ]);
    }
}
```

Caching your bundle dependencies resolution can only be used when all
dependencies are defined as strings instead of instances.

This library assumes that, as soon as something changes in your project that can
change the dependencies file, you will remove cache. Just take it in account.

## The order

Of course, the order matters. If two of your dependencies instantiate the same
bundle with different parameters, then the first one to be defined will be the
winner. In that case, if you want to explicitly define how a bundle must be
instantiated even if other dependencies do, add this bundle at the beginning of
your array.

``` php
use Symfony\Component\HttpKernel\Kernel;
use Mmoreram\SymfonyBundleDependencies\BundleDependenciesResolver;

/**
 * Class AppKernel
 */
class AppKernel extends Kernel
{
    use BundleDependenciesResolver;

    /**
     * Register application bundles
     *
     * @return array Array of bundles instances
     */
    public function registerBundles()
    {
        return $this->getBundleInstances([
            new \My\Bundle\MyBundle($this, true),
            'Another\Bundle\AnotherBundle',
            'My\Great\Bundle\MyGreatBundle',
        ]);
    }
}
```

This is also applied when defining the bundle dependencies.
