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
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
