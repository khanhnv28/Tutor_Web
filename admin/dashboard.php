<?php
include '../components/connect.php';

// Ensure the tutor is logged in via cookie
if (isset($_COOKIE['tutor_id'])) {
   $tutor_id = $_COOKIE['tutor_id'];
} else {
   $tutor_id = '';
   header('location:login.php');
   exit;
}

// Fetch tutor details
$select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
$select_profile->execute([$tutor_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

// Fetch contents, playlists, likes, and comments related to the tutor
$select_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
$select_contents->execute([$tutor_id]);
$total_contents = $select_contents->rowCount();

$select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
$select_playlists->execute([$tutor_id]);
$total_playlists = $select_playlists->rowCount();

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
   <title>Dashboard</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      /* Bố cục giống Teacher */
      .dashboard-grid {
         display: grid;
         grid-template-columns: repeat(4, 1fr); /* 4 cột cho mỗi dòng */
         gap: 20px;
         padding: 20px;
      }

      .dashboard-card {
         background-color: #f8f8f8;
         padding: 20px;
         border-radius: 8px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         text-align: center;
         overflow: hidden;
         transition: transform 0.3s ease;
      }

      .dashboard-card:hover {
         transform: translateY(-10px);
      }

      .dashboard-card h3 {
         color: #333;
         font-size: 1.8rem;
         margin-bottom: 10px;
      }

      .dashboard-card p {
         color: #666;
         font-size: 1rem;
         margin-bottom: 15px;
      }

      .dashboard-card a {
         display: inline-block;
         margin-top: 10px;
         background-color: #6A0DAD;
         color: #fff;
         padding: 10px 20px;
         border-radius: 5px;
         text-transform: uppercase;
         font-size: 0.9rem;
         text-decoration: none;
         transition: background-color 0.3s ease;
      }

      .dashboard-card a:hover {
         background-color: #5A0C9C;
      }

      .heading {
         text-align: center;
         margin-bottom: 20px;
         font-size: 2rem;
         color: #333;
         text-transform: uppercase;
      }

      /* Responsive Design */
      @media screen and (max-width: 1200px) {
         .dashboard-grid {
            grid-template-columns: repeat(3, 1fr); /* 3 cột khi màn hình nhỏ */
         }
      }

      @media screen and (max-width: 900px) {
         .dashboard-grid {
            grid-template-columns: repeat(2, 1fr); /* 2 cột khi màn hình còn nhỏ hơn */
         }
      }

      @media screen and (max-width: 600px) {
         .dashboard-grid {
            grid-template-columns: 1fr; /* 1 cột khi màn hình di động */
         }
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">

   <h1 class="heading">Dashboard</h1>

   <div class="dashboard-grid">

      <div class="dashboard-card">
         <h3>Welcome!</h3>
         <p><?= isset($fetch_profile['name']) ? htmlspecialchars($fetch_profile['name']) : 'Guest'; ?></p>
         <a href="profile.php">View Profile</a>
      </div>

      <div class="dashboard-card">
         <h3><?= $total_contents; ?></h3>
         <p>Total Contents</p>
         <a href="add_content.php">Add New Content</a>
      </div>

      <div class="dashboard-card">
         <h3><?= $total_playlists; ?></h3>
         <p>Total Projects</p>
         <a href="add_playlist.php">Add New Projects</a>
      </div>

      <div class="dashboard-card">
         <h3><?= $total_likes; ?></h3>
         <p>Total Likes</p>
         <a href="contents.php">View Contents</a>
      </div>

      <div class="dashboard-card">
         <h3><?= $total_comments; ?></h3>
         <p>Total Comments</p>
         <a href="comments.php">View Comments</a>
      </div>

      <div class="dashboard-card">
         <h3>Quick Select</h3>
         <p>Login or Register</p>
         <a href="login.php" style="margin-right: 10px;">Login</a>
         <a href="register.php">Register</a>
      </div>

   </div>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>
