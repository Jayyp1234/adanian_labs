<?php
    ob_start();
    include_once('../../init.php');
    $token_time = 30 * 60 * 60;
    if(isset($_POST['email']) && isset($_POST['password'])){
        #input validator
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        #token generator
        function logintoken() { 
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ023456789"; 
            srand((double)microtime()*1000000); 
            $i = 0; 
            $pass = '' ; 
            while ($i <= 30) { 
                    $num = rand() % 33; 
                    $tmp = substr($chars, $num, 1); 
                    $pass = $pass . $tmp; 
                    $i++; 
                } 
            return $pass; 
        }
        $key = logintoken();
        $time = time();
        $expiry_time = time() + $token_time;
        $email = test_input($_POST['email']);
        $password = test_input($_POST['password']);
        $sqle = "SELECT * FROM user WHERE email = '$email'";
        $resulte = $conn->query($sqle);
            if ($resulte->num_rows > 0) {
            // output data of each row
                while($row = $resulte->fetch_assoc()) {
                    $id = $row['user_id'];
                    $name = $row['username'];
                    $hashedPwd = $row['password'];
                    if(password_verify($password,$hashedPwd) == 1){
                        //Updating The Query with the new key;
                        $sql = "UPDATE `user` SET `start_time`='$time',`finish_time`='$expiry_time',`login_token`='$key' WHERE email = '$email'";
                        $query = mysqli_query($conn,$sql);
                        $details = array('user_id'=> $id, 'name'=> $name,'token-key' => $key, 'status'=> 'Login Succesfully', 'message'=>'You can access other api with the token key, expires in 30 minutes');
                        echo json_encode($details);
                    }
                }
            } 

        else{
            echo 'Invalid credidentials please fill correctly and try again';
        }
    }
    else{
        echo 'Incorrect please fill correctly and try again';
    }

?>