<?php
session_start();
//turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//require autoload file
require_once('vendor/autoload.php');

//instantiate the F3 Base class
$f3 = Base::instance();
$f3->set('colors', array('pink', 'green', 'blue'));

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
        if($valid)
            {
             //Data is valid
             $_SESSION['pet'] = $_POST['pet'];

		      //***Add the color to the session


		      //Redirect to the summary route
             $f3->reroute("order2.html");
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

$f3->route('GET|POST /order2', function($f3)
{
    $conds = getConds();

    //if the form has been submitted
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        //store the data in the session array
        $_SESSION['color'] = $_POST['color'];

        $f3->reroute('summary');

    }

    $f3->set('color', $color);
    $view = new Template();
    echo $view->render('views/order2.html');
});

//Breakfast route
$f3->route('GET /summary', function() {
    //echo '<h1>Thank you for your order!</h1>';

    $view = new Template();
    echo $view->render('views/summary.html');

    session_destroy();
});


//run fat free
$f3->run();
