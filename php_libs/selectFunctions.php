<?php

function selectUsers()
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM users ";

    $ret = $db->query($sql);

    return $ret;
}

function selectUsersWithPrivilege($privilege)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM users WHERE privilege=$privilege";

    $ret = $db->query($sql);

    return $ret;
}

function selectProductsTypes()
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM productstypes ";

    $ret = $db->query($sql);

    return $ret;
}

function selectProductsTypeById($id)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM productstypes where id = $id";

    $ret = $db->query($sql);

    return $ret;
}

function selectProductsTypeByName($name)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM productstypes WHERE name = \"$name\"";

    $ret = $db->query($sql);

    return $ret;
}

function selectProducts()
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM products ";

    $ret = $db->query($sql);

    return $ret;
}

function selectProductById($id)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM products where id = $id";

    $ret = $db->query($sql);

    return $ret;
}

function selectProductNameById($id){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT name FROM products where id = $id";

    $ret = $db->query($sql);

    $rowValue = "";

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["name"];
        }
    }

    return $rowValue;
}

function selectProductImageUrlById($id){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT imageurl FROM products where id = $id";

    $ret = $db->query($sql);

    $rowValue = "";

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["imageurl"];
        }
    }

    return $rowValue;
}

function selectProductDescriptionById($id){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT description FROM products where id = $id";

    $ret = $db->query($sql);

    $rowValue = "";

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["description"];
        }
    }

    return $rowValue;
}


function selectQuestionnaires()
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM questionnaires ";

    $ret = $db->query($sql);

    return $ret;
}

function selectQuestionnaireById($id)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM questionnaries where id=$id";

    $ret = $db->query($sql);

    return $ret;
}

function selectQuestionnaireByProductType($productTypeId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM questionnaires WHERE typeid=$productTypeId";

    $ret = $db->query($sql);

    return $ret;
}

function selectProductsByProductType($productTypeId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM products WHERE typeid = $productTypeId";

    $ret = $db->query($sql);

    return $ret;
}

function selectIfProductIsProductType($productId,$productTypeId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT count(*) AS count FROM products WHERE typeid = $productTypeId AND id = $productId";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["count"];
        }
    }

    if ($rowValue == 0){
        return false;
    } else {
        return true;
    };
}

function selectQuestionnaireByUser($userId)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM questionnaires WHERE personid=$userId";

    $ret = $db->query($sql);

    return $ret;
}

function selectUserLogin($id)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM users WHERE id=$id";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["login"];
        }
    }

    return $rowValue;
}

function selectUserId($login)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM users WHERE login=\"$login\"";

    $ret = $db->query($sql);

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["id"];
        }
    }

    return $rowValue;
}

function selectProductTypeName($id)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM productstypes where id=$id";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["name"];
        }
    }

    return $rowValue;
}

function selectProductTypeNameFromQuestionnaire($id)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM questionnaires where id=$id";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["typeid"];
        }
    }

    if ($rowValue == 0){
        return;
    }

    $sql = "SELECT * FROM productstypes where id=$rowValue";

    $ret = $db->query($sql);

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["name"];
        }
    }

    return $rowValue;
}

function selectProductsForQuestionnaireWithOffsetAndResults($chosenQuestionnaireId, $productTypeName, $offset, $results)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT first.id as firstid, first.name AS firstname, first.description AS firstdescription, first.imageurl AS firstimageurl,
            second.id as secondid, second.name AS secondname, second.description AS seconddescription, second.imageurl AS secondimageurl
            FROM products AS first JOIN products AS second on first.name != second.name
            AND first.id < second.id AND first.typeid == second.typeid AND first.typeid IN (SELECT id FROM productstypes WHERE name = \"$productTypeName\")
            WHERE first.id NOT IN (SELECT productid FROM excludedproducts WHERE questionnaireid = $chosenQuestionnaireId)
            LIMIT $offset, $results";

    $ret = $db->query($sql);

    return $ret;
}

function selectCountOfQuestionsForQuestionnaire($chosenQuestionnaireId, $productTypeName)
{
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT count(first.id) AS count FROM products AS first JOIN products AS second on first.name != second.name
            AND first.id < second.id AND first.typeid == second.typeid AND first.typeid IN (SELECT id FROM productstypes WHERE name = \"$productTypeName\")
            WHERE first.id NOT IN (SELECT productid FROM excludedproducts WHERE questionnaireid = $chosenQuestionnaireId)";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["count"];
        }
    }

    return $rowValue;
}

function selectCountOfProductsForProductType($productTypeName){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT count(id) as count FROM products WHERE typeid IN (SELECT id FROM productstypes WHERE name = \"$productTypeName\")";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["count"];
        }
    }

    return $rowValue;
}

function selectCountOfQuestionnairesForUser($userId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT count(id) as count FROM questionnaires WHERE personid = $userId";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["count"];
        }
    }

    return $rowValue;
}

function selectProductTypeFromQuestionnaire($qId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT typeid FROM questionnaires WHERE id=$qId";

    $ret = $db->query($sql);

    $rowValue = "";

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["typeid"];
        }
    }

    return $rowValue;
}

function selectDescriptionFromQuestionnaire($qId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT description FROM questionnaires WHERE id=$qId";

    $ret = $db->query($sql);

    $rowValue = "";

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["description"];
        }
    }

    return $rowValue;
}

function selectComparisonsCountByPK($firstProdId, $secondProdId, $questionnaireId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT count(*) AS count FROM comparisons WHERE firstproductid=$firstProdId AND secondproductid=$secondProdId AND questionnaireid=$questionnaireId";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["count"];
        }
    }
    return $rowValue;
}

function selectComparisonsByQuestionnaire($questionnaireId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM comparisons WHERE questionnaireid=$questionnaireId";

    $ret = $db->query($sql);

    return $ret;
}

function selectRate($firstProdId, $secodProdId, $qId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT * FROM comparisons WHERE firstproductid=$firstProdId AND secondproductid=$secodProdId AND questionnaireid=$qId";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["rate"];
        }
    }

    if ($rowValue == 0){
        return "";
    } else {
        return $rowValue;
    };
}

function selectRateForAllQuestionnaires($firstProdId, $secodProdId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT sum(rate) AS rate FROM comparisons WHERE firstproductid=$firstProdId AND secondproductid=$secodProdId";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["rate"];
        }
    }

    if ($rowValue == 0){
        return "";
    } else {
        return $rowValue;
    };
}

function selectRateForAllFullQuestionnaires($firstProdId, $secodProdId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT sum(rate) AS rate FROM comparisons WHERE firstproductid=$firstProdId AND secondproductid=$secodProdId
            AND questionnaireid NOT IN (SELECT questionnaireid FROM excludedproducts)";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["rate"];
        }
    }

    if ($rowValue == 0){
        return "";
    } else {
        return $rowValue;
    };
}


function selectSummaryRateForChosenQuestionnaires($productId, $qId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT SUM(rate) AS sumrate FROM comparisons WHERE questionnaireid = $qId AND firstproductid = $productId";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["sumrate"];
        }
    }

    return $rowValue;
}

function selectSummaryRateForAllQuestionnaires($productId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT SUM(rate) AS sumrate FROM comparisons WHERE firstproductid = $productId";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["sumrate"];
        }
    }

    return $rowValue;
}

function selectSummaryRateForAllFullQuestionnaires($productId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT SUM(rate) AS sumrate FROM comparisons WHERE firstproductid = $productId
            AND questionnaireid NOT IN (SELECT questionnaireid FROM excludedproducts)";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["sumrate"];
        }
    }

    return $rowValue;
}

function selectIfQuestionnaireIsCompleted($qId){
    $db = new SQLite3("db/db.sqlite3");
    if (!$db) {
        echo $db->lastErrorMsg();
        return false;
    }

    $sql = "SELECT completed FROM questionnaires WHERE id = $qId";

    $ret = $db->query($sql);

    $rowValue = 0;

    if ($ret != false) {
        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
            $rowValue = $row["completed"];
        }
    }

    if ($rowValue == 0){
        return false;
    } else {
        return true;
    };
}


?>