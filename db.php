<?php

declare(strict_types=1);

function getDatabaseConnection(): PDO
{
    return new PDO(
        'mysql:host=sql204.infinityfree.com;dbname=if0_40471473_yanada;charset=utf8mb4',
        'if0_40471473',
        '',
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
}
