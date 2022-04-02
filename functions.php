<?php
function printProduct($product)
{

    // change 1
    $in_favourites = $product['favorite'] > 0;
    if ($in_favourites) {
        $button = "<button type='submit' name ='favorite' value='Remove from Favorite'>
                    <img class='favon' src='./images/favOn.png' alt='remove from favorite'>
                </button>";
    } else {
        $button = "<button type='submit' name ='favorite' value='Add to Favourite'>
                <img class='favon' src='./images/favOff.png' alt='add to favorite'>
                </button>";
    }
    echo "<table>
        <th>$product[name]</th>
        <tr><td>$product[size]</td></tr>
        <tr><td>$product[about]</td></tr>
        <tr><td><form>
        <input type='hidden' name='favorite_item_id' value='$product[id]'>
        <input type='submit' name ='favorite' value='Add to Favourite'>
        $button
        </form></td></tr>
        </table>
        ";
}

function refresh_page()
{
    echo '<script>window.location="' . $_SERVER["PHP_SELF"] . '"</script>';
    // $_SERVER["PHP_SELF"]
}

function reQuery (){
    // get the query list
    // go to the before last link
    $queries = json_decode($_SESSION['query_list'],true) ;
    $last = end($queries);
    echo '<script>window.location="' . $last.'"</script>';
}

function search_items($products)
{
    $search_result = array();
    foreach ($products as $product) {
        // conditions are only checked if user puts an input or ticks a box otherwise 
        // they're not accounted for by making them true anyways
        $id = $product['id'];
        $isAbout = isset($_GET['about']) && $_GET['about'] != "" ? (strpos($product['about'], $_GET['about']) !== false) : true;
        $isName = isset($_GET['name']) && $_GET['name'] != "" ? (strpos($product['name'], $_GET['name']) !== false) : true;
        $isSize = isset($_GET['size']) ? ($product['size'] == $_GET['size']) : true;
        if ($isSize && $isAbout && $isName) {
            if (!in_array($product, $search_result)) {
                $search_result[$id] = $product;
            }
        }
    }
    
    $_SESSION['show'] = json_encode($search_result);
    $_SESSION['priority_list'] = "show";
}

function add_items($products)
{
    $id = count($products);
    $size = isset($_GET['addsize']) ? $_GET['addsize'] : "x";
    $name = $_GET['addname'] ? $_GET['addname'] : "unkown" . ($id + 1);
    $about = $_GET['addabout'] ? $_GET['addabout'] : "unkown";
    array_push($products, array(
        "id" => $id,
        "size" => "$size",
        "name" => "$name",
        "about" => "$about",
        "favorite" => 0
    ));

    $_SESSION['products'] = json_encode($products);
    // search items refreshes the value of show and thus shows you the new product 
    // added
    search_items($products);
    refresh_page();

    
}

function print_search_history($history_array)
{
    echo "<div class='search_item'>";
    foreach ($history_array as $key => $value) {
        if ($value != " ") {
            echo "$key : $value <br>";
        }
    }
    echo "</div>";
}

function print_favorites($favorite_item)
{
    echo "$favorite_item[name] <br>
        $favorite_item[about]<br>";
}


function devide_into_pages($list)
{
    $items_per_page = $_SESSION['items_per_page'];
    $array_length = count($list);
    $page_index = 0;
    $start = 0;
    $pages = array();
    while ($start < $array_length) {
        $pages[$page_index] = array_slice($list, $start, $items_per_page);
        $start += 15;
        $page_index++;
    }
    return $pages;
}

function make_pages_buttons($pages)
{
    if (!empty($pages)) {
        $array_length = count($pages);
        if ($array_length > 1) {
            echo "<form style='flex: 0 0 100%;display: flex;justify-content:  center; gap : 20px;'>";
            foreach ($pages as $key => $page) {
                echo "<button type='submit' name='page' value='$key'>page $key</button>";
            }
            echo "</form>";
        }
    }
}
