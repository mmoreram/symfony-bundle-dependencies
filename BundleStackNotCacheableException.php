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

use Exception;

/**
 * Class BundleStackNotCacheableException.
 */
class BundleStackNotCacheableException extends Exception
{
    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Construct the exception. Note: The message is NOT binary safe.
     *
     * @link http://php.net/manual/en/exception.construct.php
     *
     * @param string $kernelNamespace Kernel namespace
     * @param string $bundleNamespace Bundle namespace
     */
    public function __construct($kernelNamespace, $bundleNamespace)
    {
        $message = sprintf(
            'Bundle stack of kernel %s cannot be cached because bundle %s is defined as an object instance instead of a namespace',
            $kernelNamespace,
            $bundleNamespace
        );

        parent::__construct($message);
    }
}
