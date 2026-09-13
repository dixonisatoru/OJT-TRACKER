<?php

session_start();

require_once 'classes/GovernmentOJT.php';
require_once 'classes/PrivateCompanyOJT.php';
require_once 'classes/NGOOJT.php';


// ==================================================
// MAKE SURE THE FORM WAS SUBMITTED USING POST
// ==================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: add-intern.php");
    exit;

}


// ==================================================
// GET FORM DATA
// ==================================================

$name = trim($_POST["name"] ?? "");
$studentId = trim($_POST["studentId"] ?? "");
$company = trim($_POST["company"] ?? "");
$ojtType = $_POST["ojtType"] ?? "";
$hoursRendered = $_POST["hoursRendered"] ?? "";
$requiredHours = $_POST["requiredHours"] ?? "";


// ==================================================
// VALIDATE REQUIRED FIELDS
// ==================================================

if (
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
// CONVERT HOURS INTO INTEGERS
// ==================================================

$hoursRendered = (int) $hoursRendered;
$requiredHours = (int) $requiredHours;


// ==================================================
// CREATE THE CORRECT OJT OBJECT
//
// Hours validation now also happens inside the private
// validateHours() method of the Intern class itself, so
// object creation is wrapped in a try/catch. This is the
// encapsulation boundary: the class protects its own
// invariants instead of trusting the caller blindly.
// ==================================================

try {

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


        default:

            die("Invalid OJT type selected.");

    }

} catch (InvalidArgumentException $e) {

    die($e->getMessage());

}


// ==================================================
// GET REQUIREMENTS FOR THE CREATED OOP OBJECT
// ==================================================

$requirements = $intern->getRequirements();


// ==================================================
// CREATE INITIAL REQUIREMENT STATUS
// ==================================================

$requirementsStatus = [];

foreach ($requirements as $requirement) {

    $requirementsStatus[$requirement] = false;

}


// ==================================================
// CREATE SESSION STORAGE IF IT DOES NOT EXIST
// ==================================================

if (!isset($_SESSION["interns"])) {

    $_SESSION["interns"] = [];

}


// ==================================================
// PREVENT DUPLICATE STUDENT ID
// ==================================================

foreach ($_SESSION["interns"] as $existingIntern) {

    if ($existingIntern["studentId"] === $studentId) {

        die("An intern with this Student ID already exists.");

    }

}


// ==================================================
// STORE INTERN INFORMATION IN SESSION
// ==================================================

$_SESSION["interns"][] = [

    "type" => $ojtType,

    "name" => $intern->getName(),

    "studentId" => $intern->getStudentId(),

    "company" => $intern->getCompany(),

    "hoursRendered" => $intern->getHoursRendered(),

    "requiredHours" => $intern->getRequiredHours(),

    "requirementsStatus" => $requirementsStatus

];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Intern Created - OJT Tracker</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>

<body>


<!-- ================================================= -->
<!-- NAVIGATION -->
<!-- ================================================= -->

<nav class="navbar">

    <div class="container navbar-content">

        <div class="logo">
            OJT TRACKER
        </div>


        <ul class="nav-links">

            <li>
                <a href="index.php">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="add-intern.php">
                    Add Intern
                </a>
            </li>

            <li>
                <a href="intern-records.php">
                    Intern Records
                </a>
            </li>

            <li>
                <a href="requirements.php">
                    Requirements
                </a>
            </li>

        </ul>

    </div>

</nav>


<!-- ================================================= -->
<!-- MAIN CONTENT -->
<!-- ================================================= -->

<main class="container">


    <!-- ================================================= -->
    <!-- HERO -->
    <!-- ================================================= -->

    <section class="hero">

        <h1>
            Intern Successfully Created
        </h1>

        <p>
            The system created the appropriate OJT object
            and initialized all requirements as pending.
        </p>

    </section>


    <!-- ================================================= -->
    <!-- RESULT CARD -->
    <!-- ================================================= -->

    <section class="card">

        <h2>
            Intern Information
        </h2>


        <div class="record-info">


            <!-- FULL NAME -->

            <p>

                <strong>
                    Full Name:
                </strong>

                <br>

                <?php

                echo htmlspecialchars(
                    $intern->getName()
                );

                ?>

            </p>


            <!-- STUDENT ID -->

            <p>

                <strong>
                    Student ID:
                </strong>

                <br>

                <?php

                echo htmlspecialchars(
                    $intern->getStudentId()
                );

                ?>

            </p>


            <!-- ORGANIZATION -->

            <p>

                <strong>
                    Organization:
                </strong>

                <br>

                <?php

                echo htmlspecialchars(
                    $intern->getCompany()
                );

                ?>

            </p>


            <!-- HOURS RENDERED -->

            <p>

                <strong>
                    Hours Rendered:
                </strong>

                <br>

                <?php

                echo $intern->getHoursRendered();

                ?>

                hours

            </p>


            <!-- REQUIRED HOURS -->

            <p>

                <strong>
                    Required Hours:
                </strong>

                <br>

                <?php

                echo $intern->getRequiredHours();

                ?>

                hours

            </p>


            <!-- PROGRESS -->

            <p>

                <strong>
                    Progress:
                </strong>

                <br>

                <?php

                echo number_format(
                    $intern->getProgress(),
                    1
                );

                ?>

                %

            </p>


            <!-- OJT DESCRIPTION -->

            <p>

                <strong>
                    OJT Description:
                </strong>

                <br>

                <?php

                echo htmlspecialchars(
                    $intern->getOJTDescription()
                );

                ?>

            </p>


        </div>


        <!-- ================================================= -->
        <!-- OOP TYPE (now via polymorphic method, not get_class) -->
        <!-- ================================================= -->

        <div class="progress-container">

            <p>

                <strong>
                    Created Object Type:
                </strong>

                <br>

                <?php

                echo htmlspecialchars(
                    $intern->getOJTTypeLabel()
                );

                ?>

                (<?php echo htmlspecialchars(get_class($intern)); ?>)

            </p>

        </div>


        <!-- ================================================= -->
        <!-- REQUIREMENT STATUS -->
        <!-- ================================================= -->

        <div class="requirements-section">

            <h4>
                Initial Requirement Status
            </h4>


            <ul class="requirements-list">


                <?php foreach (
                    $requirementsStatus
                    as $requirement => $completed
                ): ?>


                    <li>

                        <span class="requirement-check">
                            ○
                        </span>

                        <?php

                        echo htmlspecialchars(
                            $requirement
                        );

                        ?>

                        - Pending

                    </li>


                <?php endforeach; ?>


            </ul>

        </div>

    </section>


    <!-- ================================================= -->
    <!-- ACTIONS -->
    <!-- ================================================= -->

    <section class="hero">


        <a
            href="requirements.php"
            class="submit-button"
            style="
                display: inline-block;
                text-decoration: none;
                text-align: center;
                width: auto;
            "
        >
            Check Requirements
        </a>


        <a
            href="index.php"
            class="submit-button"
            style="
                display: inline-block;
                text-decoration: none;
                text-align: center;
                width: auto;
                margin-left: 10px;
            "
        >
            Back to Dashboard
        </a>


    </section>


</main>


</body>

</html>
