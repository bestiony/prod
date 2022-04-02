<?php /*session_start();
include_once "functions.php";*/

// take an array of products 
// $products = json_decode($_SESSION['products'], true);
// devide it into arrays of 15 products each 
echo "<div class ='container'>";
$pages = array();

$items_per_page = 15;
// what are the result of count(array)/15 and count(array) % 15 
$array_length = count($show);
$page_index = 0;
$start = 0;
if (count($show)>0) {
    while ($start < $array_length) {
    $pages[$page_index] = array_slice($show, $start, $items_per_page);
    $start += 15;
    $page_index++;
}
// print each array in a separate page 
// if(isset($_GET[]))
if ($array_length > 1) {
    echo "<form>";
    foreach ($pages as $key => $page) {
        echo "<button type='submit' name='page' value='$key'>page $key</button>";
    }
    echo "</form>";
}


if (isset($_GET['page'])) {
    $page = $_GET['page'];
    foreach ($pages[$page] as $product) {
        printProduct($product);
    }
}

} else {
    echo "item not found";
}


echo "</div>";
