<?php
define('hostname', 'dijkstra.ug.bcc.bilkent.edu.tr');
define('username', 'furkan.ahi');
define('password', 'Cz0ZYTaO');
define('database', 'furkan_ahi');
$con = mysqli_connect(hostname, username,
	password, database);

if (!$con) {
	die("El-Gg dedigimiz olay" . mysqli_connect_error());
}
session_start();
