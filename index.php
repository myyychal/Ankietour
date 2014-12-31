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
include 'php_libs/utils.php';
session_start();
?>
<body>
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
        </div>
        <div class="content">
            <h3>Hello,</h3>
            <p>this is service dedicated to managing surveys. System allows users to define:</p>
                <ul>
                <li>product types,</li>
                    <li>products,</li>
                    <li>questionnaires.</li>
                </ul>
        </div>
    </div>
</div>
<script src="js/ui.js"></script>
</body>
</html>