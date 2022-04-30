<?php
session_start();
include '../../init.php';
ob_start();
if(isset($_SESSION['user_id']) && isset($_SESSION['email'])){
    header('location:../../dashboard/');
    ob_end_flush();
}
?>

<?php
  include '../../init.php';
  function createKey() { 
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ023456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $pass = '' ; 
    while ($i <= 10) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
        } 
         return $pass; 
    } 
    $key =  createKey();
    #input validator
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

  if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])){
    $name = test_input($_POST['name']);
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']);
    $password1 = test_input($_POST['confirm-password']);
    $errorArray = array();
    $emailval = "SELECT * FROM user WHERE email = '$email' ";
    $querycheck = mysqli_query($conn,$emailval);
    if ($querycheck) {
        if(mysqli_num_rows($querycheck) > 0 ){
            array_push($errorArray, "Email Already exist.." );
        }else{
            array_push($errorArray, "" );
            $passwordHash =  password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `user`(`user_id`, `username`, `email`, `password`) VALUES ('$key','$name','$email','$passwordHash')";
            $query = mysqli_query($conn,$sql);
             if($query){
                 $_SESSION['email'] = $email;
                 if(isset($_SESSION['email'])){
                    $sql = "SELECT * FROM user where email = '$email' ";
                    $query = mysqli_query($conn,$sql);
                    if($query){
                        $row = mysqli_fetch_assoc($query);
                        $id = $row['user_id'];
                        $name = $row['username'];
                        $details = array('user_id'=> $id, 'name'=> $name, 'status'=> 'Account Created Succesfully', 'message'=>'Login with email and password');
                        echo json_encode($details);
                    }
                 }else{
                     echo "cannot redirect";
                 }
              }
        }
    }
    else{
        array_push($errorArray, "Email Already exist.." );
    }
    array_push($errorArray, (!preg_match("/^[a-zA-Z ]*$/", $name)) ? "Only letters and white space allowed":"" );
    array_push($errorArray, (!filter_var($email, FILTER_VALIDATE_EMAIL)) ? "Invalid email address":"" );
    array_push($errorArray, ($password != $password1) ? "Passwords does not match, please try again.":"" );
    
    
    echo json_encode($errorArray); 
  }
  else{
    echo 'Incomplete key-values';
  }

?>