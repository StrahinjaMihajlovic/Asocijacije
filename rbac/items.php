<?php
return [
    'administriranje' => [
        'type' => 2,
        'description' => 'Dozvole za korisnike tipa admin',
    ],
    'korisnik' => [
        'type' => 2,
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'administriranje',
        ],
    ],
];
