<?php

include '../components/connect.php';

function generate_orcid() {
    return sprintf('%04d-%04d-%04d-%04d', mt_rand(0, 9999), mt_rand(0, 9999), mt_rand(0, 9999), mt_rand(0, 9999));
}

if(isset($_POST['submit'])){

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $faculty = $_POST['faculty'];
   $faculty = filter_var($faculty, FILTER_SANITIZE_STRING);
   $university = $_POST['university'];
   $university = filter_var($university, FILTER_SANITIZE_STRING);
   $orcid = !empty($_POST['orcid']) ? $_POST['orcid'] : generate_orcid();
   $orcid = filter_var($orcid, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   // Optional image upload handling
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $rename = '';
   if (!empty($image)) {
       $ext = pathinfo($image, PATHINFO_EXTENSION);
       $rename = unique_id().'.'.$ext;
       $image_tmp_name = $_FILES['image']['tmp_name'];
       $image_folder = '../uploaded_files/'.$rename;
   } else {
       $rename = 'anoymous.jpg'; // Default image if none is uploaded
   }

   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
   $select_tutor->execute([$email]);
   
   if($select_tutor->rowCount() > 0){
      $message[] = 'Email already taken!';
   }else{
      if($pass != $cpass){
         $message[] = 'Confirm password not matched!';
      }else{
         $insert_tutor = $conn->prepare("INSERT INTO `tutors`(id, name, faculty, university, orcid, email, password, image) VALUES(?,?,?,?,?,?,?,?)");
         $insert_tutor->execute([$id, $name, $faculty, $university, $orcid, $email, $cpass, $rename]);
         if (!empty($image)) {
             move_uploaded_file($image_tmp_name, $image_folder);
         }
         $message[] = 'New tutor registered! Login now';
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

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body style="padding-left: 0;">

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message form">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- register section starts  -->

<section class="form-container">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>Register New Tutor</h3>
      <div class="flex">
         <div class="col">
            <p>Your Name <span>*</span></p>
            <input type="text" name="name" placeholder="Enter your name" maxlength="50" required class="box">
            <p>Your Faculty <span>*</span></p>
            <input type="text" name="faculty" placeholder="Enter your faculty" maxlength="50" class="box">
            <p>Your University <span>*</span></p>
            <input type="text" name="university" placeholder="Enter your university" maxlength="50" class="box">
         </div>
         <div class="col">
            <!-- <p>Your ORCID (Optional)</p>
            <input type="text" name="orcid" placeholder="Enter your ORCID" maxlength="20" class="box"> -->
            <p>Your Email <span>*</span></p>
            <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">
            <p>Your Password <span>*</span></p>
            <input type="password" name="pass" placeholder="Enter your password" maxlength="20" required class="box">
            <p>Confirm Password <span>*</span></p>
            <input type="password" name="cpass" placeholder="Confirm your password" maxlength="20" required class="box">
         </div>
      </div>

      <p>Choose Your Image (Optional) <span>*</span></p>
      <input type="file" name="image" accept="image/*" class="box">

      <p class="link">Already have an account? <a href="login.php">Login now</a></p>
      <input type="submit" name="submit" value="Register Now" class="btn">
   </form>

</section>


<!-- registe section ends -->

<script>

let darkMode = localStorage.getItem('dark-mode');
let body = document.body;

const enabelDarkMode = () =>{
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
}

const disableDarkMode = () =>{
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
}

if(darkMode === 'enabled'){
   enabelDarkMode();
}else{
   disableDarkMode();
}

</script>
   
</body>
</html>