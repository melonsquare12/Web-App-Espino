<?php
// print_r(PDO::getAvailableDrivers());

$host = "localhost";
$user="root";
$password="";
$dbname="dylan";

$data_source = "mysql:host=$host; dbname=$dbname";

$conn = new PDO($data_source, $user, $password);

$statement = $conn->query("SELECT * FROM employee");

// while($row = $statement->fetch(PDO::FETCH_ASSOC)){
    // echo $row['first_name'] . "" . $row['last_name'] . "<br />";
// };

// $rows = $statement->fetchAll(PDO::FETCH_OBJ);
// for ($i=0; $i < count($rows); $i++){
    // echo $rows[$i]->first_name . "". $rows[$I]->last_name . "<br />";
// }

$address="Sauyo";
// $sql="SELECT * FROM employee WHERE address = ?";
// $statement=$conn->prepare($sql);
// $statement->execute([$address]);
// $users=$statement->fetchAll();

$sql="SELECT * FROM employee WHERE address = :ad";
$statement=$conn->prepare($sql);
$statement->execute(['ad'=>$address]);
$users=$statement->fetchAll(PDO::FETCH_OBJ);

foreach ($users as $user){
    echo $user->first_name . "" . $user->last_name . "<br />";
}



?>