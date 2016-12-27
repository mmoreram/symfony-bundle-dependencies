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

declare(strict_types=1);

namespace Mmoreram\SymfonyBundleDependencies;

use RuntimeException;

/**
 * Class BundleDependencyException.
 */
class BundleDependencyException extends RuntimeException
{
    /**
     * Construct the exception.
     *
     * @param string $bundleNamespace
     */
    public function __construct(string $bundleNamespace)
    {
        $message = sprintf(
            'The kernel or one of your required bundles is requiring the class %s, which is not a BundleInterface implementation',
            $bundleNamespace
        );

        parent::__construct($message);
    }
}
