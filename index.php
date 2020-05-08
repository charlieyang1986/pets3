<?php
session_start();
//turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//require autoload file
require_once('vendor/autoload.php');

//instantiate the F3 Base class
$f3 = Base::instance();

//define a default route
//when user visits the default root(file) - ...328/pets2
//it runs the function

$f3->route('GET /', function()
{
    //echo '<h1>My Pets</h1>';
    //echo "<a href='order'>Order a Pet</a>";
    $view = new Template();
    echo $view->render('views/pet-home.html');
});

// define an order route  GET when clicked from the link
// on home page 'Order a Pet'
// POST when the form submits to its own page pet-order
$f3->route('GET|POST /order', function($f3){
    //echo '<h1>My Pets</h1>';
    //echo "<a href='order'>Order a Pet</a>";
    //checks if the form has been submitted
    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        $valid = true;
        //Validate the data
        if (empty($_POST['pet']))
        {
            $valid = false;
            echo "Please supply a pet type <br>";
        }
        if ($_POST['color'] != "Brown" && $_POST['color'] != "Black" && $_POST['color'] != "White")
        {
            $valid = false;
            echo "Please supply a valid color";
        }
        if($valid)
            {
             //Data is valid
             $_SESSION['pet'] = $_POST['pet'];
             $_SESSION['color'] = $_POST['color'];

		      //***Add the color to the session


		      //Redirect to the summary route
             $f3->reroute("summary");
             session_destroy();
         }

    }

    $view = new Template();
    echo $view->render('views/pet-order.html');
});

$f3->route('GET /summary', function()
{
    //echo '<h1>My Pets</h1>';
    //echo "<a href='order'>Order a Pet</a>";
    $view = new Template();
    echo $view->render('views/order-summary.html');
});


//run fat free
$f3->run();
