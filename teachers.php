<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

// Lấy danh sách các trường đại học từ cơ sở dữ liệu
$select_universities = $conn->prepare("SELECT DISTINCT university FROM `tutors`");
$select_universities->execute();

// Xử lý tìm kiếm theo tên giảng viên và trường đại học
$whereClause = '';
$params = [];

if (isset($_POST['university_search']) || isset($_POST['search_tutor_btn'])) {
    $search_tutor = $_POST['search_tutor'] ?? '';
    $search_tutor = filter_var($search_tutor, FILTER_SANITIZE_STRING);

    $university = $_POST['university'] ?? '';
    $university = filter_var($university, FILTER_SANITIZE_STRING);

    // Tìm kiếm theo tên giảng viên và/hoặc trường đại học
    if ($search_tutor && $university) {
        $whereClause = "WHERE name LIKE ? AND university = ?";
        $params[] = "%" . $search_tutor . "%";
        $params[] = $university;
    } elseif ($search_tutor) {
        $whereClause = "WHERE name LIKE ?";
        $params[] = "%" . $search_tutor . "%";
    } elseif ($university) {
        $whereClause = "WHERE university = ?";
        $params[] = $university;
    }
}

$query = "SELECT * FROM `tutors` $whereClause";
$select_tutors = $conn->prepare($query);
$select_tutors->execute($params);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Custom CSS File Link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .teacher-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .teacher-card {
            width: 250px;
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .teacher-card h3 {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .inline-btn {
            background-color: #6A0DAD;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            margin: 5px;
            text-transform: uppercase;
        }

        .inline-btn:hover {
            background-color: #5A0C9C;
        }

        .empty {
            text-align: center;
            font-size: 1.2em;
            color: #888;
        }

        select {
            padding: 8px;
            font-size: 1em;
            margin-right: 10px;
        }

        .search-btn {
            padding: 8px 16px;
            background-color: #6A0DAD;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .search-btn:hover {
            background-color: #5A0C9C;
        }
    </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="teachers">
    <h1 class="heading">Professional Tutors</h1>

    <!-- Tìm kiếm theo tên giảng viên và trường đại học -->
    <form action="teachers.php" method="post" class="search-tutor">
        <input type="text" name="search_tutor" maxlength="100" placeholder="Search for a tutor by name..." value="<?= isset($_POST['search_tutor']) ? htmlspecialchars($_POST['search_tutor']) : ''; ?>">
        <select name="university">
            <option value="" disabled selected>Select University</option>
            <?php
            while ($fetch_university = $select_universities->fetch(PDO::FETCH_ASSOC)) {
                $selected = isset($_POST['university']) && $_POST['university'] == $fetch_university['university'] ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($fetch_university['university']) . '" ' . $selected . '>' . htmlspecialchars($fetch_university['university']) . '</option>';
            }
            ?>
        </select>
        <button type="submit" name="university_search" class="fas fa-search"></button>
    </form>

    <!-- Hiển thị giảng viên -->
    <div class="teacher-grid">
        <?php
        if ($select_tutors->rowCount() > 0) {
            while ($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)) {
        ?>
            <div class="teacher-card">
                <img src="uploaded_files/<?= htmlspecialchars($fetch_tutor['image']); ?>" alt="<?= htmlspecialchars($fetch_tutor['name']); ?>" style="width: 100px; height: 100px; border-radius: 50%; margin-bottom: 10px;">
                <h3><?= htmlspecialchars($fetch_tutor['name']); ?></h3>
                <p><?= htmlspecialchars($fetch_tutor['faculty']); ?></p>
                <form action="teachers.php" method="post">
                    <input type="hidden" name="tutor_email" value="<?= htmlspecialchars($fetch_tutor['email']); ?>">
                    <button type="submit" name="action" value="projects" class="inline-btn">View Projects</button>
                </form>
                <form action="tutor_profile.php" method="post">
                    <input type="hidden" name="tutor_email" value="<?= htmlspecialchars($fetch_tutor['email']); ?>">
                    <input type="submit" value="View Profiles" name="tutor_fetch" class="inline-btn">
                </form>
            </div>
        <?php
            }
        } else {
            echo '<div class="empty">No tutors found!</div>';
        }
        ?>
    </div>
</section>

<?php include 'components/footer.php'; ?>

<!-- Custom JS File Link -->
<script src="js/script.js"></script>

</body>
</html>
