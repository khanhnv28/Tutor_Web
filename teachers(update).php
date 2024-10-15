<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
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

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
      table {
         width: 100%;
         border-collapse: collapse;
      }
      table, th, td {
         border: 1px solid black;
      }
      th, td {
         padding: 10px;
         text-align: left;
      }
      .hyperlink {
         color: blue;
         text-decoration: underline;
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

<!-- teachers section starts  -->

<section class="teachers">

   <h1 class="heading">Expert Tutors</h1>

   <form action="search_tutor.php" method="post" class="search-tutor">
      <input type="text" name="search_tutor" maxlength="100" placeholder="Search tutor..." required>
      <button type="submit" name="search_tutor_btn" class="fas fa-search"></button>
   </form>

   <table>
      <thead>
         <tr>
            <th>Name</th>
            <th>Profession</th>
            <th>Playlists</th>
            <th>Total Videos</th>
            <th>Total Likes</th>
            <th>Total Comments</th>
         </tr>
      </thead>
      <tbody>
         <?php
            $select_tutors = $conn->prepare("SELECT * FROM `tutors`");
            $select_tutors->execute();
            if($select_tutors->rowCount() > 0){
               while($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)){

                  $tutor_id = $fetch_tutor['id'];

                  $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
                  $count_playlists->execute([$tutor_id]);
                  $total_playlists = $count_playlists->rowCount();

                  $count_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
                  $count_contents->execute([$tutor_id]);
                  $total_contents = $count_contents->rowCount();

                  $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
                  $count_likes->execute([$tutor_id]);
                  $total_likes = $count_likes->rowCount();

                  $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
                  $count_comments->execute([$tutor_id]);
                  $total_comments = $count_comments->rowCount();
         ?>
         <tr>
            <td>
               <a href="tutor_profile.php?tutor_id=<?= $tutor_id; ?>" class="hyperlink"><?= $fetch_tutor['name']; ?></a>
            </td>
            <td class="italic"><?= $fetch_tutor['profession']; ?></td>
            <td><span class="highlight"><?= $total_playlists; ?></span></td>
            <td><?= $total_contents ?></td>
            <td><?= $total_likes ?></td>
            <td><?= $total_comments ?></td>
         </tr>
         <?php
               }
            }else{
               echo '<tr><td colspan="6" class="empty">No tutors found!</td></tr>';
            }
         ?>
      </tbody>
   </table>

</section>

<!-- teachers section ends -->

<?php include 'components/footer.php'; ?>    

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>
