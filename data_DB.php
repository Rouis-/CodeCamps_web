<?php

require_once("./sql_request.php");

function	recup_data($i)
{
  $username = "rouis";
  $password = "next";
  $dbname = "tweets";
  $servername = "localhost";
  $conn = create_connection($servername, $username, $password, $dbname);
  select_table($conn, $i);
}

recup_data($_GET['channel']);