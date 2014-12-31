<?php

function addUser($username, $password, $privilege)
{
    global $errMsg;

    $cost = 10;
    $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
    $salt = sprintf("$2a$%02d$", $cost) . $salt;
    $hash = crypt($password, $salt);

    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT id,login,password FROM users WHERE login = \"$username\"";

    $ret = $db->query($sql);
    if ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $errMsg = "There is already a user with this login.";
        return false;
    }

    $sql = "INSERT INTO users VALUES (NULL, \"$username\", \"$hash\", $privilege)";

    $ret = $db->exec($sql);
    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function addProductType($newName, $newDescription)
{
    global $errMsg;

    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM productstypes WHERE name = \"$newName\"";

    $ret = $db->query($sql);
    if ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $errMsg = "There is already a product type with this name.";
        return false;
    }

    $sql = "INSERT INTO productstypes VALUES (NULL, \"$newName\", \"$newDescription\")";

    $ret = $db->exec($sql);
    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function addProduct($newName, $newTypeId, $newDescription, $newRate, $newImageUrl)
{
    global $errMsg;

    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM products WHERE name = \"$newName\" AND typeid=$newTypeId";

    $ret = $db->query($sql);
    if ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $errMsg = "There is already a product with this name.";
        return false;
    }

    $sql = "INSERT INTO products VALUES (NULL, $newTypeId, \"$newName\", \"$newDescription\", $newRate, \"$newImageUrl\")";

    $ret = $db->exec($sql);
    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function addExcludedProducts($productsId, $questionnaireId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    foreach ($productsId as $id){
        $sql = "INSERT INTO excludedproducts VALUES ($id, $questionnaireId)";
        $ret = $db->exec($sql);
    }

    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function addQuestionnaire($name, $description, $personId, $typeId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "INSERT INTO questionnaires VALUES (NULL, \"$name\", \"$description\", $personId, $typeId, 0)";

    $ret = $db->exec($sql);

    $id = $db->lastInsertRowID();
    if ($ret > 0) {
        $db->close();
        return $id;
    } else {
        $db->close();
        return -1;
    }
}

function addComparison($firstProdId, $secondProdId, $questionaireId, $rate)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "INSERT INTO comparisons VALUES ($firstProdId, $secondProdId, $questionaireId, $rate)";

    $ret = $db->exec($sql);
    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

?>