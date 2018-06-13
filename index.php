<?php
include("content.php");

headerContent("");
?>

<div class="panel-heading"><b>Infor Table Name</b></div>
    <div class="panel-body">
        <!-- start content -->
        <form  class="navbar-form navbar-left" action="app/CodeSpecification.class.php">
            <div class="form-group">
                <label for="tableName">Table name:</label>
                <input id="tableName" type="text" name="tableName" class="form-control" placeholder="tb_usuario"   aria-describedby="basic-addon2">
            </div>
            <button type="submit" class="btn btn-default">Read</button>
        </form>
    </div>
</div>

<?php
footerContent("");
?>
