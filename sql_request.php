<?php
// sql create connection
function create_connection($servername, $username, $password, $dbname)
{

  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if (!$conn)
    die("Connection failed: " . mysqli_connect_error() . "\n");
  return $conn;
}

// sql create table
function create_table($conn, $table)
{
  $sql = "CREATE TABLE channel_$table (
words VARCHAR(35) NOT NULL,
occur VARCHAR(4) NOT NULL
)";
  
  if (mysqli_query($conn, $sql))
    echo "Table " . $table . " created successfully\n";
  else
    echo "Error creating table: " . mysqli_error($conn) . "\n";
}

// sql insert
function insert_into_table($conn, $table, $words_tab)
{
  foreach ($words_tab as $key => $value)
    {
      $sql = "INSERT INTO channel_$table (words, occur) VALUES ('$key', '$value')";
      
      if (mysqli_query($conn, $sql))
	echo "New data created successfully\n";
      else
	echo "Error: " . $sql . "\n" . mysqli_error($conn) . "\n";
    }
}

// sql select table
function select_table($conn, $table)
{
  $sql = "SELECT * FROM channel_$table";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0)
    {
      while($row = mysqli_fetch_assoc($result))
	$array[$row["words"]] = $row["occur"];
      echo json_encode($array);
    }
  else
    echo "0 results\n";
  return ($array);
}

// sql to delete from table
function delete_from_table($conn, $table)
{
  $sql = "DELETE FROM channel_$table";

  if (mysqli_query($conn, $sql))
    echo "Data deleted successfully\n";
  else
    echo "Error deleting record: " . mysqli_error($conn) . "\n";
}

// sql deconnect
function disconnect($conn)
{
  mysqli_close($conn);
}
?>