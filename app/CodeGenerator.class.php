<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once('generator/ModelGenerator.class.php');
require_once('generator/ControllerGenerator.class.php');
require_once('generator/RepositoryGenerator.class.php');
require_once('generator/LabelGenerator.class.php');
require_once('util/Util.class.php');
require_once('database/TableReader.class.php');

include("../content.php");

headerContent("../");

class CodeGenerator
{

    private $tableName;
    private $urlMapping;

    private $className;

    function __construct()
    {

        $itemsPost = Util::getItemsFromPOST($_POST);

        $this->tableName = $_POST['tableName'];
        $this->urlMapping = $_POST['urlMapping'];

        $this->className = LabelGenerator::className($_POST['tableName']);

        if (mkdir('../files/' . $this->tableName, 0777, true)) {

            Util::successMsg('> Folder ' . $this->tableName . ' created with success.');

            if (isset($_POST['model'])) {

                $specialColumns = TableReader::getSpecialColumns($this->tableName);

                $modelGenerator = new ModelGenerator($this->tableName, $this->className, $itemsPost, $specialColumns);

                if ($modelGenerator->generate()) {

                    Util::successMsg('> Model ' . $this->className . ' created with success.');

                } else {

                    Util::errorMsg('> Error creating Model ' . $this->className . '.');

                }

            }

            if (isset($_POST['repository'])) {

                $repositoryGenerator = new RepositoryGenerator($this->tableName, $this->className);

                if ($repositoryGenerator->generate()) {

                    Util::successMsg('> ' . $this->className . 'Repository created with success.');

                } else {

                    Util::errorMsg('> Error creating ' . $this->className . 'Repository.');

                }

            }

            if (isset($_POST['controller'])) {

                $controllerGenerator = new ControllerGenerator($this->tableName, $this->className, $this->urlMapping);

                if ($controllerGenerator->generate()) {

                    Util::successMsg('> ' . $this->className . 'Controller created with success.');

                } else {

                    Util::errorMsg('> Error creating ' . $this->className . 'Controller.');

                }

            }

        } else {

            Util::errorMsg('> Error creating folder ' . $this->tableName . '.');

        }

        echo '<form action="../index.php"><input type="submit" value="Back to index"></form>';

    }

}

new CodeGenerator();


footerContent("../../");
