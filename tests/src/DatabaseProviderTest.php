<?php
namespace tests;

use \tomkyle\Databases\DatabaseProvider;
use \tomkyle\Databases\DatabaseConfig;
use \tomkyle\Databases\DatabaseConfigInterface;

class DatabaseProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Provides valid arguments to DatabaseProvider ctor.
     *
     * @dataProvider provideValidCtorArguments
     */
    public function testValidFactoryOnCtor($valid)
    {
        $dp = new DatabaseProvider( $valid );
        $this->assertInstanceOf('\tomkyle\Databases\DatabaseProvider', $dp);
    }

    /**
     * @dataProvider provideValidCtorArguments
     */
    public function testFluentInterfaceOfProtectedMethodDefineServices($valid)
    {
        $dp = new DatabaseProvider( $valid );
        $dp_fluent_interface = $this->invokeMethod($dp, 'defineServices');
        $this->assertInstanceOf('\tomkyle\Databases\DatabaseProvider', $dp_fluent_interface);
    }


    /**
     * Returns a set of valid ctor arguments.
     */
    public function provideValidCtorArguments()
    {
        $mock_dc = $this->getMock('\tomkyle\Databases\DatabaseConfigInterface');
        $mock_dc->expects($this->any())
        ->method("valid")
        ->will($this->returnValue( true ));

        return array(
            array( $mock_dc ),
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
        return array(
            'host' =>     "localhost",
            'database' => "database1",
            'user' =>     "root",
            'pass' =>     "secret",
            'charset' =>  "utf8"
        );
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


/**
 * Call protected/private method of a class.
 *
 * @param object &$object    Instantiated object that we will run method on.
 * @param string $methodName Method name to call
 * @param array  $parameters Array of parameters to pass into method.
 *
 * @return mixed Method return.
 *
 * @see  https://jtreminio.com/2013/03/unit-testing-tutorial-part-3-testing-protected-private-methods-coverage-reports-and-crap/
 */
public function invokeMethod(&$object, $methodName, array $parameters = array())
{
    $reflection = new \ReflectionClass(get_class($object));
    $method = $reflection->getMethod($methodName);
    $method->setAccessible(true);

    return $method->invokeArgs($object, $parameters);
}

}

