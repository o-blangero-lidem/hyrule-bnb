<?php

namespace Symplefony;

use Exception;
use PDO;

class Database
{
    private const PDO_OPTIONS = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
    
    private static ?PDO $pdo_instance = null;

    public static function getPDO(): PDO
    {
        if( is_null( self::$pdo_instance ) ) {
            $dsn = sprintf( 'mysql:host=%s;dbname=%s', $_ENV['MYSQL_HOST'], $_ENV['MYSQL_DATABASE'] );

            self::$pdo_instance = new PDO( $dsn, $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], self::PDO_OPTIONS );
        }
        
        return self::$pdo_instance;
    }
    
    private function __construct() { }
    private function __clone() { }
    public function __wakeup()
    {
        throw new Exception( "Non c'est interdit !" );
    }
}