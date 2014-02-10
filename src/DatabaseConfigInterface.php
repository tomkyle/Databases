<?php
/**
 * This file is part of tomkyle/DatabaseServiceLocator.
 *
 * Copyright (c) 2014 Carsten Witt
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace tomkyle\Databases;

interface DatabaseConfigInterface
{

/**
 * @return string
 */
    public function getUser();

/**
 * @param  string $user
 * @return object Fluent interface
 */
    public function setUser( $user );

/**
 * @return string
 */
    public function getPassword();

/**
 * @param  string $password
 * @return object Fluent interface
 */
    public function setPassword( $password );



/**
 * @return string
 */
    public function getHost();

/**
 * @param  string $host
 * @return object Fluent interface
 */
    public function setHost( $host );


/**
 * @return string
 */
    public function getType();

/**
 * @param  string $type
 * @return object Fluent interface
 */
    public function setType( $type );


/**
 * @return string
 */
    public function getDatabase();

/**
 * @param  string $database
 * @return object Fluent interface
 */
    public function setDatabase( $database );


/**
 * @return string
 */
    public function getCharset();

/**
 * @param  string $charset
 * @return object Fluent interface
 */
    public function setCharset( $charset );


/**
 * @return int
 */
    public function getPort();

/**
 * @param  string $port
 * @return object Fluent interface
 */
    public function setPort( $port );
}
