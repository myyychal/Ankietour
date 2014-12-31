<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Ankietour - Manage product types</title>
    <script src="js/checkFields.js"></script>
    <script src="js/utils.js"></script>
    <script src="alertify/lib/alertify.min.js"></script>
    <link rel="stylesheet" href="css/pure-min.css">
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="css/layouts/side-menu-old-ie.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
    <link rel="stylesheet" href="css/layouts/side-menu.css">
    <link rel="stylesheet" href="alertify/themes/alertify.core.css" />
    <link rel="stylesheet" href="alertify/themes/alertify.default.css" />
    <!--<![endif]-->
</head>
<body>
<?php
include 'php_libs/updateFunctions.php';
include 'php_libs/deleteFunctions.php';
include 'php_libs/utils.php';
session_start();
?>
<?php
$newName = $newDescription = "";
$editName = $editDescription = "";
$errMsg = $errMsg2 = $errMsg3 = "";
$selectedProductType = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["addProductType"])) {
        $newName = $_POST["newName"];
        $newDescription = $_POST["newDescription"];
        if (addProductType($newName, $newDescription)) {
            echo "<script> alertify.alert(\"New product type was added.\")</script>";
        } else {
            echo "<script> alertify.alert(\"New product type was not added.\")</script>";
        }
    } else if (isset($_POST["editProductType"])) {
        $selectedProductType = $_POST['selectProductType'];
        reloadEditProductTypeFields($selectedProductType);
        $editName = $_POST["editName"];
        $editDescription = $_POST["editDescription"];
        if (updateProductType($selectedProductType, $editName, $editDescription)) {
            echo "<script> alertify.alert(\"Product type data was edited and saved.\")</script>";
        } else {
            echo "<script> alertify.alert(\"Product type data was not edited.\")</script>";
        }
    } else if (isset($_POST["removeProductType"])) {
        if (!empty($_POST['check_list_remove'])) {
            $ids = $_POST['check_list_remove'];
            deleteProductsTypes($ids);
            echo "<script> alertify.alert(\"Selected products types were removed.\")</script>";
        }
    } else if (isset($_POST["selectProductType"])) {
        $selectedProductType = $_POST['selectProductType'];
        reloadEditProductTypeFields($selectedProductType);
    }
}
?>
<div id="layout">
<a href="#menu" id="menuLink" class="menu-link">
    <!-- Hamburger icon -->
    <span></span>
</a>

    <?php generateMenu(); ?>
<div id="main">
<div class="header">
    <h1>Ankietour</h1>
    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        echo "Hello, " . $_SESSION['username'];
    }
    ?>

    <h2>Manage product types</h2>
</div>
<div class="content">
    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        ?>
        <div class="pure-menu pure-menu-open pure-menu-horizontal">
            <ul>
                <li><a href="#"><h3 onclick="showAndHideDiv('addProductTypeDiv', 'allProductTypesDiv','manageSelectedProductTypeDiv')"
                                    onmouseover="" style="cursor: pointer;">Add product type</h3></a></li>
                <li><a href="#"><h3 onclick="showAndHideDiv( 'allProductTypesDiv', 'addProductTypeDiv','manageSelectedProductTypeDiv')"
                                    onmouseover="" style="cursor: pointer;">All product types</h3></a></li>
                <li><a href="#"><h3 onclick="showAndHideDiv('manageSelectedProductTypeDiv', 'addProductTypeDiv', 'allProductTypesDiv')"
                                    onmouseover="" style="cursor: pointer;">Manage selected
                            product type</h3></a></li>
            </ul>
        </div>
        <!-- ------------ Add ProductType -------------------------------------------------------------------->
        <div id="addProductTypeDiv">
            <form class="pure-form pure-form-stacked" name="addProductTypeForm" method="post"
                  onsubmit="return checkProductTypeFields('create')"
                  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <table>
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" name="newName" value="<?php echo $newName; ?>" required/></td>
                    </tr>
                    <tr>
                        <td>Description:</td>
                        <td><textarea name="newDescription"><?php echo $newDescription; ?></textarea></td>
                    </tr>
                </table>
                <p>
                    <input class="button-success pure-button" type="submit" name="addProductType" value="Add product type"/>
                    <input class="pure-button" type="button" name="cancelButton" value="Cancel"/>
                </p>

                <p class="error" id="errMsg"></p>
        </div>
        </form>

        <span class="error"><?php global $errMsg;
            echo $errMsg ?></span>

        <!-- ------------ All ProductTypes -------------------------------------------------------------------->

        <div id="allProductTypesDiv">

            <form class="pure-form pure-form-stacked" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                  method="post">
                <p>
                    <?php
                    $ret = selectProductsTypes();
                    if ($ret != false) {
                        echo "<table class=\"pure-table\">";
                        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                            echo "<tr>";
                            $rowName = $row["name"];
                            $rowDescription = $row["description"];
                            $rowId = $row["id"];
                            echo "<td>$rowName</td> <td>$rowDescription</td>";
                            echo "<td><input type=\"checkbox\" name=\"check_list_remove[]\" value=\"$rowId\"></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                    ?>

                <p><input class="button-success pure-button" type="submit" name="removeProductType"
                          value="Remove selected products types"/>

                <p>
                </p>

            <span class="error"><?php global $errMsg3;
                echo $errMsg3 ?></span>
            </form>
        </div>
        <!-- ------------ Manage selected ProductType -------------------------------------------------------------------->

        <div id="manageSelectedProductTypeDiv">
            <form class="pure-form pure-form-stacked" name="editProductTypeForm" method="post"
                  onsubmit="return checkProductTypeFields('edit')"
                  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                Choose product type:
                <select id="selectProductType" name="selectProductType" onChange="this.form.submit()">
                    <?php
                    $ret = selectProductsTypes();
                    if ($ret != false) {
                        echo "<option value=0></option>";
                        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                            $rowValue = $row["name"];
                            $rowId = $row["id"];
                            if ($rowId == $selectedProductType) {
                                echo "<option value=\"$rowId\" selected=\"selected\">$rowValue</option>";
                            } else {
                                echo "<option value=\"$rowId\">$rowValue</option>";
                            }
                        }
                    }
                    ?>
                </select>

                <h4>Edit ProductType</h4>
                <table>
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" id="editName" name="editName" value="<?php echo $editName; ?>" required/></td>
                    </tr>
                    <tr>
                        <td>Description:</td>
                        <td><textarea name="editDescription"><?php echo $editDescription; ?></textarea>
                        </td>
                    </tr>
                </table>
                <p>
                    <input class="button-success pure-button" type="submit" name="editProductType" value="Edit product type"/>
                    <input type="hidden" id="selectedProductTypeId" name="selectedProductTypeId"/>
                    <input class="pure-button" type="button" name="cancelButton" value="Cancel"/>
                </p>

                <p class="error" id="errMsg2"></p>
            <span class="error"><?php global $errMsg2;
                echo $errMsg2 ?></span>

            </form>
        </div>

        <?php
        echo "<script>hideAll('manageSelectedProductTypeDiv', 'allProductTypesDiv','addProductTypeDiv')</script>";
        if (isset($_POST["addProductType"])) {
            echo "<script>reloadShowAndHideDiv('addProductTypeDiv', 'allProductTypesDiv','manageSelectedProductTypeDiv')</script>";
        } else if (isset($_POST["editProductType"])) {
            echo "<script>reloadShowAndHideDiv('manageSelectedProductTypeDiv', 'allProductTypesDiv','addProductTypeDiv')</script>";
        } else if (isset($_POST["removeProductType"])) {
            echo "<script>reloadShowAndHideDiv('allProductTypesDiv', 'addProductTypeDiv','manageSelectedProductTypeDiv')</script>";
        } else if (isset($_POST["addProductTypesToGroup"])) {
            echo "<script>reloadShowAndHideDiv('allProductTypesDiv', 'addProductTypeDiv','manageSelectedProductTypeDiv')</script>";
        } else if (isset($_POST["unsubscribeProductType"])) {
            echo "<script>reloadShowAndHideDiv('manageSelectedProductTypeDiv', 'allProductTypesDiv','addProductTypeDiv')</script>";
        } else if (isset($_POST["selectProductType"])) {
            echo "<script>reloadShowAndHideDiv('manageSelectedProductTypeDiv', 'allProductTypesDiv','addProductTypeDiv')</script>";
        }
    } else {
        loginFirstMsg();
    }

    ?>
    <!-- ------------ Back to menu -------------------------------------------------------------------->
    <p>
        <a class="button-secondary pure-button" href="index.php">Back to menu</a>
    </p>
</div>
</div>
</div>
<script>
    var e = document.getElementById("selectProductType").value;
    document.getElementById("selectedProductTypeId").value = e;
</script>
<script src="js/ui.js"></script>
</body>
</html>