<?php
    ob_start();
    include_once('../../init.php');
    if(isset($_POST['token'])){
        $token = $_POST['token'];
        $sqle = "SELECT * FROM user WHERE login_token = '$token'";
        $resulte = $conn->query($sqle);
            if ($resulte->num_rows > 0) {
            // output data of each row
                while($row = $resulte->fetch_assoc()) {
                    $id = $row['user_id'];
                    $name = $row['username'];
                    $start = $row['start_time'];
                    $finish = $row['finish_time'];
                    $key = $row['login_token'];
                    $time = time()
                    if($time < $finish){
                        $info = array();
                        $sql = "SELECT * FROM user WHERE 1";
                        $resulte = $conn->query($sql);
                        if ($resulte->num_rows > 0) {
                        // output data of each row
                            while($row = $resulte->fetch_assoc()) {
                                array_push($info, array('user_id'=> $row['user_id'], 'name'=> $row['username'],'email' => $row['email'], 'status'=> 'active'));
                            }
                        }
                        echo json_encode($info);
                    }
                    else{
                        $details = array('user_id'=> $id, 'name'=> $name,'token-key' => $key, 'status'=> 'Token Expired', 'message'=>'Login again to be given a new token');
                        echo json_encode($details);
                    }
                }
            }

        else{
            echo 'Token Does not Exist Please send a valid token';
        }
    }
    else{
        echo 'Incorrect please fill correctly and try again';
    }

?>
