<?php

$firstname = $_POST['fn'];
$lastname = $_POST['ln'];
$email = $_POST['email'];
$password = $_POST['pass'];

if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($password)) {

    $host = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "web";

    
    $conn = new mysqli($host, $username, $dbpassword, $dbname);

    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        
        $select = "SELECT email FROM regr WHERE email = ? LIMIT 1";
        $insert = "INSERT INTO regr (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";

        
        $stmt = $conn->prepare($select);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $rnum = $stmt->num_rows;

        if ($rnum == 0) {
            
            $stmt->close();

            $stmt = $conn->prepare($insert);
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("ssss", $firstname, $lastname, $email, $password);
            $stmt->execute();

            echo "New record inserted successfully";
        } else {
            echo "Someone already registered using this email";
        }

        $stmt->close();
        $conn->close();
    }
} else {
    echo "All fields are required";
    die();
}

?>
