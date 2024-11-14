<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_POST['submit'])){

   $id = unique_id();
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $status = $_POST['status']; // ongoing or completed
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   $role = $_POST['role'];  // New: Role in the project
   $role = filter_var($role, FILTER_SANITIZE_STRING);
   $funds = $_POST['funds'];  // New: Funding information
   $funds = filter_var($funds, FILTER_SANITIZE_STRING);
   $start_date = $_POST['start_date'];  // New: Start date of the project
   $start_date = filter_var($start_date, FILTER_SANITIZE_STRING);
   $end_date = $_POST['end_date'];  // New: End date of the project (optional)
   $end_date = filter_var($end_date, FILTER_SANITIZE_STRING);
   $notes = $_POST['notes'];  // New: Additional notes
   $notes = filter_var($notes, FILTER_SANITIZE_STRING);

   // $image = $_FILES['image']['name'];
   // $image = filter_var($image, FILTER_SANITIZE_STRING);
   // $ext = pathinfo($image, PATHINFO_EXTENSION);
   // $rename = unique_id().'.'.$ext;
   // $image_size = $_FILES['image']['size'];
   // $image_tmp_name = $_FILES['image']['tmp_name'];
   // $image_folder = '../uploaded_files/'.$rename;

   $add_project = $conn->prepare("INSERT INTO `playlist`(id, tutor_id, title, description, status, role, funds, start_date, end_date, notes) VALUES(?,?,?,?,?,?,?,?,?,?)");
   $add_project->execute([$id, $tutor_id, $title, $description, $status, $role, $funds, $start_date, $end_date, $notes]);

   // move_uploaded_file($image_tmp_name, $image_folder);

   $message[] = 'New project created!';  

}

?>


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Project</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="playlist-form">

   <h1 class="heading">Create Project</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <p>Project Status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="selected disabled">-- Select status --</option>
         <option value="ongoing">Ongoing</option>
         <option value="completed">Completed</option>
      </select>

      <p>Project Title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Enter project title" class="box">

      <p>Project Description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Write description" maxlength="1000" cols="30" rows="10"></textarea>

      <!-- <p>Project Thumbnail <span>*</span></p>
      <input type="file" name="image" accept="image/*" required class="box"> -->

      <p>Your Role in the Project <span>*</span></p>
      <input type="text" name="role" maxlength="100" required placeholder="Enter your role (e.g., Chief Investigator)" class="box">

      <p>Funds <span>*</span></p>
      <input type="text" name="funds" maxlength="100" required placeholder="Enter source of funds" class="box">

      <p>Start Date <span>*</span></p>
      <input type="date" name="start_date" required class="box">

      <p>End Date</p>
      <input type="date" name="end_date" class="box">

      <p>Additional Notes</p>
      <textarea name="notes" class="box" placeholder="Write additional notes or collaboration details" maxlength="1000" cols="30" rows="10"></textarea>

      <input type="submit" value="Create Project" name="submit" class="btn">
   </form>

</section>


<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>