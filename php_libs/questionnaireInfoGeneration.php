<?php

include('utils.php');

function generateMatrix($selectedQuestionnaire)
{
    if ($selectedQuestionnaire != 0) {
        ?>
        <h3>Matrix: </h3>
        <?php
        $tableSize = selectCountOfProductsForProductType(selectProductTypeNameFromQuestionnaire($selectedQuestionnaire));
        $iterations = changeTableSize($tableSize + 1) - 1;
        $productsIds = getProductIdsAsArray(selectProductTypeFromQuestionnaire($selectedQuestionnaire));
        echo "<div class=pure-g>";
        for ($i = 0; $i <= $tableSize; $i++) {
            for ($j = 0; $j <= $iterations; $j++) {
                $class = getClassForDiv($tableSize + 1);
                if ($i == 0 && $j <= $tableSize) {
                    $class .= " bordered_headers";
                    $productName = selectProductNameById($productsIds[$j]);
                    echo "<div class=\"$class\" onclick='showPopup($productsIds[$j],\"$productName\")' onmouseover=\"\" style=\"cursor: pointer;\">";
                    echo $productName;
                    echo "</div>";
                } elseif ($j == 0) {
                    $class .= " bordered_headers";
                    $productName = selectProductNameById($productsIds[$i]);
                    echo "<div class=\"$class\" onclick='showPopup($productsIds[$i],\"$productName\")' onmouseover=\"\" style=\"cursor: pointer;\">";
                    echo $productName;
                    echo "</div>";
                } else if ($j > $tableSize) {
                    echo "<div class=\"$class\">";
                    echo "</div>";
                } else {
                    $class .= "bordered_all";
                    $rate = selectRate($productsIds[$i], $productsIds[$j], $selectedQuestionnaire);
                    if ($i == $j && $i <= $tableSize && $j <= $tableSize) {
                        $class .= " same_products";
                    } else {
                        if (empty($rate)) {
                            $class .= " excluded_products";
                        } else if ($rate > 1.0) {
                            $class .= " better_product";
                        } else if ($rate < 1.0) {
                            $class .= " worse_product";
                        }
                    }
                    echo "<div class=\"$class\">";
                    echo $rate;
                    echo "</div>";
                }
            }
        }
        echo "</div>";
    }
}

function generateInfo($questionnaireId)
{
    echo "<h3>Statistics: </h3>";
    $productsIds = getProductIdsAsArray(selectProductTypeFromQuestionnaire($questionnaireId));
    $bestId = 0;
    $worstId = 0;
    $worstResult = 2 * selectCountOfProductsForProductType(selectProductTypeNameFromQuestionnaire($questionnaireId));;
    $bestResult = 0;
    $info = array();
    foreach ($productsIds as $productId) {
        $info[$productId]["name"] = selectProductNameById($productId);
        $summaryRate = selectSummaryRateForChosenQuestionnaires($productId, $questionnaireId);
        if (!empty($summaryRate)) {
            $info[$productId]["sumRate"] = $summaryRate;
            if ($summaryRate < $worstResult) {
                $worstResult = $summaryRate;
                $worstId = $productId;
            }
            if ($summaryRate > $bestResult) {
                $bestResult = $summaryRate;
                $bestId = $productId;
            }
        }
    }
    echo "<table>";
    foreach ($productsIds as $productId) {
        $class = '';
        if ($productId == $bestId) {
            $class = "better_product";
        }
        if ($productId == $worstId && $productId != $bestId) {
            $class = "worse_product";
        }
        if (!empty($info[$productId]["sumRate"])) {
            echo "<tr><td class = $class>" . $info[$productId]["name"] . "</td><td class=$class>" . $info[$productId]["sumRate"] . "</td></tr>";
        } else {

        }
    }
    echo "</table>";
}

function generateOverallMatrix($productTypeId, $isAllQuestionnaires)
{
    echo "<h3>Matrix: </h3>";
    $productTypeName = selectProductTypeName($productTypeId);
    $tableSize = selectCountOfProductsForProductType($productTypeName);
    $iterations = changeTableSize($tableSize + 1) - 1;
    $productsIds = getProductIdsAsArray($productTypeId);
    echo "<div class=pure-g>";
    for ($i = 0; $i <= $tableSize; $i++) {
        for ($j = 0; $j <= $iterations; $j++) {
            $class = getClassForDiv($tableSize + 1);
            if ($i == 0 && $j <= $tableSize) {
                $class .= " bordered_headers";
                $productName = selectProductNameById($productsIds[$j]);
                echo "<div class=\"$class\" onclick='showPopup($productsIds[$j],\"$productName\")' onmouseover=\"\" style=\"cursor: pointer;\">";
                echo $productName;
                echo "</div>";
            } elseif ($j == 0) {
                $class .= " bordered_headers";
                $productName = selectProductNameById($productsIds[$i]);
                echo "<div class=\"$class\" onclick='showPopup($productsIds[$i],\"$productName\")' onmouseover=\"\" style=\"cursor: pointer;\">";
                echo $productName;
                echo "</div>";
            } else if ($j > $tableSize) {
                echo "<div class=\"$class\">";
                echo "</div>";
            } else {
                $class .= "bordered_all";
                if ($isAllQuestionnaires){
                    $rate = selectRateForAllQuestionnaires($productsIds[$i], $productsIds[$j]);
                } else {
                    $rate = selectRateForAllFullQuestionnaires($productsIds[$i], $productsIds[$j]);
                }
                if ($i == $j && $i <= $tableSize && $j <= $tableSize) {
                    $class .= " same_products";
                } else {
                    if (empty($rate)) {
                        $class .= " excluded_products";
                    } else if ($rate > 1.0) {
                        $class .= " better_product";
                    } else if ($rate < 1.0) {
                        $class .= " worse_product";
                    }
                }
                echo "<div class=\"$class\">";
                echo $rate;
                echo "</div>";
            }
        }
    }
    echo "</div>";
}

function generateOverallInfo($productTypeId, $isAllQuestionnaires)
{
    echo "<h3>Statistics: </h3>";
    $productsIds = getProductIdsAsArray($productTypeId);
    $bestId = 0;
    $worstId = 0;
    $worstResult = 2 * selectCountOfProductsForProductType(selectProductTypeName($productTypeId));
    $bestResult = 0;
    $info = array();
    foreach ($productsIds as $productId) {
        $info[$productId]["name"] = selectProductNameById($productId);
        if ($isAllQuestionnaires){
            $summaryRate = selectSummaryRateForAllQuestionnaires($productId);
        } else {
            $summaryRate = selectSummaryRateForAllFullQuestionnaires($productId);
        }
        if (!empty($summaryRate)) {
            $info[$productId]["sumRate"] = $summaryRate;
            if ($summaryRate < $worstResult) {
                $worstResult = $summaryRate;
                $worstId = $productId;
            }
            if ($summaryRate > $bestResult) {
                $bestResult = $summaryRate;
                $bestId = $productId;
            }
        }
    }
    echo "<table>";
    foreach ($productsIds as $productId) {
        $class = '';
        if ($productId == $bestId) {
            $class = "better_product";
        }
        if ($productId == $worstId && $productId != $bestId) {
            $class = "worse_product";
        }
        if (!empty($info[$productId]["sumRate"])) {
            echo "<tr><td class = $class>" . $info[$productId]["name"] . "</td><td class=$class>" . $info[$productId]["sumRate"] . "</td></tr>";
        } else {

        }
    }
    echo "</table>";
}

function changeTableSize($number)
{
    if ($number == 1) {
        return 1;
    } elseif ($number == 2) {
        return 2;
    } elseif ($number == 3) {
        return 3;
    } elseif ($number == 4) {
        return 4;
    } elseif ($number == 5) {
        return 5;
    } elseif ($number == 6) {
        return 6;
    } elseif ($number == 7) {
        return 7;
    } elseif ($number == 8) {
        return 8;
    } elseif ($number == 9) {
        return 9;
    } elseif ($number == 10) {
        return 10;
    } elseif ($number == 11) {
        return 11;
    } elseif ($number == 12) {
        return 12;
    } elseif ($number == 13) {
        return 13;
    } elseif ($number == 14) {
        return 14;
    } elseif ($number == 15) {
        return 15;
    } elseif ($number == 16) {
        return 16;
    } elseif ($number <= 24) {
        return 24;
    }
}

function getClassForDiv($number)
{
    if ($number == 1) {
        return "pure-u-1-1 ";
    } elseif ($number == 2) {
        return "pure-u-1-2 ";
    } elseif ($number == 3) {
        return "pure-u-1-3 ";
    } elseif ($number == 4) {
        return "pure-u-1-4 ";
    } elseif ($number == 5) {
        return "pure-u-1-5 ";
    } elseif ($number == 6) {
        return "pure-u-1-6 ";
    } elseif ($number == 7) {
        return "pure-u-1-7 ";
    } elseif ($number == 8) {
        return "pure-u-1-8 ";
    } elseif ($number == 9) {
        return "pure-u-1-9 ";
    } elseif ($number == 10) {
        return "pure-u-1-10 ";
    } elseif ($number == 11) {
        return "pure-u-1-11 ";
    } elseif ($number == 12) {
        return "pure-u-1-12 ";
    } elseif ($number == 13) {
        return "pure-u-1-13 ";
    } elseif ($number == 14) {
        return "pure-u-1-14 ";
    } elseif ($number == 15) {
        return "pure-u-1-15 ";
    } elseif ($number == 16) {
        return "pure-u-1-16 ";
    } elseif ($number <= 24) {
        return "pure-u-1-24 ";
    }
}

function getProductIdsAsArray($productTypeId)
{
    $productsIds = array();
    $productsIds[0] = 0;
    $i = 1;
    $ret = selectProductsByProductType($productTypeId);
    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $productsIds[$i] = $row["id"];
        $i++;
    }
    return $productsIds;
}

?>