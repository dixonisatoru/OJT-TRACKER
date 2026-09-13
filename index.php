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


// ==================================================
// DASHBOARD STATISTICS
// ==================================================

$totalInterns = count($interns);

$totalHours = 0;

$totalRequiredHours = 0;

$completedOJT = 0;

$inProgressOJT = 0;


foreach ($interns as $intern) {

    $totalHours +=
        $intern->getHoursRendered();

    $totalRequiredHours +=
        $intern->getRequiredHours();


    if ($intern->getProgress() >= 100) {

        $completedOJT++;

    } else {

        $inProgressOJT++;
    }
}


// ==================================================
// OVERALL PROGRESS
// ==================================================

if ($totalRequiredHours > 0) {

    $overallProgress =
        ($totalHours / $totalRequiredHours) * 100;

} else {

    $overallProgress = 0;
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

    <title>Dashboard - OJT Tracker</title>

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
            OJT Management Dashboard
        </h1>

        <p>
            Monitor internship hours, completion status,
            and requirements in one place.
        </p>

    </section>



    <!-- ================================================= -->
    <!-- STATISTICS -->
    <!-- ================================================= -->

    <section class="dashboard-stats">


        <!-- TOTAL INTERNS -->

        <div class="card dashboard-stat-card">

            <div class="stat-icon">
                👥
            </div>

            <div>

                <h3>
                    Total Interns
                </h3>

                <div class="stat-value">

                    <?php

                    echo $totalInterns;

                    ?>

                </div>

            </div>

        </div>



        <!-- TOTAL HOURS -->

        <div class="card dashboard-stat-card">

            <div class="stat-icon">
                ⏱️
            </div>

            <div>

                <h3>
                    Total Hours
                </h3>

                <div class="stat-value">

                    <?php

                    echo $totalHours;

                    ?>

                </div>

            </div>

        </div>



        <!-- COMPLETED OJT -->

        <div class="card dashboard-stat-card">

            <div class="stat-icon">
                ✅
            </div>

            <div>

                <h3>
                    Completed OJT
                </h3>

                <div class="stat-value">

                    <?php

                    echo $completedOJT;

                    ?>

                </div>

            </div>

        </div>



        <!-- IN PROGRESS -->

        <div class="card dashboard-stat-card">

            <div class="stat-icon">
                🔄
            </div>

            <div>

                <h3>
                    In Progress
                </h3>

                <div class="stat-value">

                    <?php

                    echo $inProgressOJT;

                    ?>

                </div>

            </div>

        </div>



        <!-- OVERALL PROGRESS -->

        <div class="card dashboard-stat-card">

            <div class="stat-icon">
                📊
            </div>

            <div>

                <h3>
                    Overall Progress
                </h3>

                <div class="stat-value">

                    <?php

                    echo number_format(
                        min($overallProgress, 100),
                        1
                    );

                    ?>%

                </div>

            </div>

        </div>


    </section>



    <!-- ================================================= -->
    <!-- OVERALL PROGRESS -->
    <!-- ================================================= -->

    <section class="card dashboard-progress-card">


        <div class="records-header">

            <h2>
                Overall OJT Progress
            </h2>

            <p>
                Combined progress of all registered interns.
            </p>

        </div>


        <?php if ($totalInterns > 0): ?>


            <div class="progress-label">

                <strong>
                    Total Hours Rendered
                </strong>

                <span>

                    <?php

                    echo $totalHours;

                    ?>

                    /

                    <?php

                    echo $totalRequiredHours;

                    ?>

                    hours

                </span>

            </div>


            <div class="progress-bar">

                <div
                    class="progress-fill"
                    style="width: <?php

                        echo min(
                            $overallProgress,
                            100
                        );

                    ?>%;"
                ></div>

            </div>


            <p class="progress-status">

                <?php

                echo number_format(
                    min($overallProgress, 100),
                    1
                );

                ?>% overall completion

            </p>


        <?php else: ?>


            <p class="progress-status">

                No intern records available yet.

                <br><br>

                Add an intern to start tracking OJT progress.

            </p>


            <a
                href="add-intern.php"
                class="submit-button"
                style="
                    display: inline-block;
                    text-decoration: none;
                    text-align: center;
                    width: auto;
                    margin-top: 10px;
                "
            >
                Add First Intern
            </a>


        <?php endif; ?>


    </section>



    <!-- ================================================= -->
    <!-- OJT RECORDS -->
    <!-- ================================================= -->

    <section>


        <div class="records-header">

            <h2>
                OJT Records
            </h2>

            <p>
                Current internship records and progress.
            </p>

        </div>



        <?php if (empty($interns)): ?>


            <div class="card">

                <h3>
                    No Intern Records
                </h3>

                <p>
                    There are currently no registered interns.
                </p>

            </div>


        <?php else: ?>


            <?php foreach ($interns as $index => $intern): ?>


                <?php

                $progress =
                    $intern->getProgress();

                $requirements =
                    $intern->getRequirements();


                /*
                 * Get the saved requirement status
                 * for this specific intern.
                 */
                $savedStatus = [];

                if (
                    isset(
                        $_SESSION["interns"][$index]["requirementsStatus"]
                    )
                ) {

                    $savedStatus =
                        $_SESSION["interns"][$index]["requirementsStatus"];

                }

                ?>


                <!-- ================================= -->
                <!-- INTERN CARD -->
                <!-- ================================= -->

                <div class="card record-card">


                    <!-- RECORD HEADER -->

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


                        <!--
                            OJT TYPE
                            Uses the polymorphic getOJTTypeLabel()
                            method instead of checking get_class()
                            manually. Calling the same method on
                            different subclass objects produces a
                            different label automatically.
                        -->

                        <span class="ojt-badge">

                            <?php

                            echo htmlspecialchars(
                                $intern->getOJTTypeLabel()
                            );

                            ?>

                        </span>

                    </div>



                    <!-- RECORD INFORMATION -->

                    <div class="record-info">


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



                        <p>

                            <strong>
                                Hours:
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



                        <p>

                            <strong>
                                Requirements:
                            </strong>

                            <br>

                            <?php

                            echo count($requirements);

                            ?>

                            required documents

                        </p>


                    </div>



                    <!-- OJT DESCRIPTION -->

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



                    <!-- REQUIREMENTS -->

                    <div class="requirements-section">

                        <h4>
                            OJT Requirements
                        </h4>


                        <ul class="requirements-list">


                            <?php foreach (
                                $requirements
                                as $requirement
                            ): ?>


                                <?php

                                /*
                                 * Check the saved status
                                 * of this requirement.
                                 */
                                $isCompleted =
                                    isset(
                                        $savedStatus[$requirement]
                                    )
                                    &&
                                    $savedStatus[$requirement] === true;

                                ?>


                                <li>

                                    <span
                                        class="requirement-check"
                                    >

                                        <?php

                                        if ($isCompleted) {

                                            echo "✓";

                                        } else {

                                            echo "○";

                                        }

                                        ?>

                                    </span>

                                    <?php

                                    echo htmlspecialchars(
                                        $requirement
                                    );

                                    ?>

                                </li>


                            <?php endforeach; ?>


                        </ul>

                    </div>



                    <!-- OJT PROGRESS -->

                    <div class="progress-container">


                        <div class="progress-label">

                            <strong>
                                OJT Progress
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



                    <!-- UPDATE HOURS -->

                    <div class="requirements-section">

                        <h4>
                            Update OJT Hours
                        </h4>


                        <form
                            action="update-hours.php"
                            method="POST"
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


                            <div class="form-group">

                                <label
                                    for="hours-<?php

                                        echo htmlspecialchars(
                                            $intern->getStudentId()
                                        );

                                    ?>"
                                >
                                    Additional Hours
                                </label>


                                <input
                                    type="number"
                                    id="hours-<?php

                                        echo htmlspecialchars(
                                            $intern->getStudentId()
                                        );

                                    ?>"
                                    name="additionalHours"
                                    placeholder="Example: 40"
                                    min="0"
                                    max="<?php

                                        echo max(
                                            0,
                                            $intern->getRequiredHours()
                                            -
                                            $intern->getHoursRendered()
                                        );

                                    ?>"
                                    required
                                >

                            </div>


                            <button
                                type="submit"
                                class="submit-button"
                            >
                                Add Hours
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
