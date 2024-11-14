<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:home.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Projects Overview</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
<style>
   /* Style for the projects section */
   .projects {
      padding: 20px;
      background-color: #f9f9f9;
   }

   .heading {
      text-align: center;
      font-size: 32px;
      margin-bottom: 20px;
      color: #333;
   }

   .projects-container {
      max-width: 1200px;
      margin: 0 auto;
   }

   .projects-container h2 {
      font-size: 24px;
      margin-bottom: 15px;
      color: #007bff;
   }

   /* Style for the projects table */
   .projects-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 40px;
      background-color: #fff;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
   }

   .projects-table th, .projects-table td {
      padding: 12px 15px;
      text-align: left;
      border: 1px solid #ddd;
      font-size: 16px;
   }

   .projects-table th {
      background-color: #007bff;
      color: white;
      font-weight: bold;
   }

   .projects-table td {
      background-color: #f8f9fa;
      color: #333;
   }

   .projects-table .btn {
      display: inline-block;
      padding: 8px 16px;
      background-color: #28a745;
      color: #fff;
      border-radius: 5px;
      text-decoration: none;
      font-size: 14px;
   }

   .projects-table .option-btn {
      background-color: #ffc107;
   }

   .projects-table .btn:hover {
      opacity: 0.9;
   }

   /* Empty row message */
   .empty {
      text-align: center;
      font-size: 18px;
      color: #999;
   }

   /* Responsive table for smaller screens */
   @media (max-width: 768px) {
      .projects-table th, .projects-table td {
         font-size: 14px;
         padding: 8px;
      }
   }

   @media (max-width: 576px) {
      .projects-table {
         width: 100%;
         font-size: 14px;
      }

      .projects-table th, .projects-table td {
         font-size: 12px;
         padding: 6px;
      }

      .projects-table .btn {
         padding: 6px 12px;
         font-size: 12px;
      }
}

</style>
<?php include 'components/user_header.php'; ?>

<!-- Project section starts  -->

<section class="projects">

   <h1 class="heading">Projects Overview</h1>

   <div class="projects-container">

      <!-- Completed Projects Table -->
      <h1 class="heading">Completed Projects</h1>
      <table class="projects-table">
         <thead>
            <tr>
               <th>Title</th>
               <th>Description</th>
               <th>Start Date</th>
               <th>End Date</th>
               <th>Funds</th>
               <th>Notes</th>
            </tr>
         </thead>
         <tbody>
         <?php
            // Fetch completed projects
            $select_completed_projects = $conn->prepare("SELECT * FROM `playlist` WHERE status = 'completed' AND tutor_id = ?");
            $select_completed_projects->execute([$user_id]);
            if($select_completed_projects->rowCount() > 0){
               while($fetch_project = $select_completed_projects->fetch(PDO::FETCH_ASSOC)){
         ?> 
            <tr>              
               <td><?= $fetch_project['title']; ?></td>
               <td><?= $fetch_project['description']; ?></td>
               <td><?= $fetch_project['start_date']; ?></td>
               <td><?= $fetch_project['end_date']; ?></td>
               <td><?= $fetch_project['funds']; ?></td>
               <td><?= $fetch_project['notes']; ?></td>              
            </tr>
         <?php
               }
            } else {
               echo '<tr><td colspan="8" class="empty">No completed projects found!</td></tr>';
            }
         ?>
         </tbody>
      </table>
      <!-- Ongoing Projects Table -->
      <h1 class="heading">Ongoing Projects</h1>
      <table class="projects-table">
         <thead>
            <tr>
               <th>Title</th>
               <th>Description</th>
               <th>Start Date</th>
               <th>Expected End Date</th>
               <th>Funds</th>
               <th>Notes</th>
            </tr>
         </thead>
         <tbody>
         <?php
            // Fetch ongoing projects
            $select_ongoing_projects = $conn->prepare("SELECT * FROM `playlist` WHERE status ='ongoing' AND tutor_id = ?");
            $select_ongoing_projects->execute([$user_id]);

            if($select_ongoing_projects->rowCount() > 0){
               while($fetch_project = $select_ongoing_projects->fetch(PDO::FETCH_ASSOC)){
         ?>
            <tr>
               <td><?= $fetch_project['title']; ?></td>
               <td><?= $fetch_project['description']; ?></td>
               <td><?= $fetch_project['start_date']; ?></td>
               <td><?= $fetch_project['end_date']; ?></td>
               <td><?= $fetch_project['funds']; ?></td>
               <td><?= $fetch_project['notes']; ?></td>             
            </tr>
         <?php
               }
            } else {
               echo '<tr><td colspan="8" class="empty">No ongoing projects found!</td></tr>';
            }
         ?>
         </tbody>
      </table>

   </div>

</section>

<!-- Project section ends -->

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>
