<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('util/Util.class.php');

class ControllerGenerator
{

    private $tableName;
    private $className;
    private $urlMapping;

    private $filePath;

    function __construct($tableName, $className, $urlMapping)
    {

        $this->tableName = $tableName;
        $this->className = $className;
        $this->urlMapping = $urlMapping;

        $this->filePath = '../files/' . $this->tableName . '/' . $this->className . 'Controller.java';

    }

    public function generate()
    {

        $createdSuccess = false;

        if (Util::createFile(Util::CONTROLLER_TEMPLATE_PATH, $this->filePath)) {

            if ($this->writeController()) {

                $createdSuccess = true;

            }

        }

        return $createdSuccess;

    }

    private function writeController()
    {

        $codeWritte = false;

        $code = file_get_contents($this->filePath);

        $code = str_replace("**PACKAGE_CONFIG**", Util::getPackage(), $code);
        $code = str_replace("**CLASS_NAME**", $this->className, $code);
        $code = str_replace("**URL_MAPPING**", $this->urlMapping, $code);

        if (file_put_contents($this->filePath, $code))
            $codeWritte = true;

        return $codeWritte;

    }

}