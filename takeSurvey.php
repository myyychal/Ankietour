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
$byEmail = false;
$showQuestionnaires = true;
$offset = 0;
$limit = 1;
$page = 0;
$firstId = 0;
$secondId = 0;
$productTypeName = "";
$chosenQuestionnaireId = 0;
if (isset($_SESSION["username"]) && $_SESSION["username"] != "guest"){
    if (selectCountOfQuestionnairesForUser(selectUserId($_SESSION["username"])) <= 0){
        $showQuestionnaires = false;
    }
}
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["questionnaireId"])) {
    $showQuestionnaires = false;
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = "guest";

    $chosenQuestionnaireIdHashed = $_GET["questionnaireId"];
    $ret = selectQuestionnaires();
    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            if ($chosenQuestionnaireIdHashed == md5($row["id"] . "BOMBA")) {
                $chosenQuestionnaireId = $row["id"];
            }
        }
    }
    if ($chosenQuestionnaireId == 0){
        echo "<script>alertify.alert(\"This survey does not exist anymore.\")</script>";
    } else {
        $productTypeName = selectProductTypeNameFromQuestionnaire($chosenQuestionnaireId);
        if (selectIfQuestionnaireIsCompleted($chosenQuestionnaireId)){
            echo "<script>alertify.alert(\"This survey was already taken.\")</script>";
        } else {
            $byEmail = true;
            $description = selectDescriptionFromQuestionnaire($chosenQuestionnaireId);
            echo "<script>alertify.alert(\"$description\")</script>";
        }
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $showQuestionnaires = false;
    if (isset($_POST["takeQuestionnaire"])){
        if (isset($_POST["radio_list"])){
            $questionnairesIds = $_POST["radio_list"];
            foreach ($questionnairesIds as $qId) {
                $productTypeName = selectProductTypeNameFromQuestionnaire($qId);
                $chosenQuestionnaireId = $qId;
            }
            $description = selectDescriptionFromQuestionnaire($chosenQuestionnaireId);
            echo "<script>alertify.alert(\"$description\")</script>";
        }
    }
    if (isset($_POST["nextComparison"]) || isset($_POST["prevComparison"])) {
        $productTypeName = $_POST["productTypeName"];
        if (isset($_POST["nextComparison"])){
            $page = $_POST["page"] + 1;
        } else {
            $page = $_POST["page"] - 1;
        }
        $offset = $limit * $page;
        $firstProductId = $_POST["firstProductId"];
        $secondProductId = $_POST["secondProductId"];
        $chosenQuestionnaireId = $_POST["questionnaireId"];
        if (isset($_POST["rate"])){
            $rate = $_POST["rate"]/50;
            updateComparison($firstProductId, $secondProductId, $chosenQuestionnaireId, $rate);
            updateComparison($secondProductId, $firstProductId, $chosenQuestionnaireId, 2-$rate);
        }
    }
    if (isset($_POST["finishSurvey"])){
        $chosenQuestionnaireId = $_POST["questionnaireId"];
        closeQuestionnaire($chosenQuestionnaireId);
        echo "<script> alertify.alert(\"Survey is now closed and results are saved in database.\")</script>";
        $url = "index.php";
        header("refresh:2; url=$url");
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

            <h2>Take survey</h2>
        </div>
        <div class="content">
            <?php
            if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
                loginFirstMsg();
            } else if ($showQuestionnaires) {
                ?>
                <div id="myQuestionnaiers">
                    <h3>My questionnaires</h3>

                    <form class="pure-form pure-form-stacked"
                          action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                          method="post">
                        <?php
                        $rowProductType = "";
                        $ret = selectQuestionnaireByUser(selectUserId($_SESSION["username"]));
                        if ($ret != false) {
                            echo "<table class=\"pure-table\">";
                            while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                                echo "<tr>";
                                $rowName = $row["name"];
                                $rowProductType = selectProductTypeName($row["typeid"]);
                                $rowId = $row["id"];
                                $rowCompleted = $row["completed"];
                                echo "<td>$rowName</td>";
                                echo "<td>$rowProductType</td>";
                                if ($rowCompleted == 0) {
                                    echo "<td><input type=\"radio\" name=\"radio_list[]\" value=\"$rowId\"></td>";
                                } else {
                                    echo "<td></td>";
                                }
                                echo "</tr>";
                            }
                            echo "</table>";
                        }
                        ?>
                        <p><input class="button-success pure-button" type="submit" name="takeQuestionnaire"
                                  value="Complete the selected survey" />

                        <p>
                        <span class="error"><?php global $errMsg3;
                            echo $errMsg3 ?></span>
                        </p>
                    </form>

                </div>

            <?php
            } else if ($_SERVER["REQUEST_METHOD"] == "POST" || $_SERVER["REQUEST_METHOD"] == "GET") {
                if (isset($_POST["takeQuestionnaire"]) || isset($_POST["nextComparison"]) || isset($_POST["prevComparison"]) || $byEmail) {
                    ?>
                    <form class="pure-form pure-form-stacked"
                          action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                          method="POST" name="comparison" id="comparison">

                        <?php
                        $rec_count = selectCountOfQuestionsForQuestionnaire($chosenQuestionnaireId, $productTypeName);
                        $left_rec = $rec_count - ($page * $limit);
                        $ret = selectProductsForQuestionnaireWithOffsetAndResults($chosenQuestionnaireId,$productTypeName, $offset, $limit);
                        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                            $firstId = $row["firstid"];
                            $firstName = $row["firstname"];
                            $firstDescription = $row["firstdescription"];
                            $firstImageUrl = $row["firstimageurl"];
                            $secondId = $row["secondid"];
                            $secondName = $row["secondname"];
                            $secondDescription = $row["seconddescription"];
                            $secondImageUrl = $row["secondimageurl"];
                            echo "<div class=\"centeroid pure-u-1-3\">";
                            echo "<p>";
                            echo "$firstName<br>";
                            echo "$firstDescription<br>";
                            showImage($firstImageUrl);
                            echo "</p>";
                            echo "</div>";
                            echo "<div class=\"pure-u-1-3 centeroid\">";
                            ?>
                            <input name="rate" class="slider" type=range min=0 max=100 value=50 step=1/>
                            <?php
                            echo "</div>";
                            echo "<div class=\"centeroid pure-u-1-3\">";
                            echo "<p>";
                            echo "$secondName<br>";
                            echo "$secondDescription<br>";
                            showImage($secondImageUrl);
                            echo "</p>";
                            echo "</div>";
                        }
                        if ($page > 0 && $page < $rec_count) {
                            echo "<input class=\"pure-button\" type=\"submit\" name=\"prevComparison\" value=\"Previous\"/>";
                            echo "<input class=\"pure-button\" type=\"submit\" name=\"nextComparison\" value=\"Next\"/>";
                            echo "<input class=\"pure-button\" type=\"submit\" name=\"finishSurvey\" value=\"Finish survey\"/>";
                        } else if ($page == 0) {
                            echo "<input class=\"pure-button\" type=\"submit\" name=\"nextComparison\" value=\"Next\"/>";
                            echo "<input class=\"pure-button\" type=\"submit\" name=\"finishSurvey\" value=\"Finish survey\"/>";
                        } else if ($page >= $rec_count) {
                            echo "Thank you for completing the survey. You can go back to change your ratings or finish the survey.<br>";
                            echo "<input class=\"pure-button\" type=\"submit\" name=\"prevComparison\" value=\"Previous\"/>";
                            echo "<input class=\"pure-button\" type=\"submit\" name=\"finishSurvey\" value=\"Finish survey\"/>";
                        }
                        ?>
                        <input type="hidden" name="page" value=<?php echo $page; ?>>
                        <input type="hidden" name="productTypeName" value=<?php echo $productTypeName; ?>>
                        <input type="hidden" name="questionnaireId" value=<?php echo $chosenQuestionnaireId; ?>>
                        <input type="hidden" name="firstProductId" value=<?php echo $firstId;?>>
                        <input type="hidden" name="secondProductId" value=<?php echo $secondId;?>>
                    </form>
                <?php
                }
            }
            ?>

        </div>
    </div>
</div>
<script src="js/ui.js"></script>
</body>
</html>