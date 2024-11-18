<?php

include 'components/connect.php';

if(isset($_GET['tutor_id'])){
   $tutor_id = $_GET['tutor_id'];
}else{
   $tutor_id = '';
   header('location:courses.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tutor's Projects</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      body.light-mode {
    background-color: #f9f9f9; /* Light background */
    color: #333; /* Dark text color */
}

.completed-projects,
.ongoing-projects {
    background-color: #ffffff; /* White background for light mode */
    border: 1px solid #ddd; /* Light border */
    border-radius: 8px; /* Rounded corners */
    padding: 20px; /* Padding for content */
    margin-bottom: 20px; /* Space between sections */
}

.completed-projects h2,
.ongoing-projects h2 {
    color: #007bff; /* Blue headings for light mode */
}

/* General Styles for Table */
table {
    width: 100%; /* Ensure the table takes the full width of its container */
    max-width: 1200px; /* Set a maximum width for larger displays */
    margin: 0 auto; /* Center the table within its container */
    border-collapse: collapse; /* Remove space between table cells */
}

/* Padding and Font Size for Cells */
th, td {
    padding: 15px; /* Increased padding inside table cells for more space */
    font-size: 16px; /* Increased font size for better readability */
    text-align: left; /* Left align text */
    border-bottom: 1px solid #ddd; /* Light border for rows */   
}
td {
   word-wrap: break-word;
   white-space: normal;  /* Allow text to wrap */
   overflow-wrap: break-word; /* Break long words */
   max-width: 250px;  /* Optional: Limit width to prevent overly wide cells */
}
th{
   word-wrap: break-word;
   white-space: normal;  /* Allow text to wrap */
   overflow-wrap: break-word; /* Break long words */
   max-width: 250px;  /* Optional: Limit width to prevent overly wide cells */
}

/* Dark Mode Styles */
body.light tr:hover {
    background-color: #f1f1f1; /* Hover effect for table rows in light mode */
}

body.dark tr:hover {
    background-color: #333; /* Hover effect for table rows in dark mode */
}

/* Dark Mode Styles */
body.dark {
    background-color: #333; /* Dark background */
    color: #ffffff; /* Light text color */
}

body.dark .completed-projects,
body.dark .ongoing-projects {
    background-color: #1e1e1e; /* Dark background for sections */
    border: 1px solid #444; /* Darker border */
}

body.dark .completed-projects h2,
body.dark .ongoing-projects h2 {
    color: #bb86fc; /* Purple headings for dark mode */
}

body.dark table {
    border-color: #333; /* Darker border for tables */
}

body.dark th, body.dark td {
    border-bottom: 1px solid #444; /* Darker border for table rows */
}

body.dark tr:hover {
    background-color: #333; /* Hover effect for table rows in dark mode */
}

/* Responsive Styles */
@media (max-width: 768px) {
    table {
        width: 100%; /* Keep full width on smaller screens */
        font-size: 14px; /* Slightly smaller font size for mobile devices */
    }

    th, td {
        padding: 10px; /* Reduced padding for smaller screens */
    }
}
   </style>

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Projects Section Starts -->
<section class="courses">

   <h1 class="heading">Projects</h1>

   <?php
      // Fetch all projects of the tutor
      $select_projects = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
      $select_projects->execute([$tutor_id]);

      if($select_projects->rowCount() > 0) {
         // Separate completed and ongoing projects
         $completed_projects = [];
         $ongoing_projects = [];
         while($fetch_project = $select_projects->fetch(PDO::FETCH_ASSOC)) {
            if($fetch_project['status'] == 'completed') {
               $completed_projects[] = $fetch_project;
            } else {
               $ongoing_projects[] = $fetch_project;
            }
         }
   ?>

   <!-- Completed Projects -->
   <div class="completed-projects">
      <h2>Completed Projects</h2>
      <?php if(count($completed_projects) > 0): ?>
         <table>
            <thead>
               <tr>
                  <th>Project Name</th>
                  <th>Role</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Funds</th>
                  <th>Notes</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach($completed_projects as $project): ?>
               <tr>
                  <td><?= htmlspecialchars($project['title']); ?></td>
                  <td><?= htmlspecialchars($project['role']); ?></td>
                  <td><?= htmlspecialchars($project['start_date']); ?></td>
                  <td><?= htmlspecialchars($project['end_date']); ?></td>
                  <td><?= htmlspecialchars($project['funds']); ?></td>
                  <td><?= nl2br(htmlspecialchars($project['description'])); ?></td>
               </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      <?php else: ?>
         <h3>No completed projects found.</h3>
      <?php endif; ?>
   </div>

   <!-- Ongoing Projects -->
   <div class="ongoing-projects">
      <h2>Ongoing Projects</h2>
      <?php if(count($ongoing_projects) > 0): ?>
         <table>
            <thead>
               <tr>
                  <th>Project Name</th>
                  <th>Role</th>
                  <th>Start Date</th>
                  <th>Funds</th>
                  <th>Notes</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach($ongoing_projects as $project): ?>
               <tr>
                  <td><?= htmlspecialchars($project['title']); ?></td>
                  <td><?= htmlspecialchars($project['role']); ?></td>
                  <td><?= htmlspecialchars($project['start_date']); ?></td>
                  <td><?= htmlspecialchars($project['funds']); ?></td>
                  <td><?= nl2br(htmlspecialchars($project['description'])); ?></td>
               </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      <?php else: ?>
         <h3>No ongoing projects found.</h3>
      <?php endif; ?>
   </div>

   <?php
      } else {
         echo '<p class="empty">No projects found for this tutor!</p>';
      }
   ?>

</section>

<!-- Projects Section Ends -->

<?php include 'components/footer.php'; ?>
<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
