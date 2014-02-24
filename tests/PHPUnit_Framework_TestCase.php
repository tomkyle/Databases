<?php
namespace tomkyle;


class PHPUnit_Framework_TestCase extends \PHPUnit_Framework_TestCase
{



    /**
     * @var PDO
     */
    private $pdo;



    /**
     * Creates test table, only on Travis CI
     */
    public function setUp()
    {
        if (!$this->isTravisCi()) {
            return true;
        }
        // $S_SERVER['DB'] = mysql
        $this->pdo = new \PDO(
            $GLOBALS['db_dsn'],
            $GLOBALS['db_username'],
            $GLOBALS['db_password']);
        $this->pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->query("CREATE TABLE IF NOT EXISTS hello (what VARCHAR(50) NOT NULL)");
    }


    /**
     * Drop test table, only on Travis CI
     */
    public function tearDown()
    {
        if (!$this->isTravisCi()) {
            return true;
        }
        $this->pdo->query("DROP TABLE hello");
    }


    /**
     * Checks if Test Suite is run on Travis CI.
     *
     * @return boolean
     *
     * @see  http://docs.travis-ci.com/user/ci-environment/
     */
    public function isTravisCi()
    {
        return (isset($_SERVER['CI'])
            and isset($_SERVER['TRAVIS'])
            and $_SERVER['CI']     == "true"
            and $_SERVER['TRAVIS'] == "true");
    }

    /**
     * Returns valid database data for MySQL on Travis CI
     * as defined in Travis and PHPUnit configuration files.
     *
     * See:
     *
     *   - `.travis.yml` config file
     *   - `phpunit.xml.dist` config file
     *   - and documentation on Travis CI.
     *
     * @return boolean
     * @see http://docs.travis-ci.com/user/database-setup/
     */
    public function getTravisMysqlDatabaseDescription()
    {
        return array(
          'host'     => $GLOBALS['db_host'],
          'database' => $GLOBALS['db_database'],
          'user'     => $GLOBALS['db_username'],
          'pass'     => $GLOBALS['db_password'],
          'type'     => $_SERVER['DB']
        );
    }



    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method's return value.
     *
     * @author Juan Treminio <https://jtreminio.com>
     * @see    https://jtreminio.com/2013/03/unit-testing-tutorial-part-3-testing-protected-private-methods-coverage-reports-and-crap/
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}
