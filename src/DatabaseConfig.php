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

class DatabaseConfig extends DatabaseConfigAbstract implements DatabaseConfigInterface
{


    public $charset  = 'utf8';
    public $type     = 'mysql';
    public $port     = 3306;


/**
 * @param  string $host
 * @param  string $database
 * @param  string $user
 * @param  string $pass
 * @param  string $charset Default: "utf8"
 * @param  string $type    Default: "mysql"
 * @param  string $port    Default: "3306"
 * @uses   apply()
 */
    public function __construct( $config = null)
    {
        if (isset( $config )) {
            $this->apply( $config );
        }
    }


/**
 * @param  array|object $config configuration object
 *
 * @return DatabaseConfig
 * @uses   setType()
 * @uses   setHost()
 * @uses   setPort()
 * @uses   setUser()
 * @uses   setPassword()
 * @uses   setDatabase()
 * @uses   setCharset()
 */
    public function apply( $config )
    {
        if (is_array($config)) {
            $config = (object) $config;
        }
        if (!$config instanceOf \StdClass) {
            return false;
        }

        if (isset($config->host))     $this->setHost( $config->host );
        if (isset($config->database)) $this->setDatabase( $config->database );
        if (isset($config->user))     $this->setUser( $config->user );
        if (isset($config->username)) $this->setUser( $config->username );
        if (isset($config->password)) $this->setPassword( $config->password );
        if (isset($config->pass))     $this->setPassword( $config->pass );
        if (isset($config->charset))  $this->setCharset(  $config->charset );
        if (isset($config->type))     $this->setType( $config->type );
        if (isset($config->port))     $this->setPort( $config->port );
        return $this;
    }




}
