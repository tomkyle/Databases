<?php
namespace tests;

use \tomkyle\Databases\DatabaseFactory;
use \tomkyle\Databases\DatabaseConfigInterface;
use \tomkyle\Databases\DatabaseConfig;

class DatabaseFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Provides valid arguments to DatabaseFactory ctor.
     *
     * @dataProvider provideValidCtorArguments
     */
    public function testValidFactoryOnCtor($valid)
    {
        $this->assertInstanceOf(
            '\tomkyle\Databases\DatabaseFactory',
            new DatabaseFactory( $valid ));
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
