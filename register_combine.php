<?php

include 'components/connect.php';

function generate_orcid() {
    // Generate a random 16-digit ORCID number in the format xxxx-xxxx-xxxx-xxxx
    return sprintf('%04d-%04d-%04d-%04d', mt_rand(0, 9999), mt_rand(0, 9999), mt_rand(0, 9999), mt_rand(0, 9999));
}

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

   // Capture optional fields for faculty, university, and orcid
   $faculty = $_POST['faculty'];
   $faculty = filter_var($faculty, FILTER_SANITIZE_STRING);
   $university = $_POST['university'];
   $university = filter_var($university, FILTER_SANITIZE_STRING);
   $orcid = !empty($_POST['orcid']) ? $_POST['orcid'] : generate_orcid(); // Generate ORCID if empty
   $orcid = filter_var($orcid, FILTER_SANITIZE_STRING);

   // Handle optional image upload
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $rename = '';
   if (!empty($image)) {
       $ext = pathinfo($image, PATHINFO_EXTENSION);
       $rename = unique_id().'.'.$ext;
       $image_tmp_name = $_FILES['image']['tmp_name'];
       $image_folder = 'uploaded_files/'.$rename;
   } else {
       $rename = 'anoymous.jpg'; // Use a default image if no image is uploaded
   }

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
         // If faculty and university are provided, insert into tutors, otherwise into users
         if(!empty($faculty) && !empty($university)) {
            $insert_tutor = $conn->prepare("INSERT INTO `tutors`(id, name, faculty, university, orcid, email, password, image) VALUES(?,?,?,?,?,?,?,?)");
            $insert_tutor->execute([$id, $name, $faculty, $university, $orcid, $email, $cpass, $rename]);
            $message[] = 'New tutor registered! Please login now.';
         } else {
            $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password, image) VALUES(?,?,?,?,?)");
            $insert_user->execute([$id, $name, $email, $cpass, $rename]);
            $message[] = 'New user registered! Please login now.';
         }
         if (!empty($image)) {
             move_uploaded_file($image_tmp_name, $image_folder);
         }
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
    if (!is_array($message)) {
        $message = [$message];
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

      <p>Faculty (If you're a tutor)</p>
      <input type="text" name="faculty" placeholder="Enter your faculty" maxlength="50" class="box">
      
      <p>University (If you're a tutor)</p>
      <input type="text" name="university" placeholder="Enter your university" maxlength="50" class="box">
      
      <!-- <p>ORCID (If you're a tutor) - Optional</p>
      <input type="text" name="orcid" placeholder="Enter your ORCID" maxlength="20" class="box"> -->

      <p>Choose Your Image - Optional</p>
      <input type="file" name="image" accept="image/*" class="box">

      <p class="link">Already have an account? <a href="login_combine.php">Sign in now</a></p>
      <input type="submit" name="submit" value="Register Now" class="btn">
   </form>
</section>



<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
</body>
</html>