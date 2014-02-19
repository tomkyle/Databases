<?php
namespace tests;

use \tomkyle\Databases\DatabaseServiceLocator;

class DatabaseServiceLocatorTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Challenges the DatabaseServiceLocator ctor with invalid arguments.
     *
     * @expectedException InvalidArgumentException
     * @dataProvider      provideInvalidCtorArguments
     */
    public function testThrowsInvalidArgumentExceptionOnNotArrayOrStdClass( $invalid )
    {
        new DatabaseServiceLocator( $invalid );
    }


    /**
     * Provides valid arguments to DatabaseServiceLocator ctor.
     *
     * @dataProvider provideValidCtorArguments
     */
    public function testValidConfigOnCtor( $valid )
    {
        $this->assertInstanceOf(
            '\tomkyle\Databases\DatabaseServiceLocator',
            new DatabaseServiceLocator( $valid ));
    }



    /**
     * Returns a set of invalid (wrong) ctor arguments.
     */
    public function provideInvalidCtorArguments() {
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
    public function provideValidCtorArguments() {
        return array(
            array( $this->createTwoDimArrayConfigArgument() ),
            array( $this->createStdClassConfigArgument() )
        );
    }




    /**
     * Creates a valid, two-dimensional ctor argument.
     *
     * @return array
     */
    protected function createTwoDimArrayConfigArgument()
    {
        return [
            'db1' => [
                'host' =>     "localhost",
                'database' => "database1",
                'user' =>     "root",
                'pass' =>     "secret",
                'charset' =>  "utf8"
            ],
            'db2' => [
                'host' =>     "localhost",
                'database' => "database2",
                'user' =>     "root",
                'pass' =>     "password",
                'charset' =>  "utf8"
            ]
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
            "db1" : {
                "host":     "localhost",
                "database": "database1",
                "user":     "root",
                "pass":     "secret",
                "charset":  "utf8"
            },
            "db2" : {
                "host":     "localhost",
                "database": "database2",
                "user":     "root",
                "pass":     "password",
                "charset":  "utf8"
            }
        }');
    }


}
