<?php

class Util
{

    const MODEL_TEMPLATE_PATH = __DIR__ . "/template/Model.java";
    const CONTROLLER_TEMPLATE_PATH = __DIR__ . "/template/Controller.java";
    const REPOSITORY_TEMPLATE_PATH = __DIR__ . "/template/Repository.java";

    public static function successMsg( $msg )
    {

        echo '<p style="color:green;">' . $msg . '<br/></p>';

    }

    public static function errorMsg( $msg )
    {

        echo '<p style="color:red;">' . $msg . '<br/></p>';

    }

    public static function createFile( $templateFile, $newFile )
    {

        if( copy( $templateFile, $newFile ) )
            return true;
        else
            return false;

    }

    public static function getItemsFromPOST( $post )
    {

        $data = array();

        $i = 0;
        $temp = array();

        foreach ( $post as $key => $value ) {

            $str = explode( "_", $key );

            if( $str[0] == "item" ) {

                $position = $str[sizeof($str) - 1];

                if( $position == $i ) {

                    $temp[$key] = $value;

                }else {

                    $data[$i] = $temp;
                    $temp = array();
                    $temp[$key] = $value;

                    $i++;

                }

            }

        }

        $data[$i] = $temp;

        return $data;

    }

    public static function getPackage()
    {

        $file = file_get_contents('database/config.json');
        $config = json_decode($file, true);

        return $config['package'];

    }

}
