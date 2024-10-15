<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   // Check in tutors table first
   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? AND password = ? LIMIT 1");
   $select_tutor->execute([$email, $pass]);
   $row_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
   
   if($select_tutor->rowCount() > 0){
      // If found in tutors (admin), set cookie and redirect to admin dashboard
      setcookie('user_id', $row_tutor['id'], time() + 60*60*24*30, '/');
      header('location:admin/dashboard.php'); // Redirect to admin (tutor) dashboard
   }else{
      // If not found in tutors, check in users table
      $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
      $select_user->execute([$email, $pass]);
      $row_user = $select_user->fetch(PDO::FETCH_ASSOC);
      
      if($select_user->rowCount() > 0){
         // If found in users, set cookie and redirect to user homepage
         setcookie('user_id', $row_user['id'], time() + 60*60*24*30, '/');
         header('location:home.php'); // Redirect to user homepage
      }else{
         // If not found in either table, show error message
         $message[] = 'Incorrect email or password!';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'components/user_header.php'; ?>
<?php
if(isset($message)){
    if (!is_array($message)) {
        $message = [$message]; // Convert string to an array
    }       
   foreach($message as $msg){
      echo '<div class="message"><span>'.htmlspecialchars($msg) .'</span></div>';
   }
}
?>

<section class="form-container">
   <form action="" method="post" class="login">
      <h3>Welcome back!</h3>
      <p>Your email <span>*</span></p>
      <input type="email" name="email" placeholder="Enter your email" required class="box">
      <p>Your password <span>*</span></p>
      <input type="password" name="pass" placeholder="Enter your password" required class="box">
      <p class="link">Don't have an account? <a href="register_combine.php">Register now</a></p>
      <input type="submit" name="submit" value="Login now" class="btn">
   </form>
</section>
<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
</body>
</html>
