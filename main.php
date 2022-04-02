<?php session_start();
include_once 'functions.php';
include 'processes.php';
$GLOBALS['items_per_page'] = $_SESSION['items_per_page'];
$query_list = json_decode($_SESSION['query_list'],true);
    if (empty($query_list)){
        $query_list[0] = $_SERVER["REQUEST_URI"];
    }else {
        $query_list[count($query_list)] =  $_SERVER["REQUEST_URI"];
    }
    $_SESSION['query_list'] = json_encode($query_list);



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>

    <div class="everything">

        <div class="sidebar">
            <p>hello</p>
            <div class="history">
                <?php
                // print search history
                if ($search_history) {
                    $search_count = count($search_history);
                    for ($i = $search_count - 1; $i > $search_count - 4 && $i >= 0; $i--) {
                        print_search_history($search_history[$i]);
                    }
                }
                ?>
            </div>
            <div class="favorites">
                <?php
                // print favorites
                if (isset($favorites)) {


                    foreach ($favorites as $item) {
                        print_favorites($item);
                    }
                }
                ?>

            </div>

        </div>

        <div class="content">

            <!-- go to index to generate the srote again and start fresh -->
            <form action='index.php'>
                <input type='submit' name='home' value='go home'>
            </form>

            <!-- refresh page and get rid of search results if there are any -->
            <form>
                <input type='submit' name='refresh_search' value='Refresh'>
            </form>
            <div class="head">
                <!-- search for specific products based on their properties  -->
                <form>
                    <input type='text' name='name' placeholder='name'>
                    <input type='text' name='about' placeholder='about'>

                    <input type='radio' id='sizex' name='size' value='x'>
                    <label for='sizex'> size X </label>
                    <input type='radio' id='sizexl' name='size' value='xl'>
                    <label for='sizexl'> size XL </label>
                    <button type="submit" name='search' value='search'><img class="search_icon" src="./images/search.png" alt=""></button>
                    <input type='submit' name='search' value='search'>
                </form>

                <!-- add new porducts form -->
                <div class="dropdown">
                    <button class="dropbtn">Add a new product</button>
                    <div class="dropdown-content">
                        <!-- <a href="#">Link 1</a>
                    <a href="#">Link 2</a>
                    <a href="#">Link 3</a> -->
                        <form action='./main.php'>
                            <label></label><br>
                            <label>Name</label>
                            <input type='text' name='addname'>
                            <label>About</label>
                            <input type='text' name='addabout'>
                            <label>Size</label>
                            <label>x</label>
                            <input type='radio' name='addsize' value='x'>
                            <label>xl</label>
                            <input type='radio' name='addsize' value='xl'>
                            <input type='submit' name='AddProduct' value='Add Product'>
                        </form>
                    </div>
                </div>
            </div>

            <!-- what to echo -->
            <?php
            error_reporting(E_ALL);

            // if (isset($_GET['favorite'])) {
            //     // get the search result from session 
            //         if (isset($_SESSION['search_result'])) {
            //             foreach ($search_result as $key => $item) {
            //             }

            //         $_SESSION['search_result'] = json_encode($search_result);
            //         $_SESSION['show'] =  $_SESSION['search_result'];
            //     // update it's items with the new values 
            // }
            // }

            // print sth on the page . 
            // put it here so everything is done first before deciding 
            // what to put on the page

            $show = json_decode($_SESSION["show"], true);

            // if ( isset($_SESSION['search_result'])) {
            //     $search_result = json_decode($_SESSION['search_result'],true);
            //     $show = json_decode($_SESSION['search_result'],true);
            // } else {
            //     $show = array_slice($products, 0 , $items_per_page);
            // }




            // take an array of products 
            // $products = json_decode($_SESSION['products'], true);
            // devide it into arrays of 15 products each 

            echo "<div class ='container'>";
            $pages = json_decode($_SESSION['pages'], true);

            // what are the result of count(array)/15 and count(array) % 15 
            $array_length = count($show);
            $page_index = 0;
            $start = 0;
            $results_exist = !empty($show);
            if ($results_exist) {
                $pages = devide_into_pages($show);
                $_SESSION['pages'] = json_encode($pages);
                // print each array in a separate page 
                // if(isset($_GET[]))
                make_pages_buttons($pages);
            } else {
                $pages = $show;
                echo "<h1>item not found</h1>";
            }
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                foreach ($pages[$page] as $product) {
                    printProduct($product);
                }
            } else {
                if ($results_exist) {
                    foreach ($pages[0] as $product) {
                        printProduct($product);
                    }
                }
            }

            echo "</div>";


            // foreach ($show as $product) {
            //     printProduct($product);
            // }


            echo "<pre>";
            print_r($_SESSION);
            echo "favorites";
            print_r($favorites);
            echo "<br>";
            // print_r($show);
            echo $_SERVER['QUERY_STRING'];
            // print_r($pages);
            // echo "<br> show" . empty($show);
            // print_r($show);
            foreach ($_SERVER as $key => $value) {
                echo "$key      --------------------     $value <br>";
            }
            print_r($query_list);
            echo"<br>". end($query_list);



            // print search history 
            ?>
        </div>
    </div>
</body>

</html>