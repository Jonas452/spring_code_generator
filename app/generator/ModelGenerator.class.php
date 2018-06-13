<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('util/Util.class.php');

class ModelGenerator
{

    private $tableName;
    private $className;
    private $items;

    private $filePath;

    private $specialColumns;

    function __construct($tableName, $className, $items, $specialColumns)
    {

        $this->tableName = $tableName;
        $this->className = $className;
        $this->items = $items;

        $this->filePath = '../files/' . $this->tableName . '/' . $this->className . '.java';

        $this->specialColumns = $specialColumns;

    }

    public function generate()
    {

        $createdSuccess = false;

        if (Util::createFile(Util::MODEL_TEMPLATE_PATH, $this->filePath)) {

            if ($this->writeModel()) {

                $createdSuccess = true;

            }

        }

        return $createdSuccess;

    }

    private function writeModel()
    {

        $codeWritte = false;

        $code = file_get_contents($this->filePath);

        $code = str_replace("**PACKAGE_CONFIG**", Util::getPackage(), $code);
        $code = str_replace("**TABLE_NAME**", $this->tableName, $code);
        $code = str_replace("**CLASS_NAME**", $this->className, $code);
        $code = str_replace("**FIELDS**", $this->createFields(), $code);

        if (file_put_contents($this->filePath, $code))
            $codeWritte = true;

        return $codeWritte;

    }

    private function createFields() {

        $code = '';

        $i = 0;

        foreach ($this->items as $item) {

            if(empty($this->specialColumns[$item["item_column_" . $i]])) {
                $code .= $this->notSpecialField($item, $i);
            }else {
                $code .= $this->specialField($item, $i, $this->specialColumns[$item["item_column_" . $i]]);
            }

            $i++;

        }

        return $code;

    }

    private function specialField($item, $i, $specialColum) {

        $code = '';

        if($specialColum['constraint_type'] == 'PRIMARY KEY') {

            $code .= str_repeat(' ', 4);
            $code .= "@Id";
            $code .= "\n";

            $code .= str_repeat(' ', 4);
            $code .= "private Integer id;";
            $code .= "\r\n\n";

        }else if($specialColum['constraint_type'] == 'FOREIGN KEY') {

            $code .= str_repeat(' ', 4);
            $code .= "@Valid";
            $code .= "\n";

            $code .= str_repeat(' ', 4);
            $code .= "@OneToOne";
            $code .= "\n";

            $code .= str_repeat(' ', 4);
            $code .= "private " . LabelGenerator::className($specialColum["table_name"]) . " " . LabelGenerator::verboseVariableName( $item["item_column_" . $i] ) . ";";
            $code .= "\r\n\n";

        }

        return $code;

    }

    private function notSpecialField($item, $i) {

        $code = '';

        //ADD @NotBlank
        if($item['item_is_nullable_' . $i] == 'NO') {
            $code .= str_repeat(' ', 4);
            $code .= "@NotBlank";
            $code .= "\n";
        }

        //ADD @Length if there is one
        if(!empty($item['item_length_' . $i])) {
            $code .= str_repeat(' ', 4);
            $code .= "@Length(max = " . $item['item_length_' . $i] . ")";
            $code .= "\n";
        }

        $variableType = "";

        switch ($item['item_data_type_' . $i]) {
            case "integer":
            case "bigint":
                $variableType = "Integer";
                break;
            case "numeric":
                $variableType = "Double";
                break;
            case "boolean":
                $variableType = "Boolean";
                break;
            default:
                $variableType = "String";
                break;
        }

        $code .= str_repeat(' ', 4);
        $code .= "private " . $variableType . " " . LabelGenerator::verboseVariableName( $item["item_column_" . $i] ) . ";";
        $code .= "\r\n\n";

        return $code;

    }

}