<?php


define('DB_HOST', 'sql311.infinityfree.com');
define('DB_NOME', 'if0_42967199_root');
define('DB_USUARIO', 'if0_42967199');
define('DB_SENHA', 'LkkLhjEs2XJHvmp');

function conectarBanco(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NOME . ';charset=utf8mb4';

        $opcoes = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $pdo = new PDO($dsn, DB_USUARIO, DB_SENHA, $opcoes);
        } catch (PDOException $erro) {
            die(
                'Não foi possível conectar ao banco de dados. ' .
                'Verifique se o MySQL está rodando e se o banco "assadao_do_ze" ' .
                'foi criado a partir de database/banco.sql. Detalhes: ' . $erro->getMessage()
            );
        }
    }

    return $pdo;
}
