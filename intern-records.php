<?php

session_start();

require_once 'classes/GovernmentOJT.php';
require_once 'classes/PrivateCompanyOJT.php';
require_once 'classes/NGOOJT.php';


// ==================================================
// LOAD SAVED INTERNS
// ==================================================

$interns = [];

if (isset($_SESSION["interns"])) {

    foreach ($_SESSION["interns"] as $savedIntern) {

        switch ($savedIntern["type"]) {

            case "government":

                $interns[] = new GovernmentOJT(
                    $savedIntern["name"],
                    $savedIntern["studentId"],
                    $savedIntern["company"],
                    $savedIntern["hoursRendered"],
                    $savedIntern["requiredHours"]
                );

                break;


            case "private":

                $interns[] = new PrivateCompanyOJT(
                    $savedIntern["name"],
                    $savedIntern["studentId"],
                    $savedIntern["company"],
                    $savedIntern["hoursRendered"],
                    $savedIntern["requiredHours"]
                );

                break;


            case "ngo":

                $interns[] = new NGOOJT(
                    $savedIntern["name"],
                    $savedIntern["studentId"],
                    $savedIntern["company"],
                    $savedIntern["hoursRendered"],
                    $savedIntern["requiredHours"]
                );

                break;
        }
    }
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

    <title>Intern Records - OJT Tracker</title>

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
            Intern Records
        </h1>

        <p>
            View and manage all registered interns and
            their current OJT information.
        </p>

    </section>



    <!-- ================================================= -->
    <!-- SUCCESS MESSAGES -->
    <!-- ================================================= -->

    <?php if (isset($_GET["updated"])): ?>

        <div class="success-message">

            ✓ Intern information updated successfully.

        </div>

    <?php endif; ?>


    <?php if (isset($_GET["deleted"])): ?>

        <div class="success-message">

            ✓ Intern record deleted successfully.

        </div>

    <?php endif; ?>



    <!-- ================================================= -->
    <!-- RECORDS -->
    <!-- ================================================= -->

    <section>


        <?php if (empty($interns)): ?>


            <!-- NO RECORDS -->

            <div class="card">

                <h3>
                    No Intern Records
                </h3>

                <p>
                    There are currently no registered interns.
                </p>

                <br>


                <a
                    href="add-intern.php"
                    class="submit-button"
                    style="
                        display: inline-block;
                        text-decoration: none;
                        text-align: center;
                        width: auto;
                    "
                >
                    Add Your First Intern
                </a>

            </div>


        <?php else: ?>


            <?php foreach ($interns as $intern): ?>


                <?php

                $progress =
                    $intern->getProgress();

                $objectType =
                    get_class($intern);

                ?>


                <!-- ================================= -->
                <!-- INTERN RECORD CARD -->
                <!-- ================================= -->

                <div class="card record-card">


                    <!-- HEADER -->

                    <div class="record-header">

                        <div>

                            <h3>

                                <?php

                                echo htmlspecialchars(
                                    $intern->getName()
                                );

                                ?>

                            </h3>


                            <p class="record-company">

                                <?php

                                echo htmlspecialchars(
                                    $intern->getCompany()
                                );

                                ?>

                            </p>

                        </div>


                        <!-- OJT TYPE -->

                        <span class="ojt-badge">

                            <?php

                            if (
                                $objectType ===
                                "GovernmentOJT"
                            ) {

                                echo "Government OJT";

                            } elseif (
                                $objectType ===
                                "PrivateCompanyOJT"
                            ) {

                                echo "Private Company OJT";

                            } else {

                                echo "NGO OJT";

                            }

                            ?>

                        </span>

                    </div>



                    <!-- ================================= -->
                    <!-- INFORMATION -->
                    <!-- ================================= -->

                    <div class="record-info">


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



                        <!-- HOURS -->

                        <p>

                            <strong>
                                OJT Hours:
                            </strong>

                            <br>

                            <?php

                            echo $intern->getHoursRendered();

                            ?>

                            /

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
                                $progress,
                                1
                            );

                            ?>%

                        </p>



                        <!-- REQUIREMENTS -->

                        <p>

                            <strong>
                                Requirements:
                            </strong>

                            <br>

                            <?php

                            echo count(
                                $intern->getRequirements()
                            );

                            ?>

                            required documents

                        </p>


                    </div>



                    <!-- ================================= -->
                    <!-- OJT DESCRIPTION -->
                    <!-- ================================= -->

                    <div class="requirements-section">

                        <h4>
                            OJT Description
                        </h4>

                        <p class="record-company">

                            <?php

                            echo htmlspecialchars(
                                $intern->getOJTDescription()
                            );

                            ?>

                        </p>

                    </div>



                    <!-- ================================= -->
                    <!-- PROGRESS BAR -->
                    <!-- ================================= -->

                    <div class="progress-container">


                        <div class="progress-label">

                            <strong>
                                OJT Completion
                            </strong>


                            <span>

                                <?php

                                echo number_format(
                                    $progress,
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
                                        $progress,
                                        100
                                    );

                                ?>%;"
                            ></div>

                        </div>


                        <p class="progress-status">

                            <?php

                            if ($progress >= 100) {

                                echo "Completed";

                            } else {

                                echo "In Progress";

                            }

                            ?>

                        </p>


                    </div>



                    <!-- ================================= -->
                    <!-- ACTION BUTTONS -->
                    <!-- ================================= -->

                    <div class="record-actions">


                        <!-- EDIT -->

                        <a
                            href="edit-intern.php?studentId=<?php

                                echo urlencode(
                                    $intern->getStudentId()
                                );

                            ?>"
                            class="action-button edit-button"
                        >
                            ✏ Edit Intern
                        </a>



                        <!-- DELETE -->

                        <form
                            action="delete-intern.php"
                            method="POST"
                            onsubmit="return confirm(
                                'Are you sure you want to delete this intern record?'
                            );"
                        >

                            <input
                                type="hidden"
                                name="studentId"
                                value="<?php

                                echo htmlspecialchars(
                                    $intern->getStudentId()
                                );

                                ?>"
                            >


                            <button
                                type="submit"
                                class="action-button delete-button"
                            >
                                🗑 Delete
                            </button>

                        </form>


                    </div>


                </div>


            <?php endforeach; ?>


        <?php endif; ?>


    </section>


</main>


</body>

</html>