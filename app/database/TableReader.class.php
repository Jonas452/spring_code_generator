<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('Connection.class.php');

class TableReader
{

    public static function getInfo( $tableName )
    {

        $config = LoadConfig::get();
        $type = $config->getType();

        $schema   = "information_schema.columns";
        $table    = "table_name";
        $column   = "column_name";
        $datatype = "data_type";
        $nullable = "is_nullable";
        $length   = "character_maximum_length";

        if ( $type == "mysql" ) {

            $schema   = strtoupper( $schema );
            $table    = strtoupper( $table );
            $column   = strtoupper( $column );
            $datatype = strtoupper( $datatype );
            $nullable = strtoupper( $nullable );
            $length   = strtoupper( $length );

        }

        try {

            $pdo = Connection::get()->connect();

            $stmt = $pdo->query( "SELECT * FROM {$schema} WHERE {$table} = '{$tableName}';" );

            while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {

                if($row[ $column ] != 'active' && $row[ $column ] != 'data_creation' && $row[ $column ] != 'data_modification') {

                    $data[] = [
                        "column_name" => $row[ $column ],
                        "data_type"   => $row[ $datatype ],
                        "is_nullable" => $row[ $nullable ],
                        "length"      => $row[ $length ]
                    ];

                }

            }

            return $data;

        } catch ( PDOException $e ) {

            echo $e->getMessage();

        }

    }

    public static function getSpecialColumns($tableName) {

        $config = LoadConfig::get();

        $query = "SELECT
                    ccu.table_name,
                    ccu.table_schema,
                    kc.column_name,
                    tc.constraint_type
                FROM information_schema.table_constraints tc
                JOIN information_schema.key_column_usage kc ON kc.table_name = tc.table_name AND kc.constraint_name = tc.constraint_name
                JOIN information_schema.constraint_column_usage AS ccu ON ccu.constraint_name = tc.constraint_name
                WHERE tc.table_name = '"  . $tableName . "';";

        try {

            $pdo = Connection::get()->connect();

            $stmt = $pdo->query( $query );

            while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {

                if($row[ "column_name" ] != 'active' && $row[ "column_name" ] != 'data_creation' && $row[ "column_name" ] != 'data_modification') {

                    $data[$row[ "column_name" ]] = [
                        "table_name" => $row[ "table_name" ],
                        "column_name" => $row[ "column_name" ],
                        "constraint_type"   => $row[ 'constraint_type' ],
                    ];

                }

            }

            return $data;

        } catch ( PDOException $e ) {

            echo $e->getMessage();

        }

    }

}

?>
