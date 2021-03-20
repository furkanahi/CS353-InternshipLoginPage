<?php
include("initial.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $given_cid = $_POST['given_cid'];
    $student_id = $_SESSION['sid'];

    // cancelling application query
    $delete_query = "DELETE FROM apply WHERE sid ='$student_id' AND cid='$given_cid'";
    $result = mysqli_query($con, $delete_query);

    // after deleting application, quota of this company is increased in that query
    $quota_update = "UPDATE company SET quota = quota + 1 WHERE cid='$given_cid'";
    $quota_result = mysqli_query($con, $quota_update);

    //checking errors
    if (!$result && !$quota_result)
        exit();
    else {
        echo "<script LANGUAGE='JavaScript'>
            window.alert('Application is successfully deleted.');
            window.location.href = 'list_app.php'; 
        </script>";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Accounts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
          crossorigin="anonymous">
    <style type="text/css">
        body {
            text-align: center;
        }

        p {
            margin-bottom: 10px;
        }

        th, td {
            padding: 5px;
            text-align: left;
        }
    </style>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <h5 class="navbar-text" style="color: white">Welcome,<?php echo htmlspecialchars($_SESSION['sname']); ?></h5>
        <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php" style="color: white">Go Back</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php" style="color: white">Logout</a>
                </li>
            </ul>
        </div>

    </nav>
    <div class="panel container-fluid">
        <br><br>
        <h3 class="page-header" style="font-weight: bold;">Applied Internships</h3>
        <?php
        // Prepare a select statement
        $query = "SELECT cid, cname, quota FROM student NATURAL JOIN apply NATURAL JOIN company WHERE sid = " . $_SESSION['sid'];

        echo "<p><b>Student ID:</b> " . $_SESSION['sid'] . "</p>";

        $result = mysqli_query($con, $query);

        if (!$result) {
            printf("Error: %s\n", mysqli_error($db));
            exit();
        }

        echo "<table class=\"table table-lg table-striped\">
            <tr>
            <th>Company ID</th>
            <th>Company Name</th>
            <th>Quota</th>
            <th>Cancel</th>
            </tr>";

        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['cid'] . "</td>";
            echo "<td>" . $row['cname'] . "</td>";
            echo "<td>" . $row['quota'] . "</td>";
            echo "<td> <form action=\"\" METHOD=\"POST\">
                    <button type=\"submit\" name = \"given_cid\"class=\"btn btn-dark btn-sm\" value =" . $row['cid'] . ">X</button>
                    </form>
                     
                  </td>";
            echo "</tr>";
        }

        echo "</table>";
        ?>
    </div>
    <p><a href="application.php" class="btn btn-block btn-dark">Apply Internship</a></p>
</div>


</body>
</html>

