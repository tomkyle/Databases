<?php
namespace tests;

use \tomkyle\Databases\DatabaseConfig;

class DatabaseConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Challenges the DatabaseConfig ctor with well-formed but incomplete arguments.
     *
     * @expectedException RuntimeException
     * @dataProvider      provideIncompleteCtorArguments
     */
    public function testThrowsRuntimeExceptionOnIncompleteConfigData($incomplete)
    {
        new DatabaseConfig( $incomplete );
    }

    /**
     * Challenges the DatabaseConfig ctor with invalid arguments.
     *
     * @expectedException InvalidArgumentException
     * @dataProvider      provideInvalidCtorArguments
     */
    public function testThrowsInvalidArgumentExceptionOnNotArrayOrStdClass($invalid)
    {
        new DatabaseConfig( $invalid );
    }

    /**
     * Provides valid arguments to DatabaseConfig ctor.
     *
     * @dataProvider provideValidCtorArguments
     */
    public function testValidConfigOnCtor($valid)
    {
        $this->assertInstanceOf(
            '\tomkyle\Databases\DatabaseConfig',
            new DatabaseConfig( $valid ));
    }


    /**
     * Tests the return values of all Getter methods,
     * given full configuration.
     */
    public function testInterceptors()
    {
        $dc = new DatabaseConfig( $this->createFullArrayConfigArgument() );

        $this->assertTrue(
            is_string($dc->getHost())
        and is_string($dc->getDatabase())
        and is_string($dc->getUser())
        and is_string($dc->getType())
        and is_string($dc->getPassword())
        and is_string($dc->getCharset())
        and (
            is_int($dc->getPort())
         or is_string($dc->getPort()))

        );
    }



    /**
     * Returns a set of invalid (wrong) ctor arguments.
     */
    public function provideInvalidCtorArguments()
    {
        return array(
            array( 1 ),
            array( 2.4 ),
            array( "Foo" ),
            array( new \Exception )
        );
    }

    /**
     * Returns a set of valid ctor arguments.
     */
    public function provideValidCtorArguments()
    {
        return array(
            array( $this->createArrayConfigArgument() ),
            array( $this->createStdClassConfigArgument() )
        );
    }

    /**
     * Returns a set of well-fored but incomplete ctor arguments.
     */
    public function provideIncompleteCtorArguments()
    {
        return array(
            array(array(
                // 'host' =>     "localhost",
                'database' => "database1",
                'user' =>     "root",
                'pass' =>     "secret"
            )),
            array(array(
                'host' =>     "localhost",
                // 'database' => "database1",
                'user' =>     "root",
                'pass' =>     "secret"
            )),
            array(array(
                'host' =>     "localhost",
                'database' => "database1",
                // 'user' =>     "root",
                'pass' =>     "secret"
            )),
            array(array(
                'host' =>     "localhost",
                'database' => "database1",
                'user' =>     "root",
                // 'pass' =>     "secret"
            ))
        );
    }

    /**
     * Creates a valid, fully-configured array argument.
     *
     * @return array
     */
    protected function createFullArrayConfigArgument()
    {
        return array(
            'host' =>     "localhost",
            'database' => "database1",
            'user' =>     "root",
            'pass' =>     "secret",
            'charset' =>  "utf8",
            'type' =>     "mysql",
            'port' =>     3306
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
            'pass' =>     "secret"
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

}
