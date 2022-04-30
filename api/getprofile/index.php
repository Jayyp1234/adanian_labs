<?php
    ob_start();
    include_once('../../init.php');
    if(isset($_POST['token']) && isset($_POST['id'])){
        $token = $_POST['token'];
        $id = $_POST['id'];
        $sqle = "SELECT * FROM user WHERE login_token = '$token' and user_id = '$id'";
        $resulte = $conn->query($sqle);
            if ($resulte->num_rows > 0) {
            // output data of each row
                while($row = $resulte->fetch_assoc()) {
                    $id = $row['user_id'];
                    $name = $row['username'];
                    $start = $row['start_time'];
                    $finish = $row['finish_time'];
                    $key = $row['login_token'];
                    if($start < $finish){
                        $info = array();
                        $sql = "SELECT * FROM user WHERE user_id = '$id'";
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
            echo 'Token or User ID, does not Exist Please send a valid token';
        }
    }
    else{
        echo 'Incorrect please fill correctly and try again';
    }

?>