<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('database/TableReader.class.php');
require_once('generator/LabelGenerator.class.php');
require_once('generator/IsNullableGenerator.class.php');

include("../content.php");

headerContent("../");

class CodeSpecification
{

    function __construct()
    {

        if (isset($_GET['tableName'])) {

            $data = TableReader::getInfo($_GET['tableName']);

            if ($data) {

                echo '
                <div class="panel-heading"><b>INFOS</b></div>
                
                      <div class="panel-body">
                            <form  class="navbar-form navbar" action="CodeGenerator.class.php" method="POST">    
                    
                                <input type="hidden" name="tableName" value="' . $_GET['tableName'] . '">
                        
                                <div class="form-group">
                                
                                    <input type="checkbox" name="model" checked>
                                    <label for="model">Model</label>
                                    
                                    <br>
                                    
                                    <input type="checkbox" name="repository" checked>
                                    <label for="repository">Repository</label>
                                    
                                    <br>
                                    
                                    <input type="checkbox" name="controller" checked>
                                    <label for="controller">Controller</label>
                                    
                    
                                </div>
                                
                                <br>
                                <br>
                                
                                <div class="form-group">
                                
                                    <label for="type">URL Mapping</label>
                                    <input type="text" name="urlMapping" class="form-control" value="'. LabelGenerator::urlMappingName($_GET['tableName']) .'"> 
                                    
                                </div>
                                
                                <br>
                                <br>
                                
                                 <button type="submit" class="btn btn-success">Create</button>
                                 
                                <br>

                                <h3>MODEL INFO</h3>' .

                                $this->createList($data)

                    .       '</form>
                      </div>
                </div>';

                $this->createList($data);

            } else {

                echo 'Table does not exist.';
                echo '
                <div class="panel-heading"><b>INFOS</b></div>
                      <div class="panel-body">
                         <form  class="navbar-form navbar" action="../index.php">
                            <button type="submit" class="btn btn-info">Back to index</button>
                         </form>
                       </div>
                 </div>';

            }

        }

    }

    private function createList($data)
    {

        $table = '<div class="table-responsive ">          
                    <input type="checkbox" class="check" id="checkAll" checked="checked"/> Select all
                    <table id="example" class="table table-hover" cellspacing="0" width="100%">';
        $table .= '<thead>
                                <tr align="left">                         
                                  <th>Goes?</th>
                                  <th>Column</th>
                                  <th>Is Nullable?</th>
                                  <th>Database Info</th>
                                </tr>
                              </thead>';
        $i = 0;

        foreach ($data as $item) {

            $table .= $this->listItem($item, $i);

            $i++;

        }

        $table .= '</table></div>';

        return $table;

    }

    private function listItem($item, $i)
    {
        $databaseInfo = '<span style="color:blue;">' . $item['column_name'] . '</span>';
        $databaseInfo .= '<span style="color:green;"> ' . $item['data_type'];
        $databaseInfo .= $item['length'] > 0 ? '(' . $item['length'] . ')' : '';
        $databaseInfo .= '</span>';
        $databaseInfo .= $item['is_nullable'] == 'NO' ? ' <b>not null</b>' : '';

        return '<tbody>
                    <tr>
                     <td><input type="checkbox" class="check" name="item_' . $i . '" checked="true"></td>
                     <td><input type="text" style="border: 0px;" name="item_column_' . $i . '" value="' . $item['column_name'] . '" readonly></td>
                     <td>' . IsNullableGenerator::getInfo($item['is_nullable'], $i) . '</td>
                     <td>' . $databaseInfo . '</td>
                     <td><input type="hidden" name="item_length_' . $i . '" value="' . $item['length'] . '"></td>
                     <td><input type="hidden" name="item_data_type_' . $i . '" value="' . $item['data_type'] . '"></td>
                  </tr>
              </tbody>';

    }

}

new CodeSpecification();

footerContent("../");

################# Check All itens form table #################
echo '<script>
                    $("#checkAll").click(function () {
                        $(".check").prop(\'checked\', $(this).prop(\'checked\'));
                    });
         </script>';
################# Check All itens form table #################