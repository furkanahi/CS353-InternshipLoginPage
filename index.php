<?php
include("initial.php");

$username = "";
$password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Escape special characters, if any
	$username = mysqli_real_escape_string($con, $_POST['username']);
	$password = mysqli_real_escape_string($con, $_POST['password']);

	$sql = "SELECT sname, sid FROM student WHERE sname = ? and sid = ?";

// prepare and bind
	if ($stmt = mysqli_prepare($con, $sql)) {
		$stmt->bind_param("ss", $entered_u, $entered_p);

		//set parameters
		$entered_u = $username;
		$entered_p = $password;

		if (mysqli_stmt_execute($stmt)) {
			mysqli_stmt_store_result($stmt);

			if (mysqli_stmt_num_rows($stmt)) {
				mysqli_stmt_bind_result($stmt, $username, $returned_p);

				if (mysqli_stmt_fetch($stmt)) {
					if ($returned_p == $password) {
						//inputs are correct session is starting
						session_start();
						$_SESSION['sname'] = $username;
						$_SESSION['sid'] = $password;
						header("location: list_app.php");
					}
				}
			} else echo "<script type='text/javascript'>alert('Invalid Username or Password.');</script>";
		}
	}
	$con->close();
}
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
          crossorigin="anonymous">
    <style type="text/css">
        #centerwrapper {
            text-align: center;
            margin-bottom: 10px;
        }

        #centerdiv {
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-inverse bg-dark navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <h4 class="navbar-text" style="color: white" >Summer Internship Application</h4>
            </div>
        </div>
    </nav>
    <div id="centerwrapper">
        <div id="centerdiv">
            <br><br>
            <h2>Login to Internship System</h2>
            <form id="loginForm" action="" method="post">
                <div class="form-group">
                    <label>Username: </label>
                    <input type="text" name="username" class="form-control" id="username">

                </div>
                <div class="form-group">
                    <label>Password: </label>
                    <input type="password" name="password" class="form-control" id="password">

                </div>
                <div class="form-group">
                    <input onclick="emptyCheck()" class="btn btn-dark" value="Login">
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    function emptyCheck() {
        let usernameVal = document.getElementById("username").value;
        let passwordVal = document.getElementById("password").value;
        if (passwordVal === "" || usernameVal === "") {
            alert("Please fill all fields!");
        } else {
            let form = document.getElementById("loginForm").submit();
        }
    }
</script>
</body>
</html>