<?php

session_start();

require_once 'classes/GovernmentOJT.php';
require_once 'classes/PrivateCompanyOJT.php';
require_once 'classes/NGOOJT.php';


// Only allow POST requests

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: index.php");
    exit;

}


// Get submitted information

$studentId = trim($_POST["studentId"] ?? "");

$additionalHours = $_POST["additionalHours"] ?? "";


// Validate Student ID

if ($studentId === "") {

    die("Student ID is required.");

}


// Validate additional hours

if ($additionalHours === "") {

    die("Additional hours are required.");

}


// Convert to integer

$additionalHours = (int) $additionalHours;


// Prevent negative hours

if ($additionalHours < 0) {

    die("Additional hours cannot be negative.");

}


// Check if session contains interns

if (!isset($_SESSION["interns"])) {

    die("No intern records found.");

}


$internFound = false;


// Find the correct intern

foreach ($_SESSION["interns"] as &$intern) {

    if ($intern["studentId"] === $studentId) {

        $internFound = true;


        // Calculate new total hours

        $newHours =
            $intern["hoursRendered"] + $additionalHours;


        // Prevent hours from exceeding required hours

        if ($newHours > $intern["requiredHours"]) {

            die(
                "Updated hours cannot be greater than required hours."
            );

        }


        // Save the updated hours

        $intern["hoursRendered"] = $newHours;


        break;

    }

}


// Remove reference after foreach

unset($intern);


// If no matching intern was found

if (!$internFound) {

    die("Intern record not found.");

}


// Return to dashboard

header("Location: index.php");

exit;

?>