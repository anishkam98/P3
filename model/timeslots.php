<?php

function get_timeslots() {
    global $db;
    $query = "SELECT TimeSlotId FROM students";
    $result = $db->query($query);

    if ($result == false) {
        display_db_error($db->error);
    }

    $filled_slots = array();
    for ($i = 0; $i < $result->num_rows; $i++) {
        $filled_slot = $result->fetch_assoc();

        if(!isset($filled_slots[$filled_slot['TimeSlotId']])) {
            $filled_slots[$filled_slot['TimeSlotId']] = 1;
        }
        else {
            $filled_slots[$filled_slot['TimeSlotId']] += 1;
        }

        
    }
    $result->free();

    $query = "SELECT * FROM time_slots";
    $result = $db->query($query);

    if ($result == false) {
        display_db_error($db->error);
    }
    $slots = array();
    for ($i = 0; $i < $result->num_rows; $i++) {
        $slot = $result->fetch_assoc();
        $slot['available_slots'] = 6 - (isset($filled_slots[$slot['ID']]) ? $filled_slots[$slot['ID']] : 0);
        $slot['Date'] = date('m/d/Y', strtotime($slot['Date']));
        $slot['StartTime'] = date('h:i a', strtotime($slot['StartTime']));
        $slot['EndTime'] = date('h:i a', strtotime($slot['EndTime']));

        $slots[] = $slot;
    }
    $result->free();
    return $slots;
}

?>