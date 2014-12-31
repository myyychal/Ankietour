<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Ankietour - Manage questionnaires</title>
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
include 'php_libs/questionnaireInfoGeneration.php';
session_start();
?>
<?php
$newName = $newDescription = $newEmail = "";
$newTypeId = $newPersonId = 0;
$editName = $editDesciption = "";
$editTypeId = $newPersonId = 0;
$errMsg = $errMsg2 = $errMsg3 = "";
$selectedQuestionnaire = $selectedUser = $selectedProductType = $selectProductTypeForQuestionnaire = 0;
$selectedUsers = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["removeQuestionnaire"])) {
        if (!empty($_POST['check_list_remove'])) {
            $ids = $_POST['check_list_remove'];
            deleteQuestionnaires($ids);
            echo "<script> alertify.alert(\"Selected questionnaires were removed.\")</script>";
        }
    } else if (isset($_POST["addQuestionnaire"])) {
        $newName = $_POST["newName"];
        $newDescription = $_POST["newDescription"];
        $newTypeId = $_POST["newTypeId"];
        $newMail = $_POST["newEmail"];
        $i = 0;
        if (isset( $_POST["newPersonId"])){
            $newPersonIds = $_POST["newPersonId"];
            foreach($newPersonIds as $newPersonId){
                $insertedId = addQuestionnaire($newName . "#$i", $newDescription, $newPersonId, $newTypeId);
                if ($insertedId >= 0) {
                    if (!empty($_POST['check_list_remove'])) {
                        $productsIds = $_POST['check_list_remove'];
                        addExcludedProducts($productsIds, $insertedId);
                    }
                }
                $i++;
            }
            echo "<script> alertify.alert(\"New questionnaires were added and assigned to selected users.\")</script>";
        }
        if (!empty($newMail)) {
            $p_emails = explode(",", $newMail);
            foreach ($p_emails as $email) {
                if (strpos($email, '@')) {
                    $insertedId = addQuestionnaire($newName . "#$i", $newDescription, 0, $newTypeId);
                    if ($insertedId >= 0) {
                        if (!empty($_POST['check_list_remove'])) {
                            $productsIds = $_POST['check_list_remove'];
                            addExcludedProducts($productsIds, $insertedId);
                        }
                        sendMailWithQuestionnaire($newMail, $insertedId);
                    }
                    $i++;
                }
            }
            echo "<script> alertify.alert(\"New questionnaires were added and emails were sent to $newMail.\")</script>";
        }
    } else if (isset($_POST["newTypeId"])) {
        $selectedProductType = $_POST["newTypeId"];
        $newName = $_POST["newName"];
        $newDescription = $_POST["newDescription"];
        $newEmail = $_POST["newEmail"];
        if (isset($_POST["newPersonId"])){
            $selectedUsers = $_POST["newPersonId"];
        }
    } else if (isset($_POST["selectQuestionnaire"])) {
        if (isset($_POST["selectProductType"])){
            $selectProductTypeForQuestionnaire = $_POST["selectProductType"];
        }
        $selectedQuestionnaire = $_POST['selectQuestionnaire'];
    } else if (isset($_POST["selectProductType"])) {
        $selectProductTypeForQuestionnaire = $_POST['selectProductType'];
    } else if (isset($_POST["selectPerson"])) {
        $selectedUser = $_POST['selectPerson'];
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

    <h2>Manage questionnaires</h2>
</div>
<div class="content">
<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    ?>


    <div class="pure-menu pure-menu-open pure-menu-horizontal">
        <ul>
            <li><a href="#"><h3
                        onclick="showAndHideDiv('addQuestionnaireDiv', 'allQuestionnairesDiv', 'manageSelectedQuestionnaireDiv')"
                        onmouseover="" style="cursor: pointer;">Add questionnaire</h3></a></li>

            <li><a href="#"><h3
                        onclick="showAndHideDiv('allQuestionnairesDiv', 'addQuestionnaireDiv', 'manageSelectedQuestionnaireDiv')"
                        onmouseover="" style="cursor: pointer;">All questionnaires</h3></a></li>

            <li><a href="#"><h3
                        onclick="showAndHideDiv('manageSelectedQuestionnaireDiv', 'allQuestionnairesDiv', 'addQuestionnaireDiv')"
                        onmouseover="" style="cursor: pointer;">Manage selected
                        questionnaire</h3></a></li>
        </ul>
    </div>
    <!-- ------------ Add questionnaire -------------------------------------------------------------------->
    <div id="addQuestionnaireDiv">
        <form class="pure-form pure-form-stacked" name="addQuestionnaireForm" enctype="multipart/form-data"
              onsubmit="return checkQuestionnaireFields()"
              method="post"
              action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="pure-u-1-3">
            Name: <input class="full_width" type="text" id="newName" name="newName"
                                               value="<?php echo $newName; ?>" required/>
            </div>
            <div class="pure-u-2-3"></div>
            <div class="pure-u-1-3">
            Description: <textarea class="full_width" name="newDescription"><?php echo $newDescription; ?></textarea>
                </div>
            <div class="pure-u-2-3"></div>
            <div class="pure-u-3-3">
            Choose person or put an email:
            </div>
            <div class="pure-u-1-3">
            Person:
                <select class="full_width" id="newPersonId" name="newPersonId[]" multiple="multiple">
                    <?php
                    $ret = selectUsersWithPrivilege(3);
                    if ($ret != false) {
                        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                            $rowValue = $row["login"];
                            $rowId = $row["id"];
                            if (in_array($rowId,$selectedUsers)) {
                                echo "<option value=\"$rowId\" selected=\"selected\">$rowValue</option>";
                            } else {
                                echo "<option value=\"$rowId\">$rowValue</option>";
                            }
                        }
                    }
                    ?>
                </select>

                </div>
            <div class="pure-u-1-3">
            Email:<textarea class="full_width" name="newEmail"><?php echo $newEmail; ?></textarea>
            <p id="errPersonId"></p>
            </div>
            <div class="pure-u-1-3"></div>
            <div class="pure-u-1-3">
            Product type:
                <select class="full_width" id="newTypeId" name="newTypeId" onchange="this.form.submit()">
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

            <p id="errTypeId"></p>
                </div>
            <div class="pure-u-2-3"></div>
            <?php
            if ($selectedProductType != 0) {
                ?>
                <div class="pure-u-2-3">Select products to exclude from questionnaire:
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
                    echo "</div>";
                }
                ?>
            <?php
            }
            ?>
            <p>
                <input class="button-success pure-button" type="submit" name="addQuestionnaire"
                       value="Add questionnaire"
                    />
                <input class="pure-button" type="button" name="cancelButton" value="Cancel" />
            </p>

            <p id="errMsg"></p>
        </form>
    </div>
    <span class="error"><?php global $errMsg;
        echo $errMsg ?></span>

    <!-- ------------ All questionnaires -------------------------------------------------------------------->

    <div id="allQuestionnairesDiv">
        <form class="pure-form pure-form-stacked" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
              method="post">
            <?php
            $ret = selectQuestionnaires();
            if ($ret != false) {
                echo "<table class=\"pure-table\">";
                while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                    echo "<tr>";
                    $rowName = $row["name"];
                    $rowProductType = selectProductTypeName($row["typeid"]);
                    $rowUser = selectUserLogin($row["personid"]);
                    $rowId = $row["id"];
                    echo "<td>$rowName</td>";
                    echo "<td>$rowProductType</td>";
                    echo "<td>$rowUser</td>";
                    echo "<td><input type=\"checkbox\" name=\"check_list_remove[]\" value=\"$rowId\"></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>
            <p><input class="button-success pure-button" type="submit" name="removeQuestionnaire"
                      value="Remove selected questionnaires" /></p>

            <p>
                        <span class="error"><?php global $errMsg3;
                            echo $errMsg3 ?></span>
            </p>
        </form>
    </div>

    <!-- ------------ Manage selected questionnaire -------------------------------------------------------------------->

    <div id="manageSelectedQuestionnaireDiv">
        <form class="pure-form pure-form-stacked" id="selectQuestionnaireForm" enctype="multipart/form-data"
              name="selectPersonForm"
              onsubmit="return checkQuestionnaireFields()" method="post"
              action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="pure-u-1-3">
            Choose product type:
            <select class="full_width" id="selectProductType" name="selectProductType" onChange="this.form.submit()">
                <?php
                $ret = selectProductsTypes();
                if ($ret != false) {
                    echo "<option value=0></option>";
                    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                        $rowValue = $row["name"];
                        $rowId = $row["id"];
                        if ($rowId == $selectProductTypeForQuestionnaire) {
                            echo "<option value=\"$rowId\" selected=\"selected\">$rowValue</option>";
                        } else {
                            echo "<option value=\"$rowId\">$rowValue</option>";
                        }
                    }
                }
                ?>
            </select>
            </div>
            <div class="pure-u-2-3">
            </div>
            <div class="pure-u-1-3">
            Choose questionnaire:
            <select class="full_width" id="selectQuestionnaire" name="selectQuestionnaire" onchange="this.form.submit()">
                <?php
                $ret = selectQuestionnaireByProductType($selectProductTypeForQuestionnaire);
                if ($ret != false) {
                    echo "<option value=0></option>";
                    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                        $rowValue = $row["name"];
                        $rowId = $row["id"];
                        if ($rowId == $selectedQuestionnaire) {
                            echo "<option value=\"$rowId\" selected=\"selected\">$rowValue</option>";
                        } else {
                            echo "<option value=\"$rowId\">$rowValue</option>";
                        }
                    }
                }
                ?>
            </select>
            </div>
            <?php
            if ($selectedQuestionnaire != 0 && $selectProductTypeForQuestionnaire != 0){
                if (selectProductTypeNameFromQuestionnaire($selectedQuestionnaire) == selectProductTypeName($selectProductTypeForQuestionnaire)){
                    generateMatrix($selectedQuestionnaire);
                    generateInfo($selectedQuestionnaire);
                }
            }
            ?>

            <p class="error" id="errMsg2"></p>
            <span class="error"><?php global $errMsg2;
                echo $errMsg2 ?></span>

        </form>
    </div>
    <?php
    echo "<script>hideAll('manageSelectedQuestionnaireDiv', 'allQuestionnairesDiv', 'addQuestionnaireDiv')</script>";
    if (isset($_POST["removeQuestionnaire"])) {
        echo "<script>reloadShowAndHideDiv('allQuestionnairesDiv', 'addQuestionnaireDiv','manageSelectedQuestionnaireDiv')</script>";
    } else if (isset($_POST["addQuestionnaire"])) {
        echo "<script>reloadShowAndHideDiv('addQuestionnaireDiv', 'allQuestionnairesDiv','addPersonDiv')</script>";
    } else if (isset($_POST["editQuestionnaire"])) {
        echo "<script>reloadShowAndHideDiv('manageSelectedQuestionnaireDiv', 'addQuestionnaireDiv','allQuestionnairesDiv')</script>";
    } else if (isset($_POST["selectQuestionnaire"])) {
        echo "<script>reloadShowAndHideDiv('manageSelectedQuestionnaireDiv', 'addQuestionnaireDiv','allQuestionnairesDiv')</script>";
    } else if (isset($_POST["selectProductType"])) {
        echo "<script>reloadShowAndHideDiv('manageSelectedQuestionnaireDiv', 'addQuestionnaireDiv','allQuestionnairesDiv')</script>";
    } else if (isset($_POST["newTypeId"])) {
        echo "<script>reloadShowAndHideDiv('addQuestionnaireDiv', 'allQuestionnairesDiv','addPersonDiv')</script>";
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
    var e = document.getElementById("selectQuestionnaire").value;
    document.getElementById("selectedQuestionnaireId").value = e;
</script>
<script src="js/ui.js"></script>
</body>
</html>