#Database Service Location

This Pimple extension helps you setup and manage multiple database connections without instantiating them at once. Databases are represented by connection factories rather, providing easy access to any common kind of DB connection – the way you like and when you like. 

DatabaseServiceLocator combines the Singleton factories provided by [Pimple](https://github.com/fabpot/Pimple) with a simplified creation of common database connection types, such as [PDO](http://de.php.net/manual/en/book.pdo.php), [mysqli](http://www.php.net/manual/en/book.mysqli.php) and [Aura.SQL (v1)](https://github.com/auraphp/Aura.Sql/tree/master). 

##In a Nutshell

1. **Describe database** connections in plain config object
2. Setup **DatabaseServiceLocator** with config object 
3. Choose database, get **ConnectionFactory**
4. Use database connection, provided by the **driver you like** to work with


##Requirements

###Dependencies
This library requires PHP 5.4 or later, and has no userland dependencies except from Fabien Potencier's [Pimple](https://github.com/fabpot/Pimple) library.

###Installation

This library is installable and autoloadable via Composer with the following
`require` element in your `composer.json` file:

    "require": {
        "tomkyle/databases": "dev-master"
    }

##Wait, isn't this an Anti-Pattern?
**Yes, if you use** the DatabaseServiceLocator as dependency inside your classes, injecting it in constructors or Setter methods, type-hinting against it or not.

**No, if you use** it in your composition root or configuration environment.
If a class needs a special database connection, let's say PDO, here's how: 

1. Get your connection factory from DatabaseServiceLocator
2. Let it create a PDO connection for you 
3. Inject the resulting PDO. 

…and the next class, relying on Aura.SQL dependencies:

4. Take the very same connection factory instance (remember: Singleton!)
5. Let it create a Aura.SQL connection for you
6. Inject the resulting Aura.SQL Mysql Connection. 

This way, when things go wrong, they do so outside your business classes (Inversion of Control principle).




##Configuration

Assume your project deals with a couple of different databases, with credentials and stuff in a JSON config file. First, describe each connection with config options (see [full list below](#configuration-options)), and then, simply parse it contents into a `StdClass` object, like this:

```php
$json_string = '{
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
    "type":     "not_mysql"
    "charset":  "utf8"
  }
}';
$my_databases = json_decode( $json_string );
```

###Configuration options
Currently, the following configuration elements are supported:

- **host:** The host name
- **database:** The name of the database
- **user** or **username:** the database user
- **pass** or **password:** the database password
- **charset:** the charset to use, defaults to `utf8`
- **type:** the database type, defaults to `mysql`
- **port:** the database port, defaults to `3306`


##Usage
Simply create a new instance of `DatabaseServiceLocator` and pass in your database descriptions object from above:

```php
$databases = new DatabaseServiceLocator( $my_databases );

// 1. Get DatabaseFactory instance, Pimple-style:
$foo_factory = $databases['first_db'];

// 2. Let factory create Aura.SQL connection:
$foo_aura = $foo_factory->getAuraSql();

// Shortcut: Create connection in one step
$bar_pdo    = $databases['second_db']->getPdo();
$bar_mysqli = $databases['second_db']->getMysqli();
```

###Retrieving connections
Each database passed in the `DatabaseServiceLocator` will be available like an array member. The database returned will be a Singleton instance of `DatabaseFactory`:

```php
$foo_factory = $databases['foo_db'];
echo get_class( $foo_factory );
// "DatabaseFactory"
```

Each `DatabaseFactory` instance works as a Connection factory that provides and instantiates different kinds of Singleton database connections:

####PDO Connections

```php
$pdo = $foo_factory->getPdo();
echo get_class( $pdo );
// "\PDO"
```

####Aura.SQL Connections

```php
$aura = $foo_factory->getAuraSql();
echo get_class( $aura );
// "\Aura\Sql\Connection\Mysql", for example
```

####mysqli Connections

```php
$mysql = $foo_factory->getMysqli();
echo get_class( $aura );
// "mysqli"
```


