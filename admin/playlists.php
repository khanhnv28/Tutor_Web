<?php

include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    header('location:login.php');
}

if (isset($_POST['delete'])) {
    $delete_id = $_POST['project_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_project = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND tutor_id = ? LIMIT 1");
    $verify_project->execute([$delete_id, $tutor_id]);

    if ($verify_project->rowCount() > 0) {
        // Remove project image if applicable
        $delete_project_thumb = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? LIMIT 1");
        $delete_project_thumb->execute([$delete_id]);
        $fetch_thumb = $delete_project_thumb->fetch(PDO::FETCH_ASSOC);
        
        if (!empty($fetch_thumb['thumb']) && file_exists('../uploaded_files/' . $fetch_thumb['thumb'])) {
            unlink('../uploaded_files/' . $fetch_thumb['thumb']);
        }
        
        // Delete project from playlist
        $delete_project = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
        $delete_project->execute([$delete_id]);
        
        // Delete associated content (if any)
        $delete_content = $conn->prepare("DELETE FROM `content` WHERE playlist_id = ?");
        $delete_content->execute([$delete_id]);

        $message[] = 'Project deleted successfully!';
    } else {
        $message[] = 'Project does not exist!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manage Projects</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
   
   <style>
   /* General styling for projects management section */
   .projects {
      padding: 20px;
      background-color: #f7f7f7;
   }

   .flex {
      margin-bottom: 10px;
   }

   .flex > div {
      display: block; /* Make each child div take a new line */
      margin-bottom: 5px; /* Optional: Add some space between the two divs */
   }

   .flex i {
      font-size: 16px;
   }

   .flex span {
      font-size: 14px;
      color: #555;
   }

   /* Thumbnail styling */
   .thumb {
      position: relative;
      margin-bottom: 15px;
   }

   .thumb img {
      max-width: 100%;
      height: auto;
      border-radius: 5px;
   }

   .thumb span {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: rgba(0, 0, 0, 0.8);
      color: #fff;
      padding: 5px 10px;
      border-radius: 50%;
      font-size: 14px;
   }

   /* Project Title and Description styling */
   .title {
      font-size: 22px;
      color: #007bff;
      margin-bottom: 10px;
      white-space: normal; /* Allow text to wrap */
      word-wrap: break-word; /* Ensure long words break appropriately */
      overflow-wrap: break-word; /* Ensure long words break when needed */
      max-width: 200px; /* Optional: Limit width for better layout */
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
   .description {
      font-size: 16px;
      color: #666;
      margin-bottom: 20px;
      line-height: 1.4;
      white-space: normal; /* Allow text to wrap */
      word-wrap: break-word; /* Ensure long words break appropriately */
      overflow-wrap: break-word; /* Ensure long words break when needed */
      max-width: 200px; /* Optional: Limit width for better layout */
      line-height: 1.5; /* Optional: Control line spacing */
   }

   .empty {
      text-align: center;
      font-size: 18px;
      color: #999;
   }

   .project-table {
    width: 130%;              /* Increase the width for a bigger table */
    max-width: 1300px;        /* Keep the max-width as is */
    margin: 0 auto;           /* Center the table horizontally */
    margin-left: -50px;       /* Move the table slightly to the left */
    border-collapse: collapse;
    background-color: var(--light-bg);
    color: var(--black);
    font-size: 18px;
    }


   .project-table th,
   .project-table td {
      padding: 20px;
      text-align: center;
      border: var(--border);
   }

   .project-table th {
      background-color: var(--main-color);
      color: var(--white);
      font-size: 20px;
   }

   .project-table td {
      background-color: var(--light-bg);
      color: var(--black);
   }

   .project-table tr:nth-child(even) {
      background-color: #f0f0f0;
   }

   .thumb img {
      max-width: 100px;
      height: auto;
      border-radius: 5px;
   }

   /* Responsive styles */
   @media (max-width: 992px) {
      .box {
         flex: 0 0 calc(33.33% - 20px); /* 3 boxes per row */
      }
   }

   @media (max-width: 768px) {
      .box {
         flex: 0 0 calc(50% - 20px); /* 2 boxes per row */
      }
   }

   @media (max-width: 576px) {
      .box {
         flex: 0 0 100%; /* 1 box per row */
      }
   }

   @media (max-width: 768px) {
      .box-container {
         flex-direction: row;
         align-items: center;
      }

      .box {
         width: 90%;
      }

      .heading {
         font-size: 28px;
      }
   }

   @media (max-width: 576px) {
      .box {
         padding: 15px;
      }

      .title {
         font-size: 20px;
      }

      .description {
         font-size: 14px;
      }

      .btn, .option-btn, .delete-btn {
         padding: 8px 12px;
         font-size: 12px;
      }
   }

   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlists">

   <h1 class="heading">Manage Projects</h1>

   <a href="add_playlist.php" class="btn" style="margin-bottom: 20px; display: inline-block;">Add Project</a>

   <table class="project-table">
      <thead>
         <tr>
            <th>Status</th>
            <th>Start - End Date</th>
            <!-- <th>Thumbnail</th> -->
            <th>Title</th>
            <th>Description</th>
            <th>Funds</th>
            <th>Role</th>
            <th>Actions</th>
         </tr>
      </thead>
      <tbody>

      <?php
         $select_projects = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? ORDER BY date_created DESC");
         $select_projects->execute([$tutor_id]);
         if ($select_projects->rowCount() > 0) {
            while ($fetch_project = $select_projects->fetch(PDO::FETCH_ASSOC)) {
               $project_id = $fetch_project['id'];
               $count_content = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
               $count_content->execute([$project_id]);
               $total_content = $count_content->rowCount();
      ?>
         <tr>
            <td>
               <i class="fas fa-circle-dot" style="<?= $fetch_project['status'] == 'completed' ? 'color:limegreen' : 'color:orange'; ?>"></i>
               <span style="<?= $fetch_project['status'] == 'completed' ? 'color:limegreen' : 'color:orange'; ?>"><?= $fetch_project['status']; ?></span>
            </td>
            <td><?= $fetch_project['start_date']; ?> to <?= $fetch_project['end_date'] ?? 'Ongoing'; ?></td>
            <!-- <td class="thumb">
               <img src="../uploaded_files/<?= $fetch_project['thumb']; ?>" alt="Project Thumbnail" width="80">
               <span><?= $total_content; ?></span>
            </td> -->
            <td class="title"><?= $fetch_project['title']; ?></td>
            <td class="description"><?= nl2br(htmlspecialchars($fetch_project['description'])); ?></td>
            <td class="fund"><?= $fetch_project['funds']; ?></td>
            <td class="role"><?= $fetch_project['role']; ?></td>
            <td>
               <form action="" method="post" style="display: inline-block;">
                  <input type="hidden" name="project_id" value="<?= $project_id; ?>">
                  <a href="update_playlist.php?get_id=<?= $project_id; ?>" class="option-btn">Update</a>
                  <input type="submit" value="Delete" class="delete-btn" onclick="return confirm('Are you sure you want to delete this project?');" name="delete">
               </form>
               <a href="view_playlist.php?get_id=<?= $project_id; ?>" class="btn">View</a>
            </td>
         </tr>
      <?php
         } 
      } else {
         echo '<tr><td colspan="6" class="empty">No projects found</td></tr>';
      }
      ?>

      </tbody>
   </table>

</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
