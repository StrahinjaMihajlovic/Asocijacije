<?php

return [
    'class' => 'yii\db\Connection',


    'dsn' => 'mysql:host=localhost;dbname=Asocijacije',//podesite sledeca 3 parametra u skladu sa kredencijalima sa vaseg servera
    'username' => 'root',
    'password' => '',
	'attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER],

    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
