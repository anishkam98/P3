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
        <h1>Students</h1>
    </header>
    <body>
        <div id="student-list-container">
        <?php 
            require_once('model/database.php');
            require_once('model/students.php');

            $students = get_students();
           
             foreach ($students as $student) {
                // Create radio buttons
                $slot_info = htmlspecialchars($student['Date']) . ', ' . htmlspecialchars($student['StartTime']) . ' - ' . htmlspecialchars($student['EndTime']);

                echo '
                    <div class="student-list-item">
                        <div class="form-items form-items-column">
                            <label for="UMID">UMID</label>
                            <input type="text" name="UMID" readonly value="' . htmlspecialchars($student['UMID']) . '">
                        </div>
                        <div class="form-items-row form-items">
                            <span class="form-items-column">
                                <label for="firstname">First Name</label>
                                <input type="text" name="firstname" readonly value="' . htmlspecialchars($student['FirstName']) . '">
                            </span>
                            <span class="form-items-column">
                                <label for="lastname">Last Name</label>
                                <input type="text" name="lastname" readonly value="' . htmlspecialchars($student['LastName']) . '">
                            </span>
                        </div>
                        <div class="form-items form-items-column">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" readonly value="' . htmlspecialchars($student['EmailAddress']) . '">
                        </div>
                        <div class="form-items form-items-column">
                            <label for="phone">Phone Number</label>
                            <input type="tel" readonly name="phone" value="' . htmlspecialchars($student['PhoneNumber']) . '">
                        </div>
                        <div class="form-items form-items-column">
                            <label for="projecttitle">Project Title</label>
                            <input type="text" name="projecttitle" readonly value="' . htmlspecialchars($student['ProjectTitle']) . '">
                        </div>
                        <div class="form-items form-items-column">
                            <label for="timeslot">Time Slot</label>
                            <input type="text" name="timeslot" readonly value="' . htmlspecialchars($slot_info) . '">
                        </div>
                    </div>
                    ';
                }

            ?>
        </div>
        <div class="button-container">
            <button type="button" onclick="navigateToHome()">Go Back</button>
        </div>
    </body>
    <script>
        function navigateToHome() {
            document.location.href = "index.php";
        }
    </script>
</html>