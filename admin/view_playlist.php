<?php

include '../components/connect.php';

// Ensure tutor is logged in
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

// Ensure a valid project (previously playlist) is being viewed
if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:playlist.php'); // Redirect if no ID is provided
}

// Handle project (playlist) deletion
if(isset($_POST['delete_playlist'])){
   $delete_id = $_POST['playlist_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   // Delete the project thumbnail file
   $delete_project_thumb = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? LIMIT 1");
   $delete_project_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_project_thumb->fetch(PDO::FETCH_ASSOC);
   if ($fetch_thumb) {
      unlink('../uploaded_files/'.$fetch_thumb['thumb']);
   }

   // Remove any related bookmarks, content, and finally the project (playlist)
   $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
   $delete_bookmark->execute([$delete_id]);

   $delete_content = $conn->prepare("DELETE FROM `content` WHERE playlist_id = ?");
   $delete_content->execute([$delete_id]);

   $delete_project = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
   $delete_project->execute([$delete_id]);

   header('location:playlists.php');
}

// Handle content (video) deletion
if(isset($_POST['delete_video'])){
   $delete_id = $_POST['video_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   // Verify if the content (video) exists
   $verify_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
   $verify_video->execute([$delete_id]);

   if($verify_video->rowCount() > 0){
      $delete_video_thumb = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $delete_video_thumb->execute([$delete_id]);
      $fetch_thumb = $delete_video_thumb->fetch(PDO::FETCH_ASSOC);
      if ($fetch_thumb) {
         unlink('../uploaded_files/'.$fetch_thumb['thumb']);
         unlink('../uploaded_files/'.$fetch_thumb['video']); // Delete video file
      }

      // Delete likes and comments related to the content
      $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
      $delete_likes->execute([$delete_id]);

      $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
      $delete_comments->execute([$delete_id]);

      // Finally, delete the content
      $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
      $delete_content->execute([$delete_id]);

      $message[] = 'Content deleted!';
   }else{
      $message[] = 'Content already deleted!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Project Details</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
<style>
.descrip {
      font-size: 16px;
      color: #007bff;
      margin-bottom: 20px;
      line-height: 1.4;
      white-space: normal; /* Allow text to wrap */
      word-wrap: break-word; /* Ensure long words break appropriately */
      overflow-wrap: break-word; /* Ensure long words break when needed */
      max-width: 300px; /* Optional: Limit width for better layout */
      line-height: 1.5; /* Optional: Control line spacing */
}
.fund {
    font-size: 22px;
      color: #007bff;
      margin-bottom: 10px;
   }
   .role {
    font-size: 22px;
      color: #007bff;
      margin-bottom: 10px;
   }
   h2 {
   font-size: 22px;
   display: flex; /* Use flexbox to align the label and value in a row */
   justify-content: flex-start; /* Align the label and value to the left */
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
<?php include '../components/admin_header.php'; ?>
   
<section class="playlist-details">

   <h1 class="heading">Project details</h1>

   <?php
      // Fetch the project (playlist) details based on its ID and the tutor ID
      $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND tutor_id = ?");
      $select_playlist->execute([$get_id, $tutor_id]);

      if($select_playlist->rowCount() > 0){
         while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
            $playlist_id = $fetch_playlist['id'];

            // Count the number of content (videos) in this project (playlist)
            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_videos->execute([$playlist_id]);
            $total_videos = $count_videos->rowCount();
   ?>
   <div class="row">
      <!-- <div class="thumb">
         <span><?= $total_videos; ?></span>
         <img src="../uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="">
      </div> -->
      <div class="details">
         <h3 class="title"><?= $fetch_playlist['title']; ?></h3>
         <div class="date"><i class="fas fa-calendar"></i><span>
         <?php 
            if (!empty($fetch_playlist['end_date'])) {
                  // Display the end date if it exists
                  echo $fetch_playlist['end_date']; 
            } else {
                  // Otherwise, display the start date (for ongoing projects)
                  echo $fetch_playlist['start_date']; 
            }
         ?>
         </span></div>
         <div class="fund"><h2>Funds: <?= $fetch_playlist['funds']; ?></h2></div>
         <div class="role"><h2>Role: <?= $fetch_playlist['role']; ?></h2></div>
         <h2 style="color: #007bff">Description: </h2>
         <div class="descrip"><?= $fetch_playlist['description']; ?></div>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
            <a href="update_playlist.php?get_id=<?= $playlist_id; ?>" class="option-btn">Update project</a>
            <input type="submit" value="Delete project" class="delete-btn" onclick="return confirm('Delete this project?');" name="delete_playlist">
         </form>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">No project found!</p>';
      }
   ?>

</section>

<!-- <section class="contents">

   <h1 class="heading">Project Content</h1>

   <div class="box-container">

   <?php
      // Fetch content related to this project (playlist)
      $select_videos = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ? AND playlist_id = ?");
      $select_videos->execute([$tutor_id, $playlist_id]);

      if($select_videos->rowCount() > 0){
         while($fetch_videos = $select_videos->fetch(PDO::FETCH_ASSOC)){
            $video_id = $fetch_videos['id'];
   ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-dot-circle" style="<?php if($fetch_videos['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fetch_videos['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fetch_videos['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fetch_videos['date']; ?></span></div>
         </div>
         <img src="../uploaded_files/<?= $fetch_videos['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_videos['title']; ?></h3>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <a href="update_content.php?get_id=<?= $video_id; ?>" class="option-btn">Update</a>
            <input type="submit" value="Delete" class="delete-btn" onclick="return confirm('Delete this content?');" name="delete_video">
         </form>
         <a href="view_content.php?get_id=<?= $video_id; ?>" class="btn">Watch content</a>
      </div>
   <?php
         }
      }else{
         echo '<p class="empty">No content added yet! <a href="add_content.php" class="btn" style="margin-top: 1.5rem;">Add content</a></p>';
      }
   ?>

   </div>

</section> -->

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>
