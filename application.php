<?php
include("initial.php");

define("MAX_APPLICATION", 3);

//sql query for selecting number of application of logged student
$query = "select count(*) as cnt from apply where sid = " . $_SESSION['sid'];
$result = mysqli_query($con, $query);

if (!$result) {
    printf("Error: %s\n", mysqli_error($con));
    exit();
}
$row = mysqli_fetch_array($result);
$input_success = true;
$no_of_applciation = $row['cnt'];
//if number of application is 3 do not allow to apply more
if ($no_of_applciation == MAX_APPLICATION) {
	//printing error message
	$input_success = false;
	echo "<script LANGUAGE='JavaScript'>
          window.alert('You can apply maximum 3 interships...');
          window.location.href='list_app.php';
       </script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$input_success = true;
	$given_cid = $_POST['cid'];
	$student_id = $_SESSION['sid'];
	//checking unexpected input
	$unexpected_input = "SELECT COUNT(*) AS cnt  FROM company WHERE cid='$given_cid'";

	// executing query
	$result = mysqli_query($con, $unexpected_input);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($con));
        exit();
    }
	// checking company exists or give error
	$cnt = mysqli_fetch_array($result)['cnt'];
	if ($cnt == 0) {
		$input_success = false;
		echo "<script LANGUAGE='JavaScript'>
            window.alert('No such company exists.');
            window.location.href = 'application.php'; 
        </script>";
	}


	//already applied
	$already_applied_query = "select count(*) as cnt from apply where sid in (select sid from apply where sid ='$student_id' and cid ='$given_cid')";
	$result = mysqli_query($con, $already_applied_query);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($con));
        exit();
    }
	$row = mysqli_fetch_array($result);
	$count = $row['cnt'];
	if ($count >= 1) {
		$input_success = false;
		echo "<script LANGUAGE='JavaScript'>
            window.alert('You have already applied to this company.');
            window.location.href = 'application.php'; 
        </script>";
	}


	// checking whether quota is available for given company
	$quota_error_query = "select quota from company where cid='$given_cid'";
	$result = mysqli_query($con, $quota_error_query);
    if (!$result) {
        printf("Error: %s\n", mysqli_error($con));
        exit();
    }
	$row = mysqli_fetch_array($result);
	$quota_count = $row['quota'];

	if ($quota_count == 0) {
		$input_success = false;
		echo "<script LANGUAGE='JavaScript'>
            window.alert('No quota for the company.');
            window.location.href = 'application.php'; 
        </script>";
	}
	if ($input_success == true) {
		//Inserting the application in database
		$update_quota_query = "update company set quota = quota -1 where cid = '$given_cid'";
		$result = mysqli_query($con, $update_quota_query);
        if (!$result) {
            printf("Error: %s\n", mysqli_error($con));
            exit();
        }
		$insert_application_query = "insert into apply values ('$student_id','$given_cid')";
		$result = mysqli_query($con, $insert_application_query);
        if (!$result) {
            printf("Error: %s\n", mysqli_error($con));
            exit();
        }
		else {
			echo "<script LANGUAGE='JavaScript'>
            window.alert('Application is successfully added');
            window.location.href = 'list_app.php'; 
        </script>";
		}
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
            padding: 10px;
            text-align: left;
        }

        tr:nth-child(even){
            background-color: #f1ff1f;
        }
        tr:hover{
            background-color: #ffe30d;
        }
    </style>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <h5 class="navbar-text">Welcome,<?php echo htmlspecialchars($_SESSION['sname']); ?></h5>
        <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="list_app.php">Go Back</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>

    </nav>
    <div class="panel container-fluid">
        <br><br>
        <h3 class="page-header" style="font-weight: bold;">Intership Application Screen</h3>
		<?php
		echo "<table class=\"table table-lg table-striped\">
        <tr>
            <th >CID</th>
            <th>CName</th>
            <th>Quota</th>
        </tr>";

		$query = "select * from company as c where quota > 0 and not exists (select * FROM apply where c.cid = cid AND sid =" . $_SESSION['sid'] . ")";

		if (!$query)
			exit();

		$result = mysqli_query($con, $query);

		while ($row = mysqli_fetch_array($result)) {
			echo "<tr>";
			echo "<td>" . $row['cid'] . "</td>";
			echo "<td>" . $row['cname'] . "</td>";
			echo "<td>" . $row['quota'] . "</td>";
			echo "</tr>";
		}
		echo "</table>";
		?>
    </div>

    <form action="" METHOD="POST" display= "inline-block" style="margin-top: 15px;">
        <div class="form-row" style="text-align:center" >
            <input style="margin-left: 20px" style="text-align:center" type="text" class="form-control col-md-2" name="cid" placeholder="Enter Company ID">
            <button type="submit" class="btn btn-dark btn-sm">Submit</button>
        </div>
    </form>
</div>
