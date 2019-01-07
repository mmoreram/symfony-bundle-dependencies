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
 * Class Bundle7.
 */
class Bundle7 extends Bundle implements DependentBundleInterface
{
    /**
     * Create instance of current bundle, and return dependent bundle namespaces.
     *
     * @return array
     */
    public static function getBundleDependencies(KernelInterface $kernel): array
    {
        return [
            'Mmoreram\SymfonyBundleDependencies\Tests\Bundle6',
        ];
    }
}
