<?php
/**
 * This file is part of tomkyle/Databases.
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

/**
 * DatabaseConfigAbstract
 *
 * Implements the getter methods of DatabaseConfigInterface
 * and appropriate setter methods as well.
 *
 * Fluent interface: All setter methods return the instance itself.
 */
abstract class DatabaseConfigAbstract implements DatabaseConfigInterface
{

/**
 * @var string
 */
    public $type;

/**
 * @var string
 */
    public $host;

/**
 * @var string
 */
    public $database;

/**
 * @var string
 */
    public $user;

/**
 * @var string
 */
    public $password;

/**
 * @var string
 */
    public $charset;

/**
 * @var int
 */
    public $port;


    /**
     * Checks if the current configuration is valid.
     * @return bool
     */
    abstract public function valid();


/**
 * @return string|null
 */
    public function getHost()
    {
        return $this->host;
    }

/**
 * @param  string|null $host
 * @return object Fluent interface
 */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }


/**
 * @return string|null
 */
    public function getDatabase()
    {
        return $this->database;
    }

/**
 * @param  string|null $database
 * @return object Fluent interface
 */
    public function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }



/**
 * @return string|null
 */
    public function getUser()
    {
        return $this->user;
    }

/**
 * @param  string|null $user
 * @return object Fluent interface
 */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

/**
 * @return string|null
 */
    public function getPassword()
    {
        return $this->password;
    }

/**
 * @param  string|null $password
 * @return object Fluent interface
 */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }









/**
 * @return string|null
 */
    public function getType()
    {
        return $this->type;
    }

/**
 * @param  string|null $type
 * @return object Fluent interface
 */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

/**
 * @return string|null
 */
    public function getCharset()
    {
        return $this->charset;
    }

/**
 * @param  string|null $charset
 * @return object Fluent interface
 */
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

/**
 * @return string|null
 */
    public function getPort()
    {
        return $this->port;
    }

/**
 * @param  string|null $port
 * @return object Fluent interface
 */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

}
