<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Ankietour</title>
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
<?php
include 'php_libs/updateFunctions.php';
include 'php_libs/utils.php';
session_start();
?>
<body>
<?php
$productId = 0;
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $productId = $_GET["id"];
}
?>
<div id="layout">
    <div id="main">
        <div class="header">
            <h1><?php echo selectProductNameById($productId) ?></h1>
        </div>
        <div class="content">
            <p><?php showImage(selectProductImageUrlById($productId)) ?></p>
            <p><?php selectProductDescriptionById($productId)?></p>
        </div>
    </div>
</div>
<script src="js/ui.js"></script>
</body>
</html>