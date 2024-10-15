<?php
  // connect to database
  $host="localhost";
  $user="root";
  $password="";
  $dbname="courses";

  $data_source_name = "mysql:host=$host; dbname=$dbname";
  $conn = new PDO($data_source_name, $user, $password);
  $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ); 

  $statement = $conn->query("SELECT * FROM courses");

//   // fetch_assoc = associatitve array
//   $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
//   for ($i=0; $i < count($rows); $i++){
//     echo $rows[$i]["course_name"] . "" . $rows[$i]["course_description"] . "<br />";
//   } 

  // fetch_obj = object
// $rows = $statement->fetchAll(PDO::FETCH_OBJ);
//   for ($i=0; $i < count($rows); $i++){
//     echo $rows[$i]->course_name . " > " . $rows[$i]->course_description . "<br />";
//   }
 
 // prepared statements
 $is_status_var = 1;

 // positional parameters - reserve for the actual value
  $sql = "SELECT * FROM courses WHERE is_status = ?";
  $statement=$conn->prepare($sql);
  $statement->execute([$is_status_var]);
  $subjects = $statement->fetchAll();

 // named parameters - assigns value to variable
//  $sql = "SELECT * FROM courses WHERE is_status = :status";
//   $statement=$conn->prepare($sql);
//   $statement->execute(['status' => $is_status_var]);
//   $subjects = $statement->fetchAll();


//   foreach($subjects as $subject){
//      echo $subject->course_name . " > " . $subject->course_description . "<br />";
//   }
  
// query single record
// $id=5;
// $sql="SELECT * FROM courses WHERE course_id = :id";
// $statement=$conn->prepare($sql);
// $statement->execute(['id'=>$id]);
// $subject = $statement->fetch();
// echo $subject->course_name . " > " . $subject->course_description . "<br />"; 
// $subjectCount=$statement->rowCOunt();
// echo $subjectCount;

// insert statement
// $course_name = "Software Engineering 1";
// $course_desc = "Software Engineering 1 description";
// $is_status = 0;


// $sql = "INSERT INTO courses (course_name, course_description, is_status) VALUES (:course_name, :course_desc, :status)";
// $statement=$conn->prepare($sql);
// $data = ['course_name' =>$course_name, 'course_desc'=>$course_desc, 'status'=>$is_status];
// $statement->execute($data);
// echo $statement->rowCount();

// update statement
$course_name = "Discrete Mathematics";
$id = 1;
$sql = "UPDATE courses SET course_name=:course_name WHERE course_id=:id";
$statement=$conn->prepare($sql);
$data = [ 'course_name'=>$course_name, 'id'=>$id];
$statement->execute($data);
echo $statement->rowCount();

//delete statement
$id=25;
$sql = "DELETE FROM courses WHERE course_id=:id";
$statement=$conn->prepare($sql);
$statement->execute(['id'=>$id]);
echo $statement->rowCount();

  ?>