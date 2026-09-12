<?php

require_once 'classes/GovernmentOJT.php';
require_once 'classes/PrivateCompanyOJT.php';
require_once 'classes/NGOOJT.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Intern - OJT Tracker</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!-- NAVIGATION -->

    <nav class="navbar">

        <div class="container navbar-content">

            <div class="logo">
                OJT TRACKER
            </div>

            <ul class="nav-links">

                <li>
                    <a href="index.php">Dashboard</a>
                </li>

                <li>
                    <a href="add-intern.php">Add Intern</a>
                </li>

            </ul>

        </div>

    </nav>


    <!-- MAIN CONTENT -->

    <main class="container">

        <section class="hero">

            <h1>
                Add New Intern
            </h1>

            <p>
                Enter the intern's information to create an OJT record.
            </p>

        </section>


        <!-- FORM -->

        <section class="card">

            <form action="process-intern.php" method="POST">

                <!-- FULL NAME -->

                <div class="form-group">

                    <label for="name">
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter full name"
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
                        placeholder="Example: 2026-004"
                        required
                    >

                </div>


                <!-- ORGANIZATION -->

                <div class="form-group">

                    <label for="company">
                        Organization / Company
                    </label>

                    <input
                        type="text"
                        id="company"
                        name="company"
                        placeholder="Enter organization or company"
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

                        <option value="">
                            Select OJT Type
                        </option>

                        <option value="government">
                            Government OJT
                        </option>

                        <option value="private">
                            Private Company OJT
                        </option>

                        <option value="ngo">
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
                        placeholder="Example: 300"
                        min="0"
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
                        placeholder="Example: 600"
                        min="1"
                        required
                    >

                </div>


                <!-- BUTTON -->

                <button
                    type="submit"
                    class="submit-button"
                >
                    Add Intern
                </button>

            </form>

        </section>

    </main>

</body>

</html>