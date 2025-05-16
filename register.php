<?php

$servername = "127.0.0.1";
$username = "root";
$password = "StrongDBPassword@";
$dbname = "nitc";



   $conn = new mysqli( $servername, $username, $password, $dbname);
   

           if($conn -> connect_error)
           	   die("connection failed" . $conn-> connect_error);
           	else
           	{
           			$fname=$_POST['fname'];
           			$lname=$_POST['lname'];
           			$email=$_POST['email'];
           			$dob=$_POST['dob'];
           			$gen=$_POST['gender'];
           			$mob=$_POST['mobile'];
           			$pwd=$_POST['pass'];
           		 $query1 ="SELECT fname,lname,email,mobile FROM register WHERE email='$email' OR mobile='$mob' ";
           		 //echo $query1;
           		
           		 $result = $conn->query($query1);

                  
				


                    if($result->num_rows > 0) {                         
                              include 'alreadyregistered.php';
                     }
		       else {
				 $sql = "INSERT INTO `register`(`fname`, `lname`, `email`, `password`, `mobile`, `dob`, `gender`, `image`) VALUES ('".$fname."','".$lname."','".$email."','".$pwd."','".$mob."','".$dob."','".$gen."','img/user.png')";
     				  if ($conn->query($sql) === TRUE)
					 {  
					   include 'registered.php';
				 	}						  
   	    			 else {
				    echo "Error: " . $sql . "<br>" . $conn->error;
				      }
			 }
		}


$conn->close();

?>