<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>
<style>
  /* Style cho toàn bộ trang welcome */
.welcome-container {
   position: relative;
   height: 100vh; /* Chiều cao của container sẽ bằng chiều cao của viewport */
   display: flex;
   justify-content: center;
   align-items: center;
   background-color: #222; /* Nền tối nếu ảnh không tải được */
   overflow: hidden;
}

.slideshow-container {
   position: relative;
   width: 100%;
   height: 100%;
}

.welcome-image {
   width: 100vw;
   height: 100vh;
   object-fit: cover; /* Giữ tỷ lệ ảnh và che toàn màn hình */
   opacity: 0.7; /* Mờ nhẹ */
   transition: opacity 2s ease-in-out;
}

.welcome-image:hover {
   opacity: 1; /* Làm rõ hình ảnh khi hover */
}

/* Hiệu ứng overlay tối trên hình ảnh */
.welcome-overlay {
   position: absolute;
   top: 0;
   left: 0;
   width: 100%;
   height: 100%;
   background: rgba(0, 0, 0, 0.5); /* Tạo lớp phủ tối để làm nổi bật văn bản */
}

/* Style cho văn bản chào mừng */
.welcome-text {
   position: absolute;
   top: 50%;
   left: 50%;
   transform: translate(-50%, -50%);
   color: #fff;
   text-align: center;
   z-index: 2;
   font-family: 'Arial', sans-serif;
   text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7); /* Bóng chữ giúp dễ đọc hơn */
}

.welcome-text h1 {
   font-size: 56px;
   font-weight: bold;
   margin: 0 0 20px 0;
   animation: fadeInDown 1.5s ease; /* Hiệu ứng chữ xuất hiện từ trên xuống */
}

.welcome-text p {
   font-size: 24px;
   margin-bottom: 30px;
   animation: fadeInUp 2s ease; /* Hiệu ứng chữ xuất hiện từ dưới lên */
}
</style>
<div class="welcome-container">
   <div class="slideshow-container">
      <div class="mySlides fade">
         <img src="images/slide4.jpg" class="welcome-image" alt="Welcome Image">
      </div>
      <div class="welcome-overlay"></div>
      <div class="welcome-text">
         <h1>Welcome to Our University Website</h1>
         <p>Your gateway to a world of knowledge and innovation</p>
         <a href="about.php" class="inline-btn">Learn More</a>
         <a href="contact.php" class="inline-btn">Contact Us</a>
      </div>
   </div>
</div>




<!-- quick select section starts  -->

<section class="quick-select">

   <h1 class="heading">Quick options</h1>

   <div class="box-container">

      <?php
         if($user_id != ''){
      ?>
      <div class="box">
         <h3 class="title">Likes and comments</h3>
         <p>Total likes : <span><?= $total_likes; ?></span></p>
         <a href="likes.php" class="inline-btn">View likes</a>
         <p>Total comments : <span><?= $total_comments; ?></span></p>
         <a href="comments.php" class="inline-btn">View comments</a>
         <p>Saved playlist : <span><?= $total_bookmarked; ?></span></p>
         <a href="bookmark.php" class="inline-btn">View bookmark</a>
      </div>
      <?php
         }else{ 
      ?>
      
      <?php
      }
      ?>

      <div class="box">
         <h3 class="title">Popular topics</h3>
         <div class="flex">
            <a href="#"><i class="fab fa-java"></i><span>Java</span></a>
            <a href="#"><i class="fab fa-python"></i><span>Python</span></a>
            <a href="#"><i class="fab fa-html5"></i><span>HTML</span></a>
            <a href="#"><i class="fab fa-css3"></i><span>CSS</span></a>
            <a href="#"><i class="fab fa-js"></i><span>Javascript</span></a>
            <a href="#"><i class="fab fa-react"></i><span>React</span></a>
            <a href="#"><i class="fab fa-php"></i><span>PHP</span></a>
            <a href="#"><i class="fab fa-bootstrap"></i><span>Bootstrap</span></a>
         </div>
      </div>

      <div class="box tutor">
         <h3 class="title">Become a tutor</h3>
         <p>Share your knowledge, inspire students, and grow your professional brand. Join our community of expert educators and start teaching today</p>
         <a href="register_combine.php" class="inline-btn">Join now</a>
      </div>
      


   </div>

</section>

<!-- quick select section ends -->

<!-- courses section starts  -->

<section class="courses">

   <h1 class="heading">Latest courses</h1>
   
   <div class="box-container">

      <?php
         // Fetch the latest completed course
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date_created DESC LIMIT 1");
         $select_courses->execute(['completed']);
         if($select_courses->rowCount() > 0){
            $fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC);
            $course_id = $fetch_course['id'];
            
            // Fetch the associated tutor for the course
            $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
            $select_tutor->execute([$fetch_course['tutor_id']]);
            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date_created']; ?></span>
            </div>
         </div>
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         <a href="tutor_projects.php?tutor_id=<?= $fetch_tutor['id']; ?>" class="inline-btn">View all projects</a>
      </div>
      <?php
         }else{
            echo '<p class="empty">No courses added yet!</p>';
         }
      ?>

   </div>

   <div class="more-btn">
      <a href="courses.php" class="inline-option-btn">View more</a>
   </div>

</section>


<!-- courses section ends -->

<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>