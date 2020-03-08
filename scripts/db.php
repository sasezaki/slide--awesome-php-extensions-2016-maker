<?php
use Zend\Db\Sql\ {
    Sql, Ddl, Ddl\Column
};

require_once __DIR__ . '/vendor/autoload.php';

$adapter = new Zend\Db\Adapter\Adapter([
    'driver'   => 'Pdo_Sqlite',
    'database' => __DIR__ . '/data/sqlite.db',
]);

$sql = new Sql($adapter);

foreach (['extensions', 'categories', 'pecl', 'vcs'] as $tableName) {
//    try {
//        $drop = new Ddl\DropTable($tableName);
//        $adapter->query(
//            $sql->buildSqlString($drop),
//            $adapter::QUERY_MODE_EXECUTE
//        );
//    } catch (\Exception $e) {
//        echo $e->getMessage(), PHP_EOL;
//    }

    try {
        $create = getCreateTable($tableName);
        $adapter->query(
            $sql->buildSqlString($create),
            $adapter::QUERY_MODE_EXECUTE
        );
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}

//echo $sql->buildSqlString(getCreateTable('extensions'));

function getCreateTable(string $tableName) : Ddl\CreateTable {
    switch ($tableName) {
        case 'extensions' : // @see https://github.com/php/web-pecl/blob/master/sql/packages.sql
            $table = new Ddl\CreateTable('extensions');
            $table->addColumn(new Column\Integer('id'));
            $table->addColumn(new Column\Varchar('name', 255));
            $table->addColumn(new Column\Integer('category'));
            break;
        case 'categories' : // @see https://github.com/php/web-pecl/blob/master/sql/categories.sql
            $table = new Ddl\CreateTable('categories');
            $table->addColumn(new Column\Integer('id'));
            $table->addColumn(new Column\Varchar('name', 80));
            $table->addConstraint(new Ddl\Constraint\UniqueKey('name'));
            break;
        case 'pecl' :
            $table = new Ddl\CreateTable('pecl');
            $table->addColumn(new Column\Varchar('extension_name', 255));
            $table->addColumn(new Column\Varchar('package_name', 80));
            $table->addColumn(new Column\Varchar('url', 1024));
            break;
        case 'vcs' :
            $table = new Ddl\CreateTable('vcs');
            $table->addColumn(new Column\Varchar('extension_name', 255));
            $table->addColumn(new Column\Varchar('url', 1024));
            break;
        default:
            throw new InvalidArgumentException;
    }

    return $table;
}