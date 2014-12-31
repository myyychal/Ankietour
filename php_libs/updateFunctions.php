<?php

include 'selectFunctions.php';
include 'insertFunctions.php';

function updateProductType($id, $editName, $editDescription)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $id = intval($id);

    $sql = "UPDATE productstypes SET name=\"$editName\", description=\"$editDescription\" WHERE id=$id";

    $ret = $db->exec($sql);
    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function updateProduct($id, $editTypeId, $editName, $editDescription, $editRate, $editImageUrl)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $id = intval($id);

    $sql = "UPDATE products SET name=\"$editName\", typeid=$editTypeId, description=\"$editDescription\", rate=$editRate, imageurl=\"$editImageUrl\" WHERE id=$id";

    $ret = $db->exec($sql);
    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function closeQuestionnaire($qId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "UPDATE questionnaires SET completed=1 WHERE id=$qId";

    $ret = $db->exec($sql);
    if ($ret > 0) {
        $db->close();
        return true;
    } else {
        $db->close();
        return false;
    }
}

function updateComparison($firstProductId, $secondProductId, $chosenQuestionnaireId, $rate){
    if (selectComparisonsCountByPK($firstProductId,$secondProductId,$chosenQuestionnaireId) != 0){

        $db = new SQLite3("db/db.sqlite3");
        if (!$db) {
            echo $db->lastErrorMsg();
            return false;
        }

        $sql = "UPDATE comparisons SET rate=$rate WHERE firstproductid=$firstProductId AND secondproductid=$secondProductId AND questionnaireid=$chosenQuestionnaireId";

        $ret = $db->exec($sql);
        if ($ret > 0) {
            $db->close();
            return true;
        } else {
            $db->close();
            return false;
        }

    } else {
        addComparison($firstProductId, $secondProductId, $chosenQuestionnaireId, $rate);
    }
}

?>