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
   <title>Courses</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- courses section starts  -->
<style>
   .courses .box-container {
   display: grid;
   grid-template-columns: repeat(4, 1fr); /* 4 columns */
   gap: 20px; /* Spacing between the boxes */
}

.courses .box {
   background-color: #fff;
   border-radius: 10px;
   padding: 15px;
   box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.courses .tutor {
   display: flex;
   align-items: center;
   margin-bottom: 10px;
}

.courses .tutor img {
   width: 50px;
   height: 50px;
   border-radius: 50%;
   object-fit: cover;
   margin-right: 15px;
}

.courses .thumb {
   width: 100%;
   height: auto;
   border-radius: 10px;
   margin: 10px 0;
}

.courses .title {
   font-size: 18px;
   font-weight: bold;
   margin-bottom: 10px;
}

.courses .inline-btn {
   display: inline-block;
   padding: 10px 20px;
   background-color: #007bff;
   color: #fff;
   border-radius: 5px;
   text-align: center;
   text-decoration: none;
}

@media (max-width: 1200px) {
   .courses .box-container {
      grid-template-columns: repeat(3, 1fr); /* 3 columns for smaller screens */
   }
}

@media (max-width: 768px) {
   .courses .box-container {
      grid-template-columns: repeat(2, 1fr); /* 2 columns for tablets */
   }
}

@media (max-width: 576px) {
   .courses .box-container {
      grid-template-columns: 1fr; /* 1 column for mobile devices */
   }
}

</style>
<section class="courses">

   <h1 class="heading">Tutors</h1>

   <div class="box-container">

      <?php
         // Select all tutors
         $select_tutors = $conn->prepare("SELECT * FROM `tutors`");
         $select_tutors->execute();
         if($select_tutors->rowCount() > 0){
            while($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)){
               $tutor_id = $fetch_tutor['id'];

               // Count projects for the tutor
               $select_courses_count = $conn->prepare("SELECT COUNT(*) as total_projects FROM `playlist` WHERE tutor_id = ?");
               $select_courses_count->execute([$tutor_id]);
               $fetch_courses_count = $select_courses_count->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span>Total Projects: <?= $fetch_courses_count['total_projects']; ?></span>
            </div>
         </div>
         <a href="tutor_projects.php?tutor_id=<?= $tutor_id; ?>" class="inline-btn">View Projects</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">No tutors available!</p>';
      }
      ?>

   </div>

</section>

<!-- courses section ends -->

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>
