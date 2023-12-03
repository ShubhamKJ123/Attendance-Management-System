<!DOCTYPE html>
<html lang="en">

<head>
    <title>Online Attendance Management System 1.0</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>

<body>
    <header>
        <h1>Online Attendance Management System 1.0</h1>
        <div class="navbar">
        <a href="signup.php">Create Users</a>
            <a href="index.php">Add Data</a>
            <a href="report.php">Attendance Report</a>
            <a href="../logout.php">Logout</a>
        </div>
    </header>

    <main>
        <div class="row">
            <div class="content">
                <h3>Student Report</h3>
                <br>
                <form method="post" action="" class="form-horizontal col-md-6 col-md-offset-3">
                    <div class="form-group">
                        <label for="input1" class="col-sm-3 control-label">Select Subject</label>
                        <div class="col-sm-4">
                            <select name="whichcourse" id="input1">
                                <option value="algo">Analysis of Algorithms</option>
                                <option value="algolab">Analysis of Algorithms Lab</option>
                                <option value="dbms">Database Management System</option>
                                <option value="dbmslab">Database Management System Lab</option>
                                <option value="weblab">Web Programming Lab</option>
                                <option value="os">Operating System</option>
                                <option value="oslab">Operating System Lab</option>
                                <option value="obm">Object Based Modeling</option>
                                <option value="softcomp">Soft Computing</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input1" class="col-sm-3 control-label">Your Intern  Id</label>
                        <div class="col-sm-7">
                            <input type="text" name="sr_id" class="form-control" id="input1" placeholder="enter your reg. no." />
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary col-md-3 col-md-offset-7" value="Go!" name="sr_btn" />
                </form>
                <div class="content"><br></div>
            </div>
        </div>
    </main>
</body>

</html>

<?php

ob_start();
session_start();

if ($_SESSION['name'] != 'oasis') {
    header('location: ../index.php');
}

include('connect.php');

if (isset($_POST['sr_btn'])) {
    $sr_id = $_POST['sr_id'];
    $course = $_POST['whichcourse'];

    $i = 0;

    // Create a connection to the database
    $mysqli = new mysqli("localhost", "root", "", "attsystem");

    // Check the connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Query for counting the number of present entries
    $all_query = $mysqli->query("SELECT YEAR(stat_date) AS year, MONTH(stat_date) AS month, COUNT(*) AS countP FROM attendance WHERE stat_id='$sr_id' AND course='$course' AND st_status='Present' GROUP BY YEAR(stat_date), MONTH(stat_date)");
    $singleT_query = $mysqli->query("SELECT DISTINCT YEAR(stat_date) AS year, MONTH(stat_date) AS month FROM attendance WHERE stat_id='$sr_id' AND course='$course'");

    // Fetching the total count
    $count_tot = mysqli_num_rows($singleT_query);

    ?>
    <footer>
        <form method="post" action="" class="form-horizontal col-md-6 col-md-offset-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Month</th>
                        <th>Total Class (Days)</th>
                        <th>Present (Days)</th>
                        <th>Absent (Days)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($data = $all_query->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $data['year']; ?></td>
                            <td><?php echo date("F", mktime(0, 0, 0, $data['month'], 1)); ?></td>
                            <td><?php echo cal_days_in_month(CAL_GREGORIAN, $data['month'], $data['year']) - getWeekendCount($data['year'], $data['month']); ?></td>
                            <td><?php echo $data['countP']; ?> </td>
                            <td><?php echo cal_days_in_month(CAL_GREGORIAN, $data['month'], $data['year']) - getWeekendCount($data['year'], $data['month']) - $data['countP']; ?> </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </form>
    </footer>
    <?php

    $mysqli->close();
}

function getWeekendCount($year, $month) {
    $weekendCount = 0;
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $currentDay = date("N", strtotime("$year-$month-$day"));
        if ($currentDay == 6 || $currentDay == 7) {
            $weekendCount++;
        }
    }

    return $weekendCount;
}

?>

