<?php

   include '../components/connect.php';

   if(isset($_COOKIE['tutor_id'])){
      $tutor_id = $_COOKIE['tutor_id'];
   }else{
      $tutor_id = '';
      header('location:login.php');
   }

   $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
   $select_playlists->execute([$tutor_id]);
   $total_playlists = $select_playlists->rowCount();

   $select_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
   $select_contents->execute([$tutor_id]);
   $total_contents = $select_contents->rowCount();

   $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
   $select_likes->execute([$tutor_id]);
   $total_likes = $select_likes->rowCount();

   $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
   $select_comments->execute([$tutor_id]);
   $total_comments = $select_comments->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
<style>
   h2 {
      display: flex; /* Use flexbox to align the label and value in a row */
      justify-content: center; /* Center the label and value horizontally */
      align-items: center; /* Vertically align the items */
      margin: 5px 0; /* Optional margin for spacing between the lines */
   }

   h2 span.label {
      font-weight: bold; /* Optional, for bolding the label */
      margin-right: 5px; /* Reduce the space between label and value */
   }

   h2 span.value {
      flex-grow: 0; /* Ensure the value only takes up necessary space */
   }


</style>
<section class="tutor-profile" style="min-height: calc(100vh - 19rem);"> 

   <h1 class="heading">Profile details</h1>

   <div class="details">
      <div class="tutor">
         <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <h2><span class="label">Email:</span><span class="value"><?= htmlspecialchars($fetch_profile['email']); ?></span></h2>
         <h2><span class="label">Khoa:</span><span class="value"><?= htmlspecialchars($fetch_profile['faculty']); ?></span></h2>
         <h2><span class="label">ORCID:</span><span class="value"><?= htmlspecialchars($fetch_profile['orcid']); ?></span></h2>
         <h2><span class="label">Đại Học:</span><span class="value"><?= htmlspecialchars($fetch_profile['university']); ?></span></h2>
         <a href="update.php" class="inline-btn">Update profile</a>
      </div>
      <div class="flex">
         <div class="box">
            <span><?= $total_playlists; ?></span>
            <p>Total projects</p>
            <a href="playlists.php" class="btn">View projects</a>
         </div>
         <div class="box">
            <span><?= $total_contents; ?></span>
            <p>Total videos</p>
            <a href="contents.php" class="btn">View contents</a>
         </div>
         <div class="box">
            <span><?= $total_likes; ?></span>
            <p>Total likes</p>
            <a href="contents.php" class="btn">View contents</a>
         </div>
         <div class="box">
            <span><?= $total_comments; ?></span>
            <p>Total comments</p>
            <a href="comments.php" class="btn">View comments</a>
         </div>
      </div>
   </div>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>