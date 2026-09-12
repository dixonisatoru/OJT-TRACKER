<?php

session_start();

require_once 'classes/GovernmentOJT.php';
require_once 'classes/PrivateCompanyOJT.php';
require_once 'classes/NGOOJT.php';


// ==================================================
// CHECK SESSION RECORDS
// ==================================================

$hasInterns =
    isset($_SESSION["interns"]) &&
    !empty($_SESSION["interns"]);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Requirements - OJT Tracker
    </title>

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
            OJT Requirements Checker
        </h1>

        <p>
            Mark the required documents that have already
            been completed for each intern.
        </p>

    </section>



    <!-- ================================================= -->
    <!-- REQUIREMENT RECORDS -->
    <!-- ================================================= -->

    <section>


        <?php if (!$hasInterns): ?>


            <!-- ========================================= -->
            <!-- NO RECORDS -->
            <!-- ========================================= -->

            <div class="card">

                <h3>
                    No Intern Records
                </h3>

                <p>
                    Add an intern first before checking
                    requirements.
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
                    Add Intern
                </a>

            </div>


        <?php else: ?>


            <!-- ========================================= -->
            <!-- LOOP THROUGH SESSION RECORDS -->
            <!-- ========================================= -->

            <?php foreach (
                $_SESSION["interns"]
                as $savedIntern
            ): ?>


                <?php

                // ------------------------------------------
                // CREATE THE CORRECT OJT OBJECT
                // ------------------------------------------

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

                        continue 2;

                }


                // ------------------------------------------
                // GET POLYMORPHIC REQUIREMENTS
                // ------------------------------------------

                $requirements =
                    $intern->getRequirements();


                // ------------------------------------------
                // GET SAVED REQUIREMENT STATUS
                // ------------------------------------------

                $savedStatus = [];

                if (
                    isset(
                        $savedIntern["requirementsStatus"]
                    )
                ) {

                    $savedStatus =
                        $savedIntern["requirementsStatus"];

                }


                // ------------------------------------------
                // MAKE SURE EVERY REQUIREMENT HAS A STATUS
                // ------------------------------------------

                foreach ($requirements as $requirement) {

                    if (
                        !isset(
                            $savedStatus[$requirement]
                        )
                    ) {

                        $savedStatus[$requirement] = false;

                    }

                }


                // ------------------------------------------
                // COUNT COMPLETED REQUIREMENTS
                // ------------------------------------------

                $completedCount = 0;

                foreach ($requirements as $requirement) {

                    if (
                        isset(
                            $savedStatus[$requirement]
                        ) &&
                        $savedStatus[$requirement] === true
                    ) {

                        $completedCount++;

                    }

                }


                // ------------------------------------------
                // OBJECT TYPE
                // ------------------------------------------

                $objectType =
                    get_class($intern);

                ?>



                <!-- ========================================= -->
                <!-- INTERN CARD -->
                <!-- ========================================= -->

                <div class="card record-card">


                    <!-- ===================================== -->
                    <!-- HEADER -->
                    <!-- ===================================== -->

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

                                Student ID:

                                <?php

                                echo htmlspecialchars(
                                    $intern->getStudentId()
                                );

                                ?>

                                <br>

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



                    <!-- ===================================== -->
                    <!-- REQUIREMENT PROGRESS -->
                    <!-- ===================================== -->

                    <div class="requirements-progress">

                        <strong>
                            Requirements Progress
                        </strong>


                        <span>

                            <?php

                            echo $completedCount;

                            ?>

                            /

                            <?php

                            echo count($requirements);

                            ?>

                            Completed

                        </span>

                    </div>



                    <!-- ===================================== -->
                    <!-- CHECKLIST FORM -->
                    <!-- ===================================== -->

                    <form
                        action="update-requirements.php"
                        method="POST"
                    >


                        <!-- STUDENT ID -->

                        <input
                            type="hidden"
                            name="studentId"
                            value="<?php

                            echo htmlspecialchars(
                                $intern->getStudentId()
                            );

                            ?>"
                        >



                        <!-- REQUIREMENTS -->

                        <div class="requirements-checklist">


                            <?php foreach (
                                $requirements
                                as $requirement
                            ): ?>


                                <?php

                                $isCompleted =
                                    isset(
                                        $savedStatus[$requirement]
                                    ) &&
                                    $savedStatus[$requirement] === true;

                                ?>


                                <label
                                    class="requirement-item"
                                >


                                    <!-- CHECKBOX -->

                                    <input
                                        type="checkbox"
                                        name="requirements[]"
                                        value="<?php

                                        echo htmlspecialchars(
                                            $requirement
                                        );

                                        ?>"
                                        <?php

                                        if ($isCompleted) {

                                            echo "checked";

                                        }

                                        ?>
                                    >



                                    <!-- STATUS ICON -->

                                    <span
                                        class="requirement-check"
                                    >

                                        <?php

                                        echo $isCompleted
                                            ? "✓"
                                            : "○";

                                        ?>

                                    </span>



                                    <!-- REQUIREMENT NAME -->

                                    <span>

                                        <?php

                                        echo htmlspecialchars(
                                            $requirement
                                        );

                                        ?>

                                    </span>


                                </label>


                            <?php endforeach; ?>


                        </div>



                        <!-- ================================= -->
                        <!-- SAVE -->
                        <!-- ================================= -->

                        <button
                            type="submit"
                            class="submit-button"
                            style="margin-top: 20px;"
                        >
                            Save Requirement Status
                        </button>


                    </form>


                </div>


            <?php endforeach; ?>


        <?php endif; ?>


    </section>


</main>


</body>

</html>