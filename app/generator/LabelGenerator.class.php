<?php

class LabelGenerator
{

    public static function verboseVariableName( $columnName ) {

        $variableName = str_replace( "_id", "", $columnName );
        $variableName = ucwords( $variableName, "_" );
        $variableName = lcfirst( $variableName );
        $variableName = str_replace( "_", "", $variableName );

        return $variableName;

    }

    public static function urlMappingName( $table ) {

        $urlMappingName = str_replace( "_", "-", $table );

        return $urlMappingName;

    }

    public static function className( $table ) {

        $className = ucwords( $table, "_" );
        $className = str_replace( "_", "", $className );

        return $className;

    }

}