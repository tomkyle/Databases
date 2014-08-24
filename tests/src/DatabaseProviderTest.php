<?php
namespace tests;

use \tomkyle\Databases\DatabaseProvider;
use \tomkyle\Databases\DatabaseConfig;
use \tomkyle\Databases\DatabaseConfigInterface;

class DatabaseProviderTest extends TestCaseBaseClass
{





    /**
     * Test DatabaseProvider against a MySQL-Database on Travis CI.
     */
    public function testConnectionFactoryMethodsOnTravisMySql()
    {
        if (!$this->isTravisCi()) {
            return true;
        }

        $describe = $this->getTravisMysqlDatabaseDescription();

        $dp = new DatabaseProvider( new DatabaseConfig($describe) );

        $this->assertInstanceOf('\tomkyle\Databases\DatabaseProvider', $dp);

        $this->assertInstanceOf('\mysqli',                    $dp->getMysqli());
        $this->assertInstanceOf('\PDO',                       $dp->getPdo());
        $this->assertInstanceOf('\Aura\Sql\Connection\Mysql', $dp->getAuraSql());
    }



    /**
     * Provides valid arguments to DatabaseProvider ctor.
     *
     * @dataProvider provideValidCtorArguments
     */
    public function testValidConfigArgumentsOnCtor($valid)
    {
        $dp = new DatabaseProvider( $valid );
        $this->assertInstanceOf('\tomkyle\Databases\DatabaseProvider', $dp);
    }



    /**
     * Tests DatabaseProvider's protected method defineServices,
     * i.e. if it returns the DatabaseProvider (fluent interface)
     *
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
            'database' => "not_existing",
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

}

