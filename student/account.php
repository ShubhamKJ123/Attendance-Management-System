<?php

ob_start();
session_start();

// Checking if the session is valid
if ($_SESSION['name'] != 'oasis') {
    header('location: ../login.php');
}

include('connect.php');

try {
    // Checking form data and empty fields
    if (isset($_POST['done'])) {
        $name = $_POST['name'];
        $dept = $_POST['dept'];
        $batch = $_POST['batch'];
        $semester = $_POST['semester'];
        $email = $_POST['email'];
        $sid = $_POST['id'];

        if (empty($name) || empty($dept) || empty($batch) || empty($email)) {
            throw new Exception("All fields are required.");
        }

        // Updating students' information using prepared statements to prevent SQL injection
        $stmt = $pdo->prepare("UPDATE students SET st_name=?, st_dept=?, st_batch=?, st_sem=?, st_email=? WHERE st_id=?");
        $stmt->execute([$name, $dept, $batch, $semester, $email, $sid]);

        $success_msg = 'Updated successfully';
    }
} catch (Exception $e) {
    $error_msg = $e->getMessage();
}

?>

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
</head>

<body>

<header>
    <h1>Online Attendance Management System 1.0</h1>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="attendance.php">Attendance</a>
        <!-- <a href="students.php">Students</a>
        <a href="report.php">My Report</a> -->
        <a href="account.php">My Account</a>
        <a href="../logout.php">Logout</a>
    </div>
</header>

<center>
    <div class="row">
        <div class="content">
            <h3>Update Account</h3>
            <br>
            <p>
                <?php
                if (isset($success_msg)) {
                    echo $success_msg;
                }
                if (isset($error_msg)) {
                    echo $error_msg;
                }
                ?>
            </p>
            <br>

            <form method="post" action="" class="form-horizontal col-md-6 col-md-offset-3">
                <div class="form-group">
                    <label for="input1" class="col-sm-3 control-label">Intern Id</label>
                    <div class="col-sm-7">
                        <input type="text" name="sr_id" class="form-control" id="input1"
                            placeholder="Enter your reg. no. to continue" />
                    </div>
                </div>
                <input type="submit" class="btn btn-primary col-md-3 col-md-offset-7" value="Go!" name="sr_btn" />
            </form>

            <?php
            if (isset($_POST['sr_btn'])) {
                // initializing student ID from form data
                $sr_id = $_POST['sr_id'];
                $i = 0;

                // search students' information respected to the particular ID
                $stmt = $pdo->prepare("SELECT * FROM students WHERE st_id = ?");
                $stmt->execute([$sr_id]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($data) {
            ?>

            <form action="" method="post" class="form-horizontal col-md-6 col-md-offset-3">
                <table class="table table-striped">
                    <tr>
                        <td>Intern Id:</td>
                        <td><?php echo $data['st_id']; ?></td>
                    </tr>
                    <tr>
                        <td>Student's Name:</td>
                        <td><input type="text" name="name"
                                value="<?php echo htmlspecialchars($data['st_name']); ?>"></td>
                    </tr>
                    <tr>
                        <td>Department:</td>
                        <td><input type="text" name="dept"
                                value="<?php echo htmlspecialchars($data['st_dept']); ?>"></td>
                    </tr>
                    <tr>
                        <td>Batch:</td>
                        <td><input type="text" name="batch"
                                value="<?php echo htmlspecialchars($data['st_batch']); ?>"></td>
                    </tr>
                    <tr>
                        <td>Semester:</td>
                        <td><input type="text" name="semester"
                                value="<?php echo htmlspecialchars($data['st_sem']); ?>"></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input type="text" name="email"
                                value="<?php echo htmlspecialchars($data['st_email']); ?>"></td>
                    </tr>
                    <input type="hidden" name="id" value="<?php echo $sr_id; ?>">
                    <tr>
                        <td></td>
                        <td><input type="submit" class="btn btn-primary col-md-3 col-md-offset-7" value="Update"
                                name="done"></td>
                    </tr>
                </table>
            </form>

            <?php
                } else {
                    echo "Student not found.";
                }
            }
            ?>
        </div>
    </div>
</center>

</body>

</html>
