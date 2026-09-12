<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: requirements.php");
    exit;
}

$studentId = trim($_POST["studentId"] ?? "");
$completedRequirements = $_POST["requirements"] ?? [];

if ($studentId === "") {
    die("Student ID is required.");
}

if (!isset($_SESSION["interns"])) {
    die("No intern records found.");
}

$internFound = false;

foreach ($_SESSION["interns"] as &$intern) {

    if ($intern["studentId"] === $studentId) {

        $internFound = true;

        /*
         * Get the requirements belonging to this intern's OJT type.
         */
        switch ($intern["type"]) {

            case "government":
                $requirements = [
                    "Endorsement Letter",
                    "Memorandum of Agreement",
                    "Medical Certificate",
                    "OJT Training Plan"
                ];
                break;

            case "private":
                $requirements = [
                    "Resume",
                    "Endorsement Letter",
                    "Memorandum of Agreement",
                    "Medical Certificate",
                    "Company Orientation Form"
                ];
                break;

            case "ngo":
                $requirements = [
                    "Endorsement Letter",
                    "Memorandum of Agreement",
                    "Medical Certificate",
                    "Volunteer Agreement",
                    "NGO Orientation Form"
                ];
                break;

            default:
                die("Invalid OJT type.");
        }

        /*
         * Create the requirementsStatus array if it does not exist.
         */
        $intern["requirementsStatus"] = [];

        /*
         * Save the status of every requirement.
         */
        foreach ($requirements as $requirement) {

            $intern["requirementsStatus"][$requirement] =
                in_array($requirement, $completedRequirements);
        }

        break;
    }
}

unset($intern);

if (!$internFound) {
    die("Intern record not found.");
}

/*
 * Return to the Requirements Checker.
 */
header("Location: requirements.php");
exit;

?>