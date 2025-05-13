<?php
include 'db.php';

// Fetch all students
$sql = "SELECT id, student_name, student_class, student_code, student_stream, image FROM students ORDER BY student_name ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f3f6;
            padding: 30px;
        }
        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .students {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .card {
            width: 260px;
            background: #fafafa;
            margin-bottom: 25px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: #ddd;
        }
        .card-body {
            padding: 15px;
            text-align: center;
        }
        .card-body h3 {
            margin: 0;
            font-size: 18px;
            color: #222;
        }
        .card-body p {
            margin: 4px 0;
            color: #666;
            font-size: 14px;
        }
        .card-body a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>All Registered Students</h2>

    <div class="students">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($student = $result->fetch_assoc()): ?>
                <div class="card">
                <img src="<?= !empty($student['image']) && file_exists('uploads/' . $student['image']) ? 'uploads/' . $student['image'] : 'assets/images/default.png' ?>" alt="Image">
                    <div class="card-body">
                        <h3><?= htmlspecialchars($student['student_name']) ?></h3>
                        <p>Class: <?= htmlspecialchars($student['student_class']) ?></p>
                        <p>Code: <?= htmlspecialchars($student['student_code']) ?></p>
                        <p>Stream: <?= htmlspecialchars($student['student_stream']) ?></p>
                        <a href="student_profile.php?code=<?= urlencode($student['student_code']) ?>">View Profile</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; width:100%;">No students found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
