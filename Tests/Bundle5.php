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

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

use Mmoreram\SymfonyBundleDependencies\DependentBundleInterface;

/**
 * Class Bundle5.
 */
class Bundle5 extends Bundle implements DependentBundleInterface
{
    /**
     * @var string
     *
     * Some value
     */
    protected $value;

    /**
     * Construct.
     *
     * @param string $value Value
     */
    public function __construct($value = 'Z')
    {
        $this->value = $value;
    }

    /**
     * Get value.
     *
     * @return string Value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Create instance of current bundle, and return dependent bundle namespaces.
     *
     * @return array
     */
    public static function getBundleDependencies(KernelInterface $kernel) : array
    {
        return [
            new \Mmoreram\SymfonyBundleDependencies\Tests\Bundle1(),
        ];
    }
}
