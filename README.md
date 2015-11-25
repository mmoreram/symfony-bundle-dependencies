Symfony Bundle Dependencies
===========================

This package provides a very simple way of adding dependencies between Symfony
Bundles. Composer defines these definitions in a very soft layer, only
downloading these dependent packages. Bundles should as well force other
Bundles to be instanced in the application to comply with Dependency Injection
dependencies.

## Installing

To install this package you must add this dependency in your bundles or
packages. Of course, adding this optional feature to your bundles, enable others
to use this feature on their projects.

``` json
"require": {
    "mmoreram/symfony-bundle-dependencies": "^1.0",
},
```

## For you Bundle

If you want your bundles to provide this feature, then is as simple as make your
bundles implement an interface. That simple.

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
            'Another\Bundle\AnotherBundle',
            'My\Great\Bundle\MyGreatBundle',
            // ...
        ];
    }
}
```

Maybe one of your bundle dependencies needs the kernel, or special data to be
instantiated. Well, see that this method receives the kernel as the unique
parameter. Use it :)

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
            'Another\Bundle\AnotherBundle',
            'My\Great\Bundle\MyGreatBundle',
            new \Yet\Another\Bundle\YetAnotherBundle($kernel),
            new \Even\Another\Bundle\EvenAnotherBundle($kernel, true),
        ];
    }
}
```

Applying this change you will just offer the possibility to use it in other
projects, ignoring it when is not necessary.

## For your Kernel

In your project, you should be able to resolve all these dependencies. For this,
this package offers you as well a way of doing that in your kernel.

``` php
use Mmoreram\SymfonyBundleDependencies\BundleDependenciesResolver;

/**
 * Class AppKernel
 */
class AppKernel
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
use Mmoreram\SymfonyBundleDependencies\BundleDependenciesResolver;

/**
 * Class AppKernel
 */
class AppKernel
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

## The order

Of course, the order matters. If two of your dependencies instantiate the same
bundle with different parameters, then the first one to be defined will be the
winner. In that case, if you want to explicitly define how a bundle must be
instantiated even if other dependencies do, add this bundle at the beginning of
your array.

``` php
use Mmoreram\SymfonyBundleDependencies\BundleDependenciesResolver;

/**
 * Class AppKernel
 */
class AppKernel
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
