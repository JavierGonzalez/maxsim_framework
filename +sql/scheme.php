<?php # maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>


define('SQL_SCHEME', [
    [
        'table' => 'users',
        'columns' => [
            [
                'name'      => 'id',
                'type_sql'  => 'int',
                'autoincrement' => true,
            ],
            [
                'name'      => 'nick',
                'type_sql'  => 'string',
                'size_min'  => 3,
                'size_max'  => 25,
                'validate'  => "/[a-zA-Z0-9_.]{3,25}/",
            ],
            [
                'name'      => 'email',
                'type_sql'  => 'string',
                'default'   => null,
                'align'     => 'right',
                'size_min'  => 3,
                'size_max'  => 100,
                'validate'  => "/[a-zA-Z0-9_-.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/",
            ],
        ],
    ],
]);