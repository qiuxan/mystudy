<?php
$data= json_decode(file_get_contents("php://input"));
$empono=mysql_real_escape_string($data->empono);
$fname=mysql_real_escape_string($data->fname);
$lname=mysql_real_escape_string($data->lname);
$dept=mysql_real_escape_string($data->dept);
mysql_connect("localhost","aj_admin","123321");
mysql_select_db("company");
mysql_query("INSERT INTO employee( `emp_no`, `first_name`, `last_name`, `dept_name`) VALUES ('".$empono."', '".$fname."','".$lname."','".$dept."')");
?>