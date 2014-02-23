<?php
namespace tests;

use \tomkyle\Databases\DatabaseProvider;
use \tomkyle\Databases\DatabaseConfig;

class DatabaseProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Provides valid arguments to DatabaseProvider ctor.
     *
     * @dataProvider provideValidCtorArguments
     */
    public function testValidFactoryOnCtor($valid)
    {
        $this->assertInstanceOf(
            '\tomkyle\Databases\DatabaseProvider',
            new DatabaseProvider( $valid ));
    }

    /**
     * Returns a set of valid ctor arguments.
     */
    public function provideValidCtorArguments()
    {
        return array(
            array( new DatabaseConfig( $this->createArrayConfigArgument() )),
            array( new DatabaseConfig( $this->createStdClassConfigArgument() ))
        );
    }

    /**
     * Creates a valid ctor array argument.
     *
     * @return array
     */
    protected function createArrayConfigArgument()
    {
        return [
                'host' =>     "localhost",
                'database' => "database1",
                'user' =>     "root",
                'pass' =>     "secret",
                'charset' =>  "utf8"
            ];
    }

    /**
     * Creates a valid StdClass ctor argument.
     *
     * @return \StdClass
     */
    protected function createStdClassConfigArgument()
    {
        return json_decode('{
                "host":     "localhost",
                "database": "database1",
                "user":     "root",
                "pass":     "secret",
                "charset":  "utf8"
            }');
    }

}

