<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:projects.php');
}

if(isset($_POST['submit'])){

   // Sanitize and capture the input values
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   $role = $_POST['role'];
   $role = filter_var($role, FILTER_SANITIZE_STRING);
   $funds = $_POST['funds'];
   $funds = filter_var($funds, FILTER_SANITIZE_STRING);
   $start_date = $_POST['start_date'];
   $start_date = filter_var($start_date, FILTER_SANITIZE_STRING);
   $end_date = $_POST['end_date'];
   $end_date = filter_var($end_date, FILTER_SANITIZE_STRING);
   $notes = $_POST['notes'];
   $notes = filter_var($notes, FILTER_SANITIZE_STRING);

   // Update the project table
   $update_project = $conn->prepare("UPDATE `playlist` SET title = ?, description = ?, status = ?, role = ?, funds = ?, start_date = ?, end_date = ?, notes = ? WHERE id = ?");
   $update_project->execute([$title, $description, $status, $role, $funds, $start_date, $end_date, $notes, $get_id]);

   // Image handling
   // $old_image = $_POST['old_image'];
   // $old_image = filter_var($old_image, FILTER_SANITIZE_STRING);
   // $image = $_FILES['image']['name'];
   // $image = filter_var($image, FILTER_SANITIZE_STRING);
   // $ext = pathinfo($image, PATHINFO_EXTENSION);
   // $rename = unique_id().'.'.$ext;
   // $image_size = $_FILES['image']['size'];
   // $image_tmp_name = $_FILES['image']['tmp_name'];
   // $image_folder = '../uploaded_files/'.$rename;

   // if(!empty($image)){
   //    if($image_size > 2000000){
   //       $message[] = 'image size is too large!';
   //    }else{
   //       $update_image = $conn->prepare("UPDATE `project` SET thumb = ? WHERE id = ?");
   //       $update_image->execute([$rename, $get_id]);
   //       move_uploaded_file($image_tmp_name, $image_folder);
   //       if($old_image != '' AND $old_image != $rename){
   //          unlink('../uploaded_files/'.$old_image);
   //       }
   //    }
   // } 

   $message[] = 'Project updated!';  
}

if(isset($_POST['delete'])){
   $delete_id = $_POST['project_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
   $delete_project_thumb = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? LIMIT 1");
   $delete_project_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_project_thumb->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_files/'.$fetch_thumb['thumb']);
   $delete_project = $conn->prepare("DELETE FROM `project` WHERE id = ?");
   $delete_project->execute([$delete_id]);
   header('location:playlist.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Project</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="playlist-form">

   <h1 class="heading">Update Project</h1>

   <?php
         $select_project = $conn->prepare("SELECT * FROM `playlist` WHERE id = ?");
         $select_project->execute([$get_id]);
         if($select_project->rowCount() > 0){
         while($fetch_project = $select_project->fetch(PDO::FETCH_ASSOC)){
            $project_id = $fetch_project['id'];
      ?>
   <form action="" method="post" enctype="multipart/form-data">
      <!-- <input type="hidden" name="old_image" value="<?= $fetch_project['thumb']; ?>">   -->
      <p>Project Status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fetch_project['status']; ?>" selected><?= $fetch_project['status']; ?></option>
         <option value="ongoing">Ongoing</option>
         <option value="completed">Completed</option>
      </select>

      <p>Project Title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Enter project title" value="<?= $fetch_project['title']; ?>" class="box">

      <p>Project Description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Write description" maxlength="1000" cols="30" rows="10"><?= $fetch_project['description']; ?></textarea>

      <p>Project Role <span>*</span></p>
      <input type="text" name="role" maxlength="100" required placeholder="Enter your role" value="<?= $fetch_project['role']; ?>" class="box">

      <p>Funds <span>*</span></p>
      <input type="text" name="funds" maxlength="100" required placeholder="Enter source of funds" value="<?= $fetch_project['funds']; ?>" class="box">

      <p>Start Date <span>*</span></p>
      <input type="date" name="start_date" value="<?= $fetch_project['start_date']; ?>" class="box">

      <p>End Date</p>
      <input type="date" name="end_date" value="<?= $fetch_project['end_date']; ?>" class="box">

      <p>Additional Notes</p>
      <textarea name="notes" class="box" placeholder="Write additional notes" maxlength="1000" cols="30" rows="10"><?= $fetch_project['notes']; ?></textarea>

      <!-- <p>Project Thumbnail <span>*</span></p>
      <div class="thumb">
         <img src="../uploaded_files/<?= $fetch_project['thumb']; ?>" alt="">
      </div>
      <input type="file" name="image" accept="image/*" class="box"> -->

      <input type="submit" value="Update Project" name="submit" class="btn">
      <div class="flex-btn">
         <input type="submit" value="Delete" class="delete-btn" onclick="return confirm('Delete this project?');" name="delete">
         <a href="view_playlist.php?get_id=<?= $project_id; ?>" class="option-btn">View Project</a>
      </div>
   </form>
   <?php
      } 
   }else{
      echo '<p class="empty">No project found!</p>';
   }
   ?>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>
