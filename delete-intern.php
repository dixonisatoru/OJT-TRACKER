<?php

session_start();


// ==================================================
// ONLY ALLOW POST REQUESTS
// ==================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: intern-records.php");
    exit;

}


// ==================================================
// GET STUDENT ID
// ==================================================

$studentId =
    trim($_POST["studentId"] ?? "");


// ==================================================
// VALIDATE STUDENT ID
// ==================================================

if ($studentId === "") {

    die("Student ID is required.");

}


// ==================================================
// CHECK SESSION RECORDS
// ==================================================

if (!isset($_SESSION["interns"])) {

    die("No intern records found.");

}


// ==================================================
// FIND AND DELETE INTERN
// ==================================================

$internFound = false;


foreach (
    $_SESSION["interns"]
    as $index => $intern
) {

    if (
        $intern["studentId"] ===
        $studentId
    ) {

        unset(
            $_SESSION["interns"][$index]
        );

        $internFound = true;

        break;

    }

}


// ==================================================
// CHECK IF INTERN EXISTS
// ==================================================

if (!$internFound) {

    die("Intern record not found.");

}


// ==================================================
// RE-INDEX SESSION ARRAY
// ==================================================

$_SESSION["interns"] =
    array_values(
        $_SESSION["interns"]
    );


// ==================================================
// REDIRECT AFTER SUCCESS
// ==================================================

header(
    "Location: intern-records.php?deleted=1"
);

exit;

?>
