<?php

session_start();

require_once 'classes/GovernmentOJT.php';
require_once 'classes/PrivateCompanyOJT.php';
require_once 'classes/NGOOJT.php';


// ==================================================
// GET STUDENT ID
// ==================================================

$studentId = trim($_GET["studentId"] ?? "");


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
// FIND INTERN
// ==================================================

$internData = null;

foreach ($_SESSION["interns"] as $savedIntern) {

    if ($savedIntern["studentId"] === $studentId) {

        $internData = $savedIntern;

        break;

    }

}


// ==================================================
// CHECK IF INTERN EXISTS
// ==================================================

if ($internData === null) {

    die("Intern record not found.");

}


// ==================================================
// CREATE OBJECT
// ==================================================

switch ($internData["type"]) {

    case "government":

        $intern = new GovernmentOJT(
            $internData["name"],
            $internData["studentId"],
            $internData["company"],
            $internData["hoursRendered"],
            $internData["requiredHours"]
        );

        break;


    case "private":

        $intern = new PrivateCompanyOJT(
            $internData["name"],
            $internData["studentId"],
            $internData["company"],
            $internData["hoursRendered"],
            $internData["requiredHours"]
        );

        break;


    case "ngo":

        $intern = new NGOOJT(
            $internData["name"],
            $internData["studentId"],
            $internData["company"],
            $internData["hoursRendered"],
            $internData["requiredHours"]
        );

        break;


    default:

        die("Invalid OJT type.");

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Intern - OJT Tracker</title>

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


    <!-- HERO -->

    <section class="hero">

        <h1>
            Edit Intern Information
        </h1>

        <p>
            Update the internship information for this intern.
            Current type: <strong><?php

                echo htmlspecialchars(
                    $intern->getOJTTypeLabel()
                );

            ?></strong>
        </p>

    </section>



    <!-- ================================================= -->
    <!-- EDIT FORM -->
    <!-- ================================================= -->

    <section class="card">

        <form
            action="update-intern.php"
            method="POST"
        >


            <!-- ORIGINAL STUDENT ID -->

            <input
                type="hidden"
                name="originalStudentId"
                value="<?php

                echo htmlspecialchars(
                    $intern->getStudentId()
                );

                ?>"
            >


            <!-- FULL NAME -->

            <div class="form-group">

                <label for="name">
                    Full Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?php

                    echo htmlspecialchars(
                        $intern->getName()
                    );

                    ?>"
                    required
                >

            </div>



            <!-- STUDENT ID -->

            <div class="form-group">

                <label for="studentId">
                    Student ID
                </label>

                <input
                    type="text"
                    id="studentId"
                    name="studentId"
                    value="<?php

                    echo htmlspecialchars(
                        $intern->getStudentId()
                    );

                    ?>"
                    required
                >

            </div>



            <!-- COMPANY -->

            <div class="form-group">

                <label for="company">
                    Organization / Company
                </label>

                <input
                    type="text"
                    id="company"
                    name="company"
                    value="<?php

                    echo htmlspecialchars(
                        $intern->getCompany()
                    );

                    ?>"
                    required
                >

            </div>



            <!-- OJT TYPE -->

            <div class="form-group">

                <label for="ojtType">
                    OJT Type
                </label>

                <select
                    id="ojtType"
                    name="ojtType"
                    required
                >

                    <option
                        value="government"
                        <?php

                        echo $internData["type"] === "government"
                            ? "selected"
                            : "";

                        ?>
                    >
                        Government OJT
                    </option>


                    <option
                        value="private"
                        <?php

                        echo $internData["type"] === "private"
                            ? "selected"
                            : "";

                        ?>
                    >
                        Private Company OJT
                    </option>


                    <option
                        value="ngo"
                        <?php

                        echo $internData["type"] === "ngo"
                            ? "selected"
                            : "";

                        ?>
                    >
                        NGO OJT
                    </option>

                </select>

            </div>



            <!-- HOURS RENDERED -->

            <div class="form-group">

                <label for="hoursRendered">
                    Hours Rendered
                </label>

                <input
                    type="number"
                    id="hoursRendered"
                    name="hoursRendered"
                    value="<?php

                    echo $intern->getHoursRendered();

                    ?>"
                    min="0"
                    max="<?php

                    echo $intern->getRequiredHours();

                    ?>"
                    required
                >

            </div>



            <!-- REQUIRED HOURS -->

            <div class="form-group">

                <label for="requiredHours">
                    Required Hours
                </label>

                <input
                    type="number"
                    id="requiredHours"
                    name="requiredHours"
                    value="<?php

                    echo $intern->getRequiredHours();

                    ?>"
                    min="1"
                    required
                >

            </div>



            <!-- CURRENT PROGRESS -->

            <div class="progress-container">

                <div class="progress-label">

                    <strong>
                        Current Progress
                    </strong>

                    <span>

                        <?php

                        echo number_format(
                            $intern->getProgress(),
                            1
                        );

                        ?>%

                    </span>

                </div>


                <div class="progress-bar">

                    <div
                        class="progress-fill"
                        style="width: <?php

                            echo min(
                                $intern->getProgress(),
                                100
                            );

                        ?>%;"
                    ></div>

                </div>

            </div>



            <!-- BUTTONS -->

            <div
                style="
                    margin-top: 25px;
                    display: flex;
                    gap: 10px;
                "
            >

                <button
                    type="submit"
                    class="submit-button"
                >
                    Save Changes
                </button>


                <a
                    href="intern-records.php"
                    class="submit-button"
                    style="
                        text-decoration: none;
                        text-align: center;
                        background: #6b7280;
                    "
                >
                    Cancel
                </a>

            </div>


        </form>

    </section>


</main>


</body>

</html>
