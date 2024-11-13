<?php
require_once('model/database.php');
require_once('model/students.php');
require_once('model/timeslots.php');

function setupSession($session_variable) {
    return $session_variable != null ? $session_variable : '';
}

session_start();

foreach ($_SESSION as $name) {
    $name = setupSession($name);
}

// Get all slots
$timeslots = get_timeslots();
$session_choice = isset($_SESSION["timeslot"]) ? $_SESSION["timeslot"] : '';

// Display the home page
include('signup.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($_POST['action']) {
        case 'add_student':
            // Retrieve form data
            $_SESSION["UMID"] = $_POST["UMID"];
            $_SESSION["firstname"] = $_POST["firstname"];
            $_SESSION["lastname"] = $_POST["lastname"];
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["phone"] = $_POST["phone"];
            $_SESSION["projecttitle"] = $_POST["projecttitle"];
            $_SESSION["timeslot"] = $_POST["slot"];

            if(is_student_valid()) {
                $session_choice = $_POST["slot"];
                $student_id = add_student($_SESSION["UMID"], $_SESSION["firstname"], $_SESSION["lastname"], $_SESSION["email"], $_SESSION["phone"], $_SESSION["projecttitle"], $_SESSION["timeslot"]);
                if($student_id){
                    include('views/success.php');
                }
            
                
            }
            else {
                $_SESSION["timeslot"] = 'Invalid student';
                include('views/invalidinfo.php');
            }
            break;
        case 'confirm_update':
            // Retrieve form data
            $should_change= $_POST["should_change"];
            
            if(is_student_valid() && $should_change == 'yes') {
                update_student();
            }
            else {
                header("Refresh:0");
            }
            break;
     } 
}

?>