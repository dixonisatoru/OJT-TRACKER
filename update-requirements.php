<?php

session_start();

require_once 'classes/GovernmentOJT.php';
require_once 'classes/PrivateCompanyOJT.php';
require_once 'classes/NGOOJT.php';

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

foreach ($_SESSION["interns"] as &$savedIntern) {

    if ($savedIntern["studentId"] === $studentId) {

        $internFound = true;

        /*
         * Recreate the correct OJT subclass object so that
         * we can call the polymorphic getRequirements()
         * method, instead of duplicating each subclass's
         * requirement list here with a switch statement.
         * If a requirement list ever changes in a subclass
         * file, this code automatically stays correct.
         */
        switch ($savedIntern["type"]) {

            case "government":
                $intern = new GovernmentOJT(
                    $savedIntern["name"],
                    $savedIntern["studentId"],
                    $savedIntern["company"],
                    $savedIntern["hoursRendered"],
                    $savedIntern["requiredHours"]
                );
                break;

            case "private":
                $intern = new PrivateCompanyOJT(
                    $savedIntern["name"],
                    $savedIntern["studentId"],
                    $savedIntern["company"],
                    $savedIntern["hoursRendered"],
                    $savedIntern["requiredHours"]
                );
                break;

            case "ngo":
                $intern = new NGOOJT(
                    $savedIntern["name"],
                    $savedIntern["studentId"],
                    $savedIntern["company"],
                    $savedIntern["hoursRendered"],
                    $savedIntern["requiredHours"]
                );
                break;

            default:
                die("Invalid OJT type.");
        }

        /*
         * Polymorphic call: the same method call produces
         * a different requirement list depending on which
         * subclass $intern actually is.
         */
        $requirements = $intern->getRequirements();

        $savedIntern["requirementsStatus"] = [];

        foreach ($requirements as $requirement) {

            $savedIntern["requirementsStatus"][$requirement] =
                in_array($requirement, $completedRequirements);

        }

        break;
    }
}

unset($savedIntern);

if (!$internFound) {
    die("Intern record not found.");
}

/*
 * Return to the Requirements Checker.
 */
header("Location: requirements.php");
exit;

?>
