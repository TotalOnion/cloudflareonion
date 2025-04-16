<?php

if (!getenv('SKIPDB')) {
    // $sqlitedb = __DIR__ . '/sqlite.db';
    // if (file_exists($sqlitedb)) {
    //     echo "Deleting ".$sqlitedb."\n";
    //     unlink($sqlitedb);
    // }

    // echo "Creating new DB\n";
    // passthru('bin/console doctrine:schema:create --env=test');

    // echo "Generating fixtures\n";
    // passthru('bin/console hautelook:fixtures:load -n --env=test');
}

require __DIR__.'/../vendor/autoload.php';
