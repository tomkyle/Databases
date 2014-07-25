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

use \Pimple\Container as Pimple;
use \Aura\Sql\ConnectionFactory as AuraConnectionFactory;

/**
 * DatabaseProvider
 *
 * Provides you with methods for creating generic connections
 * to PDO, mysqli and Aura.SQL. Requires Pimple as base class.
 *
 * Configuration and Usage:
 *
 *    <?php
 *    // Describe your Database (somehow)
 *    $description = new \StdClass;
 *    $description->host     =  "db_host";
 *    $description->database =  "db_name";
 *    $description->user     =  "db_user";
 *    $description->pass     =  "db_pass"; // and so on
 *
 *    // Create factory
 *    $factory = new DatabaseProvider ( new DatabaseConfig ( $description ) );
 *
 *    // Grab your connection
 *    $aura   = $factory->getAuraSql();
 *    $pdo    = $factory->getPdo();
 *    $mysqli = $factory->getMysqli();
 *    ?>
 */
class DatabaseProvider extends Pimple implements DatabaseProviderInterface
{

/**
 * @param DatabaseConfigInterface $config  Database description instance
 * @param array                   $options Unused yet, defaults to blank array.
 * @uses  \Pimple::__construct()
 * @uses  defineServices()
 */
    public function __construct(DatabaseConfigInterface $config, $options = array())
    {
        parent::__construct( array(
            'config'  => $config,
            'options' => $options
        ));

        $this->defineServices();
    }

/**
 * Defines how the connections are created (with Pimple, to be concise).
 *
 * The method makes use of Pimple API.
 *
 * Note that for Aura.SQL connections, a generic PDO connection is used.
 * For details, see http://harikt.com/blog/2012/12/06/node-227/
 *
 * @return DatabaseProvider Fluent Interface
 * @uses   \Aura\Sql\ConnectionFactory
 * @uses   \Aura\Sql\ConnectionFactory::newInstance()
 * @uses   \Aura\Sql\ConnectionFactory::setPdo()
 * @uses   DatabaseConfigInterface::getType()
 * @uses   DatabaseConfigInterface::getDatabase()
 * @uses   DatabaseConfigInterface::getHost()
 * @uses   DatabaseConfigInterface::getPort()
 * @uses   DatabaseConfigInterface::getCharset()
 * @uses   DatabaseConfigInterface::getUser()
 * @uses   DatabaseConfigInterface::getPassword()
 */
    protected function defineServices()
    {
        $this->offsetSet('aura.connectionfactory', function () {
            return new AuraConnectionFactory;
        });

        $this->offsetSet('aura.sql', function () {
            $config = $this['config'];
            $aura   = $this['aura.connectionfactory']->newInstance( $config->getType() );
            $aura->setPdo( $this['pdo'] );

            return $aura;
        });

        $this->offsetSet('pdo', function () {
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
        });

        $this->offsetSet('mysqli', function () {
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
        });

        return $this;
    }

/**
 * Returns a generic PDO instance.
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
 * Returns a generic Mysqli instance.
 *
 * @api
 * @return \mysqli
 */
    public function getMysqli()
    {
        return $this['mysqli'];
    }
}
