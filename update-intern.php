<?php

session_start();

require_once 'classes/GovernmentOJT.php';
require_once 'classes/PrivateCompanyOJT.php';
require_once 'classes/NGOOJT.php';


// ==================================================
// ONLY ALLOW POST REQUESTS
// ==================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: intern-records.php");
    exit;

}


// ==================================================
// GET FORM DATA
// ==================================================

$originalStudentId =
    trim($_POST["originalStudentId"] ?? "");

$name =
    trim($_POST["name"] ?? "");

$studentId =
    trim($_POST["studentId"] ?? "");

$company =
    trim($_POST["company"] ?? "");

$ojtType =
    $_POST["ojtType"] ?? "";

$hoursRendered =
    $_POST["hoursRendered"] ?? "";

$requiredHours =
    $_POST["requiredHours"] ?? "";


// ==================================================
// VALIDATE REQUIRED FIELDS
// ==================================================

if (
    $originalStudentId === "" ||
    $name === "" ||
    $studentId === "" ||
    $company === "" ||
    $ojtType === "" ||
    $hoursRendered === "" ||
    $requiredHours === ""
) {

    die("Please complete all required fields.");

}


// ==================================================
// CONVERT HOURS TO INTEGER
// ==================================================

$hoursRendered =
    (int) $hoursRendered;

$requiredHours =
    (int) $requiredHours;


// ==================================================
// VALIDATE HOURS
// ==================================================

if ($hoursRendered < 0) {

    die("Hours rendered cannot be negative.");

}


if ($requiredHours <= 0) {

    die("Required hours must be greater than zero.");

}


if ($hoursRendered > $requiredHours) {

    die(
        "Hours rendered cannot be greater than required hours."
    );

}


// ==================================================
// VALIDATE OJT TYPE
// ==================================================

$validTypes = [
    "government",
    "private",
    "ngo"
];

if (!in_array($ojtType, $validTypes, true)) {

    die("Invalid OJT type selected.");

}


// ==================================================
// CHECK SESSION RECORDS
// ==================================================

if (!isset($_SESSION["interns"])) {

    die("No intern records found.");

}


// ==================================================
// CHECK FOR DUPLICATE STUDENT ID
// ==================================================

foreach ($_SESSION["interns"] as $savedIntern) {

    if (
        $savedIntern["studentId"] === $studentId &&
        $savedIntern["studentId"] !== $originalStudentId
    ) {

        die(
            "Student ID already exists. Please use a different Student ID."
        );

    }

}


// ==================================================
// FIND ORIGINAL INTERN
// ==================================================

$internFound = false;

$oldType = "";

$oldRequirementsStatus = [];

$internIndex = null;


foreach (
    $_SESSION["interns"]
    as $index => $savedIntern
) {

    if (
        $savedIntern["studentId"] ===
        $originalStudentId
    ) {

        $internFound = true;

        $internIndex = $index;

        $oldType =
            $savedIntern["type"];

        if (
            isset(
                $savedIntern["requirementsStatus"]
            )
        ) {

            $oldRequirementsStatus =
                $savedIntern["requirementsStatus"];

        }

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
// CREATE UPDATED OJT OBJECT
// ==================================================

switch ($ojtType) {

    case "government":

        $intern = new GovernmentOJT(
            $name,
            $studentId,
            $company,
            $hoursRendered,
            $requiredHours
        );

        break;


    case "private":

        $intern = new PrivateCompanyOJT(
            $name,
            $studentId,
            $company,
            $hoursRendered,
            $requiredHours
        );

        break;


    case "ngo":

        $intern = new NGOOJT(
            $name,
            $studentId,
            $company,
            $hoursRendered,
            $requiredHours
        );

        break;

}


// ==================================================
// HANDLE REQUIREMENT STATUS
// ==================================================

$requirementsStatus = [];

$requirements =
    $intern->getRequirements();


// If the OJT type did not change,
// preserve the previous requirement status.

if ($oldType === $ojtType) {

    foreach ($requirements as $requirement) {

        $requirementsStatus[$requirement] =
            $oldRequirementsStatus[$requirement]
            ?? false;

    }

} else {

    // If OJT type changed, create
    // a fresh requirement checklist.

    foreach ($requirements as $requirement) {

        $requirementsStatus[$requirement] = false;

    }

}


// ==================================================
// UPDATE SESSION RECORD
// ==================================================

$_SESSION["interns"][$internIndex] = [

    "type" =>
        $ojtType,

    "name" =>
        $intern->getName(),

    "studentId" =>
        $intern->getStudentId(),

    "company" =>
        $intern->getCompany(),

    "hoursRendered" =>
        $intern->getHoursRendered(),

    "requiredHours" =>
        $intern->getRequiredHours(),

    "requirementsStatus" =>
        $requirementsStatus

];


// ==================================================
// REDIRECT AFTER SUCCESS
// ==================================================

header(
    "Location: intern-records.php?updated=1"
);

exit;

?>