#Databases Service Locator  

DatabaseServiceLocator is a service locator for generic connections to common database APIs. As an extension of [Pimple](https://github.com/fabpot/Pimple), it combines the Singleton-behaving factories provided by Pimple with a simplified creation of connections to [PDO](http://de.php.net/manual/en/book.pdo.php), [mysqli](http://www.php.net/manual/en/book.mysqli.php) and [Aura.SQL v1.3](https://github.com/auraphp/Aura.Sql/tree/master). 

[![Build Status](https://travis-ci.org/tomkyle/Databases.png?branch=master)](https://travis-ci.org/tomkyle/Databases)
[![Coverage Status](https://coveralls.io/repos/tomkyle/Databases/badge.png)](https://coveralls.io/r/tomkyle/Databases)

##In a Nutshell

1. **Describe database** connections, e.g. in plain config object
2. Setup **DatabaseServiceLocator** with config object 
3. Get **ConnectionFactory** from ServiceLocator
4. **Grab your connection** for the database API you like

[Show it already!](#usage)


##Requirements

###Dependencies
This library requires PHP 5.4 or later, and has no dependencies except from Fabien Potencier's [Pimple](https://github.com/fabpot/Pimple) library. 



###Installation

This library is installable and autoloadable via Composer. During installation, Composer will suggest to install [Aura.SQL v1.3](http://github.com/auraphp/Aura.Sql/tree/1.3.0), if you have not already. Install from command line or add the following `require` element in your `composer.json` file:

    "require": {
        "tomkyle/databases": "dev-master"
    }





##Configuration

Assume your project deals with a couple of different databases, with credentials and stuff in a JSON config file. First, describe each connection with config options (see [full list below](#configuration-options)), like this:

###Sample config file
```json
{
  "first_db" : {
    "host":     "db_host",
    "database": "db_name",
    "user":     "db_user",
    "pass":     "db_pass"
  },
  "second_db" : {
    "host":     "other_host",
    "database": "other_db",
    "user":     "other_user",
    "pass":     "other_pass",
    "type":     "not_mysql",
    "charset":  "utf8"
  }
}
```

###Configuration options

######Mandatory
If one of these fields is empty or missing, an `RuntimException` will be thrown.

- **host:** The host name
- **database:** The name of the database
- **user** or **username:** the database user
- **pass** or **password:** the database password

######Optional
- **charset:** the charset to use, defaults to `utf8`
- **type:** the database type, defaults to `mysql`
- **port:** the database port, defaults to `3306`


##Usage

1. Parse config contents into a `StdClass` object
2. Create a new instance of `DatabaseServiceLocator`,  
   passing in your database descriptions object from above
3. Get your Factory instance for your database
4. Let factory create generic connection 

```php
$config = json_decode( file_get_contents( 'config.json' ));
$databases = new DatabaseServiceLocator( $config );

// 1. Get DatabaseFactory instance, Pimple-style:
$foo_factory = $databases['first_db'];

// 2. Let factory create Aura.SQL connection:
$foo_aura = $foo_factory->getAuraSql();

// Shortcut: Create connection in one step
$bar_pdo    = $databases['second_db']->getPdo();
$bar_mysqli = $databases['second_db']->getMysqli();
```

####Shortcut/Oneliner
Since both ServiceLocator and Factories are Pimple extensions, you can get your connection in one call as well:

```php
$databases = new DatabaseServiceLocator( $config );
$foo_pdo = $databases['first_db']['pdo'];
```


###Retrieving connections
Each database passed in the `DatabaseServiceLocator` will be available like an array member. The database returned will be a Singleton instance of `DatabaseFactory`:

```php
$foo_factory = $databases['foo_db'];  
echo get_class( $foo_factory );
// "DatabaseFactory"
```

Each `DatabaseFactory` instance works as a Connection factory that provides and instantiates different kinds of Singleton database connections. You may grab your connection either by calling a getter Method or access it as array key (the Pimple way):

####PDO Connections

```php
$pdo = $foo_factory['pdo'];
// or 
$pdo = $foo_factory->getPdo();
echo get_class( $pdo );
// "PDO"
```

####Aura.SQL Connections

```php
$aura = $foo_factory['aura.sql'];
// or 
$aura = $foo_factory->getAuraSql();
echo get_class( $aura );
// "Aura\Sql\Connection\Mysql", for example

// Common configuration afterwards
$aura->setAttribute( \PDO::ATTR_ERRMODE,             \PDO::ERRMODE_EXCEPTION );
$aura->setAttribute( \PDO::ATTR_DEFAULT_FETCH_MODE,  \PDO::FETCH_OBJ);
```



####mysqli Connections

```php
$mysqli = $foo_factory['mysqli'];
// or 
$mysqli = $foo_factory->getMysqli();
echo get_class( $mysqli );
// "mysqli"
```

###Best practice
If a class needs a special database connection, let's say PDO, here's how: 

1. Get your connection factory from DatabaseServiceLocator
2. Let it create a PDO connection for you 
3. Inject the resulting PDO. 

…and the next class, relying on Aura.SQL dependencies:

4. Take the very same connection factory instance (remember: Singleton!)
5. Let it create a Aura.SQL connection for you
6. Inject the resulting Aura.SQL Mysql Connection. 

This way, when things go wrong, they do so outside your business classes (Inversion of Control principle).

Paul M. Jones recently covers this topic in his recently published article [“What Application Layer Does A DI Container Belong In?”](http://paul-m-jones.com/archives/5914).


##Questions and Answers


###Wait, isn't this an Anti-Pattern?
**Yes, if you use** the DatabaseServiceLocator as dependency inside your classes, injecting it in constructors or Setter methods, type-hinting against it or not. **No, if you use** it in your composition root or configuration environment.


###How far are the connections configured?
Beside from their charset, the connections “ex factory” are not configured specially. So if you like to change the default fetch mode or (think of `PDO::setAttribute`), you may want to configure it yourself. Remember, each connection is generic!

###What about Aura.SQL v2 ?
Currently, DatabaseServiceLocator supports [Aura.SQL v1.3](http://github.com/auraphp/Aura.Sql/tree/1.3.0). With Aura v2 coming soon, Aura.SQL splits up into three modules *Aura.SQL v2  Aura.SQL_Query* and *Aura.SQL_Schema* – see Paul M. Jones' article [“A Peek At Aura v2 -- Aura.Sql and ExtendedPdo”](http://auraphp.com/blog/2013/10/21/aura-sql-v2-extended-pdo/). 

I will try to add v2 support as soon as v2 has become stable or standard, and I got used to it. Just in case you already are, you are invited to fork your own DatabaseServiceLocator :-)



##Automated tests

[![Build Status](https://travis-ci.org/tomkyle/Databases.png?branch=master)](https://travis-ci.org/tomkyle/Databases)
[![Coverage Status](https://coveralls.io/repos/tomkyle/Databases/badge.png)](https://coveralls.io/r/tomkyle/Databases)

Currently, the test suite covers:

- Instantiation of `DatabaseServiceLocator` with both valid and invalid arguments.
- Instantiation of `DatabaseFactory` with with both valid and invalid arguments.
- Instantiation of `DatabaseConfig` with with both valid, invalid and incomplete arguments.
- more detailled test to come (I am a bloody but hooked beginner with PHPUnit/TravisCI)

Any help with testing or useful hint regarding what and how to test next will be appreciated!

