<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Ankietour - Manage products</title>
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
$newName = $newDescription = $newImageUrl = "";
$newTypeId = $newRate = 0;
$editName = $editDescription = $editImageUrl = "";
$editTypeId = $editRate = 0;
$errMsg = $errMsg2 = $errMsg3 = "";
$selectedProduct = $selectedProductType = $selectProductTypeForProduct = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["removeProduct"])) {
        if (!empty($_POST['check_list_remove'])) {
            $ids = $_POST['check_list_remove'];
            deleteProducts($ids);
            echo "<script> alertify.alert(\"Selected products were removed.\")</script>";
        }
    } else if (isset($_POST["addProduct"])) {
        $newName = $_POST["newName"];
        $newTypeId = $_POST["newTypeId"];
        $newDescription = $_POST["newDescription"];
        if (isset($_FILES["newImageUrl"])){
            $newImageUrl = uploadImage("newImageUrl");
        }
        if (addProduct($newName, $newTypeId, $newDescription, $newRate, $newImageUrl)) {
            echo "<script> alertify.alert(\"New product was added.\")</script>";
        } else {
            echo "<script> alertify.alert(\"New product was not added.\")</script>";
        }
    } else if (isset($_POST["editProduct"])) {
        $selectProductTypeForProduct = $_POST["selectProductTypeForProduct"];
        $selectedProduct = $_POST['selectProduct'];
        reloadEditProductFields($selectedProduct);
        $editName = $_POST["editName"];
        $editTypeId = $_POST["editTypeId"];
        $editDescription = $_POST["editDescription"];
        if (isset($_FILES["editImageUrl"])) {
            $editImageUrl = uploadImage("editImageUrl");
        }
        if (updateProduct($selectedProduct, $editTypeId, $editName, $editDescription, $editRate, $editImageUrl)) {
            echo "<script> alertify.alert(\"Product data was edited and saved.\")</script>";
        } else {
            echo "<script> alertify.alert(\"Product data was not edited.\")</script>";
        }
    }  else if (isset($_POST["selectProduct"])) {
        if (isset($_POST["selectProductTypeForProduct"])){
            $selectProductTypeForProduct = $_POST["selectProductTypeForProduct"];
        }
        $selectedProduct = $_POST['selectProduct'];
        reloadEditProductFields($selectedProduct);
    } else if (isset($_POST["selectProductType"])) {
        $selectedProductType = $_POST['selectProductType'];
        reloadEditProductFields($selectedProduct);
    } else if (isset($_POST["selectProductTypeForProduct"])){
        $selectProductTypeForProduct = $_POST["selectProductTypeForProduct"];
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

    <h2>Manage products</h2>
</div>
<div class="content">
<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    ?>


    <div class="pure-menu pure-menu-open pure-menu-horizontal">
        <ul>
            <li><a href="#"><h3 onclick="showAndHideDiv('addProductDiv', 'allProductsDiv', 'manageSelectedProductDiv')"
                                onmouseover="" style="cursor: pointer;">Add product</h3></a></li>

            <li><a href="#"><h3 onclick="showAndHideDiv('allProductsDiv', 'addProductDiv', 'manageSelectedProductDiv')"
                                onmouseover="" style="cursor: pointer;">All products</h3></a></li>

            <li><a href="#"><h3 onclick="showAndHideDiv('manageSelectedProductDiv', 'allProductsDiv', 'addProductDiv')"
                                onmouseover="" style="cursor: pointer;">Manage selected
                        product</h3></a></li>
        </ul>
    </div>
    <!-- ------------ Add product -------------------------------------------------------------------->
    <div id="addProductDiv">
        <form class="pure-form pure-form-stacked" name="addProductForm"  enctype="multipart/form-data" onsubmit="return checkProductFields()"
              method="post"
              action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <table>
                <tr>
                    <td>Name: <input class="full_width" type="text" id="newName" name="newName"
                                     value="<?php echo $newName;?>" required/></td>
                </tr>
                <tr>
                    <td>Type:
                        <select class="full_width" id="newTypeId" name="newTypeId">
                            <?php
                            $ret = selectProductsTypes();
                            if ($ret != false) {
                                echo "<option value=0></option>";
                                while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                                    $rowValue = $row["name"];
                                    $rowId = $row["id"];
                                    if ($rowId == $selectedProduct) {
                                        echo "<option value=\"$rowId\" selected=\"selected\">$rowValue</option>";
                                    } else {
                                        echo "<option value=\"$rowId\">$rowValue</option>";
                                    }
                                }
                            }
                            ?>
                        </select></td>
                </tr>
                <tr>
                    <td>Description: <textarea class="full_width" name="newDescription"><?php echo $newDescription; ?></textarea></td>
                </tr>
                <tr>
                    <td class="pure-u-1-2">
                        <img class="pure-img" id="imagePreview" src="images/no_photo.jpg" alt="Image" />
                    </td>
                </tr>
                <tr>
                    <td>Image:   <input name="newImageUrl" type="file" onchange="readURL(this);" />
                        </td>
                </tr>
            </table>
            <p>
                <input class="button-success pure-button" type="submit" name="addProduct" value="Add product"
                       />
                <input class="pure-button" type="button" name="cancelButton" value="Cancel" />
            </p>

            <p id="errMsg"></p>
        </form>
    </div>
    <span class="error"><?php global $errMsg;
        echo $errMsg ?></span>

    <!-- ------------ All products -------------------------------------------------------------------->

    <div id="allProductsDiv">
        <form class="pure-form pure-form-stacked" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
              method="post">
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
            <?php
            $ret = selectProductsByProductType($selectedProductType);
            if ($ret != false) {
                echo "<table class=\"pure-table\">";
                while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                    echo "<tr>";
                    $rowName = $row["name"];
                    $rowId = $row["id"];
                    echo "<td>$rowName</td>";
                    echo "<td><input type=\"checkbox\" name=\"check_list_remove[]\" value=\"$rowId\"></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>
            <p><input class="button-success pure-button" type="submit" name="removeProduct"
                      value="Remove selected products" /></p>

            <p>
                        <span class="error"><?php global $errMsg3;
                            echo $errMsg3 ?></span>
            </p>
        </form>
    </div>

    <!-- ------------ Manage selected product -------------------------------------------------------------------->

    <div id="manageSelectedProductDiv">
        <form class="pure-form pure-form-stacked" id="selectProductForm" enctype="multipart/form-data" name="selectPersonForm"
              onsubmit="return checkProductFields()" method="post"
              action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            Choose product type:
            <select id="selectProductTypeForProduct" name="selectProductTypeForProduct" onChange="this.form.submit()">
                <?php
                $ret = selectProductsTypes();
                if ($ret != false) {
                    echo "<option value=0></option>";
                    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                        $rowValue = $row["name"];
                        $rowId = $row["id"];
                        if ($rowId == $selectProductTypeForProduct) {
                            echo "<option value=\"$rowId\" selected=\"selected\">$rowValue</option>";
                        } else {
                            echo "<option value=\"$rowId\">$rowValue</option>";
                        }
                    }
                }
                ?>
            </select>
            Choose product:
            <select id="selectProduct" name="selectProduct" onchange="this.form.submit()">
                <?php
                $ret = selectProductsByProductType($selectProductTypeForProduct);
                if ($ret != false) {
                    echo "<option value=0></option>";
                    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                        $rowValue = $row["name"];
                        $rowId = $row["id"];
                        if ($rowId == $selectedProduct) {
                            echo "<option value=\"$rowId\" selected=\"selected\">$rowValue</option>";
                        } else {
                            echo "<option value=\"$rowId\">$rowValue</option>";
                        }
                    }
                }
                ?>
            </select>

            <?php
            if(selectIfProductIsProductType($selectedProduct, $selectProductTypeForProduct)){
            ?>

            <h4>Edit product</h4>
            <table>
                <tr>
                    <td>Name: <input type="text" id="editName" name="editName"
                                     value="<?php echo $editName; ?>" required/></td>
                </tr>
                <tr>
                    <td>Type:
                        <select id="editTypeId" name="editTypeId">
                            <?php
                            $ret = selectProductsTypes();
                            if ($ret != false) {
                                echo "<option value=0></option>";
                                while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                                    $rowValue = $row["name"];
                                    $rowId = $row["id"];
                                    if ($rowId == $editTypeId) {
                                        echo "<option value=\"$rowId\" selected=\"selected\">$rowValue</option>";
                                    } else {
                                        echo "<option value=\"$rowId\">$rowValue</option>";
                                    }
                                }
                            }
                            ?>
                        </select></td>
                </tr>
                <tr>
                    <td>Description: <textarea name="editDescription"><?php echo $editDescription; ?></textarea></td>
                </tr>
                <tr>
                <tr>
                    <td class="pure-u-1-2">
                        <?php
                        if (strpos($editImageUrl, '.') !== FALSE){
                            echo "<img class=\"pure-img\" id=\"imagePreview\" src=\"$editImageUrl\" alt=\"Image\" />";
                        } else {
                            echo "<img class=\"pure-img\" id=\"imagePreview\" src=\"images/no_photo.jpg\" alt=\"Image\" />";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Image:   <input name="editImageUrl" type="file" onchange="readURL(this);" /></td>
                </tr>
                </tr>
            </table>
            <p>
                <input class="button-success pure-button" type="submit" name="editProduct" value="Edit product"
                       />
                <input class="pure-button" type="hidden" id="selectedProductId" name="selectedProductId"/>
                <input class="pure-button" type="button" name="cancelButton" value="Cancel" />
            </p>

            <p class="error" id="errMsg2"></p>
            <span class="error"><?php global $errMsg2;
                echo $errMsg2 ?></span>

            <?php
            }
            ?>


        </form>
    </div>
    <?php
    echo "<script>hideAll('manageSelectedProductDiv', 'allProductsDiv', 'addProductDiv')</script>";
    if (isset($_POST["removeProduct"])) {
        echo "<script>reloadShowAndHideDiv('allProductsDiv', 'addProductDiv','manageSelectedProductDiv')</script>";
    } else if (isset($_POST["addProduct"])) {
        echo "<script>reloadShowAndHideDiv('addProductDiv', 'allProductsDiv','addPersonDiv')</script>";
    } else if (isset($_POST["editProduct"])) {
        echo "<script>reloadShowAndHideDiv('manageSelectedProductDiv', 'addProductDiv','allProductsDiv')</script>";
    } else if (isset($_POST["selectProduct"])) {
        echo "<script>reloadShowAndHideDiv('manageSelectedProductDiv', 'addProductDiv','allProductsDiv')</script>";
    } else if (isset($_POST["selectProductType"])) {
        echo "<script>reloadShowAndHideDiv('allProductsDiv', 'addProductDiv','manageSelectedProductDiv')</script>";
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
    var e = document.getElementById("selectProduct").value;
    document.getElementById("selectedProductId").value = e;
</script>
<script src="js/ui.js"></script>
</body>
</html>