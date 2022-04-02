<?php
include_once 'functions.php';
// get the array with 60 products 

$products = json_decode($_SESSION['products'], true);


// add product 
if (isset($_GET['AddProduct'])) {
    $products = add_items($products);
}

// make an array called show[] that contains the current showed items 
// default value is first 15 items 
$show = json_decode($_SESSION['show'], true);

// its value is changed based on search parameteres 
// if (!isset($_SESSION['search_result'])) {
//     // show 15 of them 
//     $_SESSION['show'] = json_encode(array_slice($products, 0));
// }
// it's printed using a function at the end of the script


// refresh page and get rid of search results if there are any
if (isset($_GET['refresh_search'])) {
    if (isset($_SESSION['search_result'])) {
        unset($_SESSION['search_result']);
    }
    // else {
    //     echo "you didn't search anything yet";
    // }
    $_SESSION['show'] = $_SESSION['products'];
    refresh_page();
}
// perform the search 
// $search_result = array();

$search_history = json_decode($_SESSION['search_history'], true);

if (isset($_GET["search"])) {
    search_items($products);

    //  search history 
    $searchAbout = $_GET["about"] ? $_GET['about'] : " ";
    $searchName = $_GET['name'] ? $_GET["name"] : " ";
    $searchSize = isset($_GET["size"]) ? $_GET['size'] : " ";
    if ($searchName != " " || $searchAbout != " " || $searchSize != " ") {
        $search_history_item = array(
            "size" => "$searchSize",
            "name" => "$searchName",
            "about" => "$searchAbout"
        );
        if (empty($search_history)) {
            $search_history[0] = $search_history_item;
        } else if (!in_array($search_history_item, $search_history)) {
            $search_history[count($search_history)] = $search_history_item;
        }
        $_SESSION['search_history'] = json_encode($search_history);
    }
}

$favorites = json_decode($_SESSION["favorites"], true);


// for each product add next to it a "add to favourite " input button to 
if (isset($_GET["favorite"])) {

    $product_id = $_GET['favorite_item_id'];
    

    // add to favorites
    if ($_GET['favorite'] == "Add to Favourite") {
        // in_array() and count() throw warnings if you give them an empty array
        // so using empty() avoids that warning 
        $products[$product_id]["favorite"] = 1;
        $product_at_hand = $products[$product_id];

        // to avoid the error returned from "in_array" when the array is empty we check
        // if it's empty first
        if (empty($favorites)) {
            $favorites[$product_id] = $product_at_hand;
        } else if (!in_array($product_at_hand, $favorites)) {
            // to preserve the index use $product_id
            $favorites[$product_id] = $product_at_hand;
        }
    } 

    // remove from favorites 
    else if ($_GET['favorite'] == "Remove from Favorite") {
        $products[$product_id]['favorite'] = 0;
        unset($favorites[$product_id]);
    }

    
        foreach ($show as $key => $product) {
            // $show[$key]["favorite"] = $products[$key]['favorite'];
            $show[$key] = $products[$key];
        }
    

    // store the users favourite products in a session and update store products


    $_SESSION["favorites"] = json_encode($favorites);
    $_SESSION['products'] = json_encode($products);
    $_SESSION["show"] = json_encode($show);
    
    reQuery();
}

// implement a search history that saves the user's last 3 searches
