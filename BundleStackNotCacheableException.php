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

use Exception;

/**
 * Class BundleStackNotCacheableException.
 */
class BundleStackNotCacheableException extends Exception
{
    /**
     * Construct the exception.
     *
     * @param string $kernelNamespace
     * @param string $bundleNamespace
     */
    public function __construct(
        string $kernelNamespace,
        string $bundleNamespace
    ) {
        $message = sprintf(
            'Bundle stack of kernel %s cannot be cached because bundle %s is defined as an object instance instead of a namespace',
            $kernelNamespace,
            $bundleNamespace
        );

        parent::__construct($message);
    }
}
