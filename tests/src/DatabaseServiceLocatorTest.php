<?php
namespace tests;

use \tomkyle\Databases\DatabaseServiceLocator;

class DatabaseServiceLocatorTest extends TestCaseBaseClass
{

    /**
     * Test DatabaseServiceLocator against a MySQL-Database on Travis CI.
     */
    public function testConnectionFactoriesMethodsOnTravisMySql()
    {
        if (!$this->isTravisCi()) {
            return true;
        }

        $describe = array(
            'travis' => $this->getTravisMysqlDatabaseDescription()
        );

        $service = new DatabaseServiceLocator( $describe );

        $this->assertInstanceOf('\tomkyle\Databases\DatabaseServiceLocator', $service);
        $this->assertInstanceOf('\mysqli',                    $service['travis']->getMysqli());
        $this->assertInstanceOf('\PDO',                       $service['travis']->getPdo());
        $this->assertInstanceOf('\Aura\Sql\Connection\Mysql', $service['travis']->getAuraSql());
    }

    /**
     * Challenges the DatabaseServiceLocator ctor with invalid arguments.
     *
     * @expectedException InvalidArgumentException
     * @dataProvider      provideInvalidCtorArguments
     */
    public function testThrowsInvalidArgumentExceptionOnNotArrayOrStdClass($invalid)
    {
        new DatabaseServiceLocator( $invalid );
    }

    /**
     * Provides valid arguments to DatabaseServiceLocator ctor.
     *
     * @dataProvider provideValidCtorArguments
     */
    public function testValidConfigOnCtor($valid)
    {
        $this->assertInstanceOf(
            '\tomkyle\Databases\DatabaseServiceLocator',
            new DatabaseServiceLocator( $valid ));
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
        return array(
            'db1' => array(
                'host'     => "localhost",
                'database' => "database1",
                'user'     => "root",
                'pass'     => "secret",
                'charset'  => "utf8"
            ),
            'db2' => array(
                'host'     => "localhost",
                'database' => "database2",
                'user'     => "root",
                'pass'     => "password",
                'charset'  => "utf8"
            )
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
