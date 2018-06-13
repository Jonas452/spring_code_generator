<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('util/Util.class.php');

class RepositoryGenerator
{

    private $tableName;
    private $className;

    private $filePath;

    function __construct($tableName, $className)
    {

        $this->tableName = $tableName;
        $this->className = $className;

        $this->filePath = '../files/' . $this->tableName . '/' . $this->className . 'Repository.java';

    }

    public function generate()
    {

        $createdSuccess = false;

        if (Util::createFile(Util::REPOSITORY_TEMPLATE_PATH, $this->filePath)) {

            if ($this->writeRepository()) {

                $createdSuccess = true;

            }

        }

        return $createdSuccess;

    }

    private function writeRepository()
    {

        $codeWritte = false;

        $code = file_get_contents($this->filePath);

        $code = str_replace("**PACKAGE_CONFIG**", Util::getPackage(), $code);
        $code = str_replace("**CLASS_NAME**", $this->className, $code);

        if (file_put_contents($this->filePath, $code))
            $codeWritte = true;

        return $codeWritte;

    }

}