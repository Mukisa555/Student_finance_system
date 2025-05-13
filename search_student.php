<?php
include 'db.php';

$searchTerm = '';
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchTerm = trim($_POST['search']);
    $sql = "SELECT id, student_name, student_code, student_class, image FROM students 
            WHERE student_name LIKE ? OR student_code LIKE ? OR student_class LIKE ?
            ORDER BY student_name ASC LIMIT 20";

    $stmt = $conn->prepare($sql);
    $like = "%$searchTerm%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

foreach ($results as &$student) {
    $imgFile = 'uploads/' . $student['image'];
    $student['imagePath'] = (!empty($student['image']) && file_exists($imgFile)) 
        ? $imgFile 
        : 'assets/images/default.png'; // <-- make sure this default image exists
}
unset($student); // good practice after modifying array by reference


?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Students</title>
    <style>
        body {
            background-color: #eef1f5;
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            margin-bottom: 20px;
            text-align: center;
        }
        input[type="text"] {
            width: 60%;
            padding: 10px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #aaa;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            margin-left: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .result {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .card {
            background: #fafafa;
            width: 260px;
            margin: 15px;
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
            color: #666;
            font-size: 14px;
            margin: 5px 0;
        }
        .card-body a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            background: #28a745;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Search Student</h2>

    <form method="POST">
        <input type="text" name="search" placeholder="Search by name, code, or class" required value="<?= htmlspecialchars($searchTerm) ?>">
        <button type="submit">Search</button>
    </form>

    <div class="result">
        <?php if (!empty($results)): ?>
            <?php foreach ($results as $student): ?>
                <div class="card">
                <img src="<?= htmlspecialchars($student['imagePath']) ?>" alt="Profile">
                    <div class="card-body">
                        <h3><?= htmlspecialchars($student['student_name']) ?></h3>
                        <p>Class: <?= htmlspecialchars($student['student_class']) ?></p>
                        <p>Code: <?= htmlspecialchars($student['student_code']) ?></p>
                        <a href="student_profile.php?code=<?= urlencode($student['student_code']) ?>">View Profile</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p style="text-align:center;">No students found for “<?= htmlspecialchars($searchTerm) ?>”.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
