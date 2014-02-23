<?php
namespace tomkyle;


class PHPUnit_Framework_TestCase extends \PHPUnit_Framework_TestCase
{


    /**
     * Checks if Test Suite is run on Travis CI.
     *
     * @return boolean
     */
    public function isTravisCi()
    {
        return (isset($_SERVER['ci'])
            and isset($_SERVER['TRAVIS'])
            and $_SERVER['CI']     == "true"
            and $_SERVER['TRAVIS'] == "true");
    }

    /**
     * Returns valid database data for MySQL on Travis CI.
     *
     * See `.travis.yml` config file and documentation on Travis CI
     *
     * @return boolean
     * @see  http://docs.travis-ci.com/user/database-setup/
     */
    public function getTravisMysqlDatabaseDescription()
    {
        return array(
          'host'     => "127.0.0.1",
          'database' => "tomkyle_test",
          'user'     => "travis",
          'pass'     => "",
          'type'     => "mysql"
        );
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
