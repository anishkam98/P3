<?php
function is_student_signed_up($um_id) {
    global $db;
    $um_id_esc = $db->escape_string($um_id);
    $query = "SELECT ID FROM students WHERE UMID = '$um_id_esc'";
    $result = $db->query($query);
    $num_rows = $result->num_rows;
    
    if ($result == false) {
        display_db_error($db->error);
    }
    if($num_rows == 0){
        return false;
    }
    $result->free();
    return true;
}

function is_timeslot_available($timeslotid) {
    global $db;
    $timeslot_id_esc = $db->escape_string($timeslotid);
    $query = "SELECT ID FROM students WHERE TimeSlotId = '$timeslot_id_esc'";
    $result = $db->query($query);
    $num_rows = $result->num_rows;

    if ($result == false) {
        display_db_error($db->error);
    }
    if($num_rows < 6){
        return true;
    }
    $result->free();
    include('views/slotfull.php');
    return false;
}

function is_student_valid() {
    $is_valid = true;

    $um_id_pattern = "/[0-9]{8}/";
    if(preg_match($um_id_pattern, $_SESSION["UMID"]) == 0) {
        $_SESSION["formerror"] = 'Please enter a valid 8 digit UMID\n';
        $is_valid = false;
    }

    $first_name_pattern = "/[A-Za-z]{1,50}/";
    if(preg_match($first_name_pattern, $_SESSION["firstname"]) == 0) {
        $_SESSION["formerror"] = 'Please enter a valid first name\n';
        $is_valid = false;
    }

    $last_name_pattern = "/[A-Za-z]{1,50}/";
    if(preg_match($last_name_pattern, $_SESSION["lastname"]) == 0) {
        $_SESSION["formerror"] = 'Please enter a valid last name\n';
        $is_valid = false;
    }
   
    if(!filter_var($_SESSION["email"], FILTER_VALIDATE_EMAIL)) {
        $_SESSION["formerror"] = 'Please enter a valid email address\n';
        $is_valid = false;
    }

    $phone_pattern = "/[0-9]{3}-[0-9]{3}-[0-9]{4}/";
    if($_SESSION["phone"] != '' && preg_match($phone_pattern, $_SESSION["phone"]) == 0) {
        $_SESSION["formerror"] = 'Please enter a valid phone number\n';
        $is_valid = false;
    }

    if($_SESSION["projecttitle"] == '' || strlen($_SESSION["projecttitle"]) > 70) {
        $_SESSION["formerror"] = 'Please enter a valid project title\n';
        $is_valid = false;
    }

    if($_SESSION["timeslot"] == '') {
        $_SESSION["formerror"] = 'Please select a time slot\n';
        $is_valid = false;
    }

    return $is_valid;
}

function add_student($um_id, $firstname, $lastname, $email, $phone, $projecttitle, $timeslot) {
    global $db;
    
    if(!is_student_signed_up($um_id) && is_timeslot_available($timeslot)) {
        $query = 'INSERT INTO students (UMID, FirstName, LastName, EmailAddress,
                    PhoneNumber, ProjectTitle, TimeSlotId)
                    VALUES
                        (?, ?, ?, ?, ?, ?, ?)';
        try {
            $statement = $db->prepare($query);

            if ($statement == false) {
                die ("Error: " . $db->error);
                display_db_error($db->error);
            }
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        
        
        $statement->bind_param('sssssss', $um_id, $firstname, $lastname, $email, $phone, $projecttitle, $timeslot);
        $success = $statement->execute();
  
        if ($success) {
            $student_slot_id = $db->insert_id;
            $statement->close();
            return $student_slot_id;
        } else {
            display_db_error($db->error);
        }
    }
    else if(is_student_signed_up($um_id) && is_timeslot_available($timeslot)) {
        include('views/confirmchange.php');
    } 
}

function update_student() {
    global $db;
    
    $query = 'UPDATE students SET FirstName= ?, LastName = ?, EmailAddress = ?,
                PhoneNumber = ?, ProjectTitle = ?, TimeSlotId = ?
                WHERE UMID = ?';
    try {
        $statement = $db->prepare($query);

        if ($statement == false) {
            die ("Error: " . $db->error);
            display_db_error($db->error);
        }
    }
    catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
    
    $statement->bind_param('sssssss', $_SESSION["firstname"], $_SESSION["lastname"], $_SESSION["email"], $_SESSION["phone"], $_SESSION["projecttitle"], $_SESSION["timeslot"], $_SESSION["UMID"]);
    $success = $statement->execute();

    if ($success) {
        $statement->close();
        include('views/success.php');
    } else {
        display_db_error($db->error);
    }
}

function get_students() {
    global $db;
    $query = "SELECT s.UMID, s.FirstName, s.LastName, s.EmailAddress, s.PhoneNumber, s.ProjectTitle, t.Date, t.StartTime, t.EndTime FROM P3Database.students s INNER JOIN P3Database.time_slots t ON s.TimeSlotId = t.ID";
    $result = $db->query($query);

    if ($result == false) {
        display_db_error($db->error);
    }

    $students = array();
    for ($i = 0; $i < $result->num_rows; $i++) {
        $student = $result->fetch_assoc();
        $student['Date'] = date('m/d/Y', strtotime($student['Date']));
        $student['StartTime'] = date('h:i a', strtotime($student['StartTime']));
        $student['EndTime'] = date('h:i a', strtotime($student['EndTime']));

        $students[] = $student;
    }
    $result->free();

    return $students;
}

?>