<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Ankietour</title>
    <script src="js/utils.js"></script>
    <script src="alertify/lib/alertify.min.js"></script>
    <link rel="stylesheet" href="css/pure-min.css">
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="css/layouts/side-menu-old-ie.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
    <link rel="stylesheet" href="css/layouts/side-menu.css">
    <link rel="stylesheet" href="alertify/themes/alertify.core.css"/>
    <link rel="stylesheet" href="alertify/themes/alertify.default.css"/>
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
$isAllQuestionnaires = true;
$selectProductTypeForQuestionnaire = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["selectProductType"])) {
        $selectProductTypeForQuestionnaire = $_POST['selectProductType'];
        if (isset($_POST["allQuestionnaires"])){
            $isAllQuestionnaires = true;
        } else {
            $isAllQuestionnaires = false;
        }
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

            <h2>Browse results</h2>
        </div>
        <div class="content">
            <?php
            if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
                loginFirstMsg();
            }
            ?>
            <form class="pure-form pure-form-stacked" id="selectQuestionnaireForm" enctype="multipart/form-data"
                  name="selectPersonForm"
                  onsubmit="return checkQuestionnaireFields()" method="post"
                  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="pure-u-1-3">
                    <p>Choose product type:</p>
                    <select class="full_width" id="selectProductType" name="selectProductType"
                            onChange="this.form.submit()">
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
                <div class="pure-u-2-3"></div>
                <div class="pure-u-2-3">
                    <?php if ($isAllQuestionnaires) { ?>
                        <input type="checkbox" name="allQuestionnaires" checked
                               onchange="this.form.submit()">All questionnaires (also with empty comparisons)<br>
                    <?php } else { ?>
                        <input type="checkbox" name="allQuestionnaires"
                               onchange="this.form.submit()">All questionnaires (also with empty comparisons)<br>
                    <?php } ?>
                </div>
            </form>
            <?php
            if ($selectProductTypeForQuestionnaire != 0) {
                generateOverallMatrix($selectProductTypeForQuestionnaire, $isAllQuestionnaires);
                generateOverallInfo($selectProductTypeForQuestionnaire, $isAllQuestionnaires);
            }
            ?>
        </div>
    </div>
</div>
</div>
<script src="js/ui.js"></script>
</body>
</html>