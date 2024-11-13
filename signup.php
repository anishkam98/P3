<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>P3</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cute+Font&family=Edu+AU+VIC+WA+NT+Pre:wght@400..700&display=swap" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="styles.css">
    </head>
    <header>
        <h1>Please choose a time slot</h1>
    </header>
    <body>
        <form action="index.php" id='signup-form' method="post">
            <input type="hidden" name="action" value="add_student" >
            <div class="form-items form-items-column">
                <label for="UMID">UMID</label>
                <input type="text" name="UMID" required pattern="[0-9]{8}" value="<?php echo isset($_SESSION["UMID"]) ? $_SESSION["UMID"] : '' ;?>">
            </div>
            <div class="form-items-row form-items">
                <span class="form-items-column">
                    <label for="firstname">First Name</label>
                    <input type="text" name="firstname" required pattern="[A-Za-z]{1,50}" value="<?php echo isset($_SESSION["firstname"]) ? $_SESSION["firstname"] : '';?>">
                </span>
                <span class="form-items-column">
                    <label for="lastname">Last Name</label>
                    <input type="text" name="lastname" required pattern="[A-Za-z]{1,50}" value="<?php echo isset($_SESSION["lastname"]) ? $_SESSION["lastname"] : '';?>">
                </span>
            </div>
            <div class="form-items form-items-column">
                <label for="email">Email Address</label>
                <input type="email" name="email" required value="<?php echo isset($_SESSION["email"]) ? $_SESSION["email"] : '';?>">
            </div>
            <div class="form-items form-items-column">
                <label for="phone">Phone Number</label>
                <input type="tel" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?php echo isset($_SESSION["phone"]) ? $_SESSION["phone"] : '';?>">
            </div>
            <div class="form-items form-items-column">
                <label for="projecttitle">Project Title</label>
                <input type="text" name="projecttitle" required maxlength="70" value="<?php echo isset($_SESSION["projecttitle"]) ? $_SESSION["projecttitle"] : '';?>">
            </div>
            <div id="slots-container form-items-column">
                <?php  foreach ($timeslots as $timeslot) {
                    // Create radio buttons
                    $slot_label = $timeslot['Date'] . ', ' . $timeslot['StartTime'] . ' - ' . $timeslot['EndTime'] . ', ' . $timeslot['available_slots'] . ' seats remaining';

                    echo '<div>
                            <input type="radio" id="' . htmlspecialchars($timeslot['ID']) . '" name="slot" value="' . htmlspecialchars($timeslot['ID']) . '" required ' . (($session_choice != '' && $session_choice==$timeslot['ID']) ? "checked" : "") . '>
                            <label for="' . htmlspecialchars($timeslot['ID']) . '">' . htmlspecialchars($slot_label) . '</label>
                        </div>';
                    }

                ?>
            </div>
            <div class="button-container">
                <button type="submit">Submit</button>
                <button type="button" onclick="navigateToStudents()">View Students</button>
            </div>
        </form>
    </body>
    <script>
        function navigateToStudents() {
            document.location.href = "students.php";
        }
    </script>
</html>