<?php

include 'components/connect.php';

if(isset($_POST['submit'])){

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_files/'.$rename;

   $profession = $_POST['profession']; // Capture profession input if present
   $profession = filter_var($profession, FILTER_SANITIZE_STRING);

   // Check if email already exists in tutors or users tables
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");

   $select_user->execute([$email]);
   $select_tutor->execute([$email]);

   if($select_user->rowCount() > 0 || $select_tutor->rowCount() > 0){
      $message[] = 'Email already taken!';
   }else{
      if($pass != $cpass){
         $message[] = 'Confirm password not matched!';
      }else{
         // If profession is not empty, insert into tutors, otherwise into users
         if(!empty($profession)) {
            $insert_tutor = $conn->prepare("INSERT INTO `tutors`(id, name, profession, email, password, image) VALUES(?,?,?,?,?,?)");
            $insert_tutor->execute([$id, $name, $profession, $email, $cpass, $rename]);
            $message[] = 'New tutor registered! Please login now.';
         } else {
            $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password, image) VALUES(?,?,?,?,?)");
            $insert_user->execute([$id, $name, $email, $cpass, $rename]);
            $message[] = 'New user registered! Please login now.';
         }
         move_uploaded_file($image_tmp_name, $image_folder);
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
   <title>Register</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
<?php include 'components/user_header.php'; ?>
<?php
if (isset($message)) {
    // Check if $message is an array, if not, convert it to an array
    if (!is_array($message)) {
        $message = [$message]; // Wrap $message into an array
    }
    
    foreach($message as $msg) {
        echo '
        <div class="message form">
           <span>' . htmlspecialchars($msg) . '</span>
           <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>


<section class="form-container">
   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>Create Account</h3>
      <div class="flex">
         <div class="col">
            <p>Your Name <span>*</span></p>
            <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="box">
            <p>Your Email <span>*</span></p>
            <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
         </div>
         <div class="col">
            <p>Your Password <span>*</span></p>
            <input type="password" name="pass" placeholder="Enter your password" maxlength="20" required class="box">
            <p>Confirm Password <span>*</span></p>
            <input type="password" name="cpass" placeholder="Confirm your password" maxlength="20" required class="box">
         </div>
      </div>

      <p>Your Profession (Only if you're a tutor) <span>*</span></p>
      <select name="profession" class="box">
         <option value="" selected>-- Optional: Select your profession --</option>
         <option value="developer">Developer</option>
         <option value="designer">Designer</option>
         <option value="musician">Musician</option>
         <option value="biologist">Biologist</option>
         <option value="engineer">Engineer</option>
         <option value="lawyer">Lawyer</option>
         <option value="accountant">Accountant</option>
         <option value="doctor">Doctor</option>
         <!-- Add more options as needed -->
      </select>

      <p>Choose Your Image <span>*</span></p>
      <input type="file" name="image" accept="image/*" required class="box">

      <p class="link">Already have an account? <a href="login_combine.php">Sign in now</a></p>
      <input type="submit" name="submit" value="Register Now" class="btn">
   </form>
</section>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
</body>
</html>
