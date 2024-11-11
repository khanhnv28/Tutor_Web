<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
   $user_id = $_COOKIE['user_id'];
} else {
   $user_id = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Teachers</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link, đảm bảo file style.css ở cuối -->
   <link rel="stylesheet" href="css/style.css">

   <!-- CSS lưới nội tuyến -->
   <style>
      .teacher-grid {
         display: flex;
         flex-wrap: wrap;
         gap: 20px;
         justify-content: center; /* canh giữa các phần tử */
   }  

      .teacher-card {
         width: 200px;
         padding: 20px;
         background-color: #f0f0f0;
         border-radius: 8px;
         text-align: center;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      .tutor-name {
         font-size: 1.1em;
         margin-bottom: 10px;
         font-weight: bold;
      }
      .inline-btn {
         background-color: #6A0DAD;
         color: #fff;
         padding: 8px 16px;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         font-size: 0.9em;
         text-transform: uppercase;
      }
      .inline-btn:hover {
         background-color: #5A0C9C;
      }
      .empty {
         grid-column: 1 / -1;
         text-align: center;
         font-size: 1.2em;
         color: #888;
   <style>
      /* Grid layout for tutors */
      .tutors-list {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
         gap: 20px;
         padding: 20px;
      }
      .tutor-info {
         background-color: #f9f9f9;
         padding: 20px;
         border-radius: 10px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         border: 1px solid #ddd;
      }
      .tutor-info h2 {
         color: blue;
         text-decoration: underline;
      }
      .tutor-info p {
         margin: 5px 0;
      }
      .highlight {
         color: green;
         font-weight: bold;
      }
      .italic {
         font-style: italic;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="teachers">
   <h1 class="heading">Giảng viên chuyên nghiệp</h1>

   <form action="search_tutor.php" method="post" class="search-tutor">
      <input type="text" name="search_tutor" maxlength="100" placeholder="Tìm kiếm giảng viên..." required>
      <button type="submit" name="search_tutor_btn" class="fas fa-search"></button>
   </form>

   <div class="teacher-grid">
   <?php
      $select_tutors = $conn->prepare("SELECT * FROM `tutors`");
      $select_tutors->execute();
      if($select_tutors->rowCount() > 0){
         while($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="teacher-card">
      <p class="tutor-name"><?= htmlspecialchars($fetch_tutor['name']); ?></p>
      <form action="tutor_profile.php" method="post">
         <input type="hidden" name="tutor_email" value="<?= htmlspecialchars($fetch_tutor['email']); ?>">
         <input type="submit" value="View Profiles" name="tutor_fetch" class="inline-btn">
      </form>
   </div>
   <?php
         }
      } else {
         echo '<div class="empty">Không tìm thấy giảng viên!</div>';
      }
   ?>

   <h1 class="heading">Expert Tutors</h1>

   <form action="search_tutor.php" method="post" class="search-tutor">
      <input type="text" name="search_tutor" maxlength="100" placeholder="Search tutor..." required>
      <button type="submit" name="search_tutor_btn" class="fas fa-search"></button>
   </form>

   <div class="tutors-list">
      <?php
         // Select all tutors from the Users table with role_id = 1 (assuming this is the tutor role)
         $select_tutors = $conn->prepare("SELECT * FROM `Users` WHERE role_id = :role_id");
         $select_tutors->execute(['role_id' => 1]); // role_id 1 represents tutors
         
         if ($select_tutors->rowCount() > 0) {
            while ($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)) {

               $tutor_id = $fetch_tutor['userid'];

               // Count total playlists by the tutor
               $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
               $count_playlists->execute([$tutor_id]);
               $total_playlists = $count_playlists->rowCount();

               // Count total contents by the tutor
               $count_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
               $count_contents->execute([$tutor_id]);
               $total_contents = $count_contents->rowCount();

               // Count total likes by the tutor
               $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
               $count_likes->execute([$tutor_id]);
               $total_likes = $count_likes->rowCount();

               // Count total comments by the tutor
               $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
               $count_comments->execute([$tutor_id]);
               $total_comments = $count_comments->rowCount();
      ?>
      <div class="tutor-info">
         <h2><a href="tutor_profile.php?tutor_id=<?= $tutor_id; ?>" class="hyperlink"><?= htmlspecialchars($fetch_tutor['username']); ?></a></h2>
         <p class="italic">Profession: <?= htmlspecialchars($fetch_tutor['degree']); ?></p>
         <p>Playlists: <span class="highlight"><?= $total_playlists; ?></span></p>
         <p>Total Videos: <?= $total_contents ?></p>
         <p>Total Likes: <?= $total_likes ?></p>
         <p>Total Comments: <?= $total_comments ?></p>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">No tutors found!</p>';
         }
      ?>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<!-- teachers section ends -->

<?php include 'components/footer.php'; ?>    

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
