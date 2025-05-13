<?php
include 'db.php';

if (!isset($_GET['code'])) {
    die("No student code provided.");
}

$code = $_GET['code'];
$stmt = $conn->prepare("SELECT * FROM students WHERE student_code = ?");
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($student['student_name']) ?> - Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f5f9;
            padding: 30px;
        }
        .profile-box {
            max-width: 900px;
            margin: auto;
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        .header {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .header img {
            width: 140px;
            height: 140px;
            border-radius: 10px;
            object-fit: cover;
            background: #eee;
        }
        .header h2 {
            margin: 0;
            color: #333;
        }
        .details {
            margin-top: 25px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .section {
            background: #fafafa;
            padding: 15px;
            border-radius: 10px;
        }
        .section h3 {
            margin-top: 0;
            color: #007bff;
        }
        .section p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            margin-top: 25px;
        }
        .footer a {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="profile-box">
    <div class="header">
    <?php
$imagePath = !empty($student['image']) && file_exists('uploads/' . $student['image'])
? 'uploads/' . $student['image']
: 'assets/images/default.png';
?>
<img src="<?= $imagePath ?>" alt="Student Image" style="width: 120px; height: 120px; object-fit: cover; border-radius: 10px;">

        <div>
            <h2><?= htmlspecialchars($student['student_name']) ?></h2>
            <p><strong>Student Code:</strong> <?= htmlspecialchars($student['student_code']) ?></p>
            <p><strong>Class:</strong> <?= htmlspecialchars($student['student_class']) ?> |
               <strong>Stream:</strong> <?= htmlspecialchars($student['student_stream']) ?></p>
        </div>
    </div>

    <div class="details">
        <div class="section">
            <h3>Personal Info</h3>
            <p><strong>Age:</strong> <?= $student['student_age'] ?></p>
            <p><strong>Gender:</strong> <?= $student['student_gender'] ?></p>
            <p><strong>Residential Area:</strong> <?= $student['residential_area'] ?></p>
            <p><strong>Entry Year:</strong> <?= $student['entry_year'] ?></p>
        </div>

        <div class="section">
            <h3>Academic Info</h3>
            <p><strong>Current Term:</strong> <?= $student['current_school_term'] ?></p>
            <p><strong>Current Year:</strong> <?= $student['current_school_year'] ?></p>
            <p><strong>School Term:</strong> <?= $student['school_term'] ?></p>
            <p><strong>School Year:</strong> <?= $student['school_year'] ?></p>
        </div>

        <div class="section">
            <h3>Parent Details</h3>
            <p><strong>Father:</strong> <?= $student['father_name'] ?> <?= $student['father_occupation'] ?></p>
            <p><strong>Contact:</strong> <?= $student['father_contact'] ?> <?= $student['father_alt_contact'] ?></p>
            <p><strong>Mother:</strong> <?= $student['mother_name'] ?> <?= $student['mother_occupation'] ?></p>
            <p><strong>Contact:</strong> <?= $student['mother_contact'] ?>  <?= $student['mother_alt_contact'] ?></p>
        </div>

        <div class="section">
            <h3>Guardian Details</h3>
            <p><strong>Name:</strong> <?= $student['guardian_name'] ?> <?= $student['guardian_occupation'] ?></p>
            <p><strong>Contact:</strong> <?= $student['guardian_contact'] ?>  <?= $student['guardian_alt_contact'] ?></p>
        </div>

        <div class="section">
            <h3>Finance</h3>
            <p><strong>Expected:</strong> UGX <?= number_format($student['expected_income']) ?></p>
            <p><strong>Received:</strong> UGX <?= number_format($student['received_income']) ?></p>
            <p><strong>Balance:</strong> UGX <?= number_format($student['balance_income']) ?></p>
        </div>
    </div>

    <div class="footer">
    <div class="footer">
    <a href="edit_student.php?code=<?= urlencode($student['student_code']) ?>">Edit</a>
    |
    <a href="delete_student.php?code=<?= urlencode($student['student_code']) ?>" onclick="return confirm('Are you sure you want to delete this student?');" style="color:red;">Delete</a>
    |
    <a href="search_student.php">← Back</a>
</div>
</div>

</body>
</html>
