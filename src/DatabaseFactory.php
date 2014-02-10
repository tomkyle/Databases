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

use \Pimple;
use \Aura\Sql\ConnectionFactory;


class DatabaseFactory extends Pimple implements DatabaseFactoryInterface
{
    protected $config;

    public function __construct( DatabaseConfigInterface $config, $options = [] )
    {
        parent::__construct( [
            'config'  => $config,
            'options' => $options
        ]);

        $this->defineServices();
    }



    protected function defineServices()
    {
        $this['aura.connectionfactory'] = function() {
            return new ConnectionFactory;
        };


        $this['aura.sql'] = function( ) {
            $config = $this['config'];

            // Use PDO instance, as described here:
            // http://harikt.com/blog/2012/12/06/node-227/
            $aura = $this['aura.connectionfactory']->newInstance( $config->getType() );
            $aura->setPdo( $this['pdo'] );
            return $aura;
        };


        $this['pdo'] = function( ) {
            $config = $this['config'];
            $dsn = "%s:dbname=%s;host=%s;port=%s;charset=%s";
            return new \PDO(
                sprintf($dsn,
                $config->getType(),
                $config->getDatabase(),
                $config->getHost(),
                $config->getPort(),
                $config->getCharset()
                ),
                $config->getUser(),
                $config->getPassword()
            );
        };


        $this['mysqli'] = function() {
            $config = $this['config'];

            $mysqli = mysqli_connect(
                $config->getHost(),
                $config->getUser(),
                $config->getPassword(),
                $config->getDatabase(),
                $config->getPort()
            );
            $mysqli->set_charset( $config->getCharset() );
            return $mysqli;
        };
    }






/**
 * Returns a PDO instance.
 *
 * @api
 * @return \PDO
 */
    public function getPdo()
    {
        return $this['pdo'];
    }



/**
 * Returns a Aura.SQL Database Connection
 *
 * @api
 * @return \Aura\Sql\Connection\AbstractConnection
 */
    public function getAuraSql()
    {
        return $this['aura.sql'];
    }


/**
 * Returns a Mysqli instance.
 *
 * @api
 * @return \mysqli
 */
    public function getMysqli()
    {
        return $this['mysqli'];
    }
}
