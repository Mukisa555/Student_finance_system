<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php';

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name = $_POST['student_name'];
    $student_code = $_POST['student_code'];
    $student_class = $_POST['student_class'];
    $student_gender = $_POST['student_gender'];
    $student_age = $_POST['student_age'];
    $residential_area = $_POST['residential_area'];
    $father_name = $_POST['father_name'];
    $father_occupation = $_POST['father_occupation'];
    $father_contact = $_POST['father_contact'];
    $father_alt_contact = $_POST['father_alt_contact'];
    $mother_name = $_POST['mother_name'];
    $mother_occupation = $_POST['mother_occupation'];
    $mother_contact = $_POST['mother_contact'];
    $mother_alt_contact = $_POST['mother_alt_contact'];
    $guardian_name = $_POST['guardian_name'];
    $guardian_occupation = $_POST['guardian_occupation'];
    $guardian_contact = $_POST['guardian_contact'];
    $guardian_alt_contact = $_POST['guardian_alt_contact'];
    $school_term = $_POST['school_term'];
    $school_year = $_POST['school_year'];
    $expected_income = $_POST['expected_income'];
    $received_income = $_POST['received_income'];
    $balance_income = $_POST['balance_income'];
    $student_stream = $_POST['school_stream'];
    $entry_year = $_POST['entry_year'];
    $current_school_term = $_POST['current_school_term'];
    $current_school_year = $_POST['current_school_year'];

    // Handle image upload
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $image;

        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);
        } else {
            $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    if (!$error) {
        $sql = "INSERT INTO students (
            student_name, student_code, student_class, student_gender, student_age, residential_area,
            father_name, father_occupation, father_contact, father_alt_contact,
            mother_name, mother_occupation, mother_contact, mother_alt_contact,
            guardian_name, guardian_occupation, guardian_contact, guardian_alt_contact,
            school_term, school_year, expected_income, received_income, balance_income,
            student_stream, entry_year, current_school_term, current_school_year, image, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssissssssssssssssssdddsssss", 
            $student_name, $student_code, $student_class, $student_gender, $student_age, $residential_area,
            $father_name, $father_occupation, $father_contact, $father_alt_contact,
            $mother_name, $mother_occupation, $mother_contact, $mother_alt_contact,
            $guardian_name, $guardian_occupation, $guardian_contact, $guardian_alt_contact,
            $school_term, $school_year, $expected_income, $received_income, $balance_income,
            $student_stream, $entry_year, $current_school_term, $current_school_year, $image,  $created_by
        );

        $created_by = 'admin'; // or $_SESSION['username'] if using login

        if ($stmt->execute()) {
            $success = "Student registered successfully!";
        } else {
            $error = "Failed to register student. Maybe student code already exists.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <style>
        body {
            background: #f7f9fc;
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        .form-box {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        input[type="text"],
        input[type="number"],
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            background: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            color: green;
        }
        .error {
            text-align: center;
            margin-top: 20px;
            color: red;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Register New Student</h2>

    <?php if ($success): ?>
        <div class="message"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Student Name:</label>
        <input type="text" name="student_name" required>

        <label>Student Code:</label>
        <input type="text" name="student_code" required>

        <label>Residential Area:</label>
        <input type="text" name="residential_area" required>

        <label>Class:</label>
        <input type="text" name="student_class" required>

        <label>Gender:</label>
        <select name="student_gender" required>
            <option value="">--Select--</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>

        <label>Age:</label>
        <input type="number" name="student_age" required min="3">

        <label>Father's Name:</label>
        <input type="text" name="father_name" required>
        
        <label>Father's Occupation:</label>
        <input type="text" name="father_occupation" required>

        <label>Father's Contact:</label>
        <input type="text" name="father_contact" required>

        <label>Father's Alternative Contact:</label>
        <input type="text" name="father_alt_contact" required>

        <label>Mother's Name:</label>
        <input type="text" name="mother_name" required>
        
        <label>Mother's Occupation:</label>
        <input type="text" name="mother_occupation" required>

        <label>Mother's Contact:</label>
        <input type="text" name="mother_contact" required>

        <label>Mother's Alternative Contact:</label>
        <input type="text" name="mother_alt_contact" required>

        <label>Guardian's Name:</label>
        <input type="text" name="guardian_name">

        <label>Guardian's Occupation:</label>
        <input type="text" name="guardian_occupation">
        
        <label>Guardian's Contact:</label>
        <input type="text" name="guardian_contact">

        <label>Guardian's Alternative Contact:</label>
        <input type="text" name="guardian_alt_contact">

        <label>School Term:</label>
        <select name="school_term" required>
            <option value="">--Select--</option>
            <option value="term 1">Term 1</option>
            <option value="term 2">Term 2</option>
            <option value="term 3">Term 3</option>
        </select>

        <label>School Year:</label>
        <input type="text" name="school_year" required>

        <label>Expected Income:</label>
        <input type="number" name="expected_income" step="0.01">

        <label>Received Income:</label>
        <input type="number" name="received_income" step="0.01">

        <label>Balance Income:</label>
        <input type="number" name="balance_income" step="0.01">

        <label>School Stream:</label>
        <select name="school_stream" required>
            <option value="">--Select--</option>
            <option value="North">North</option>
            <option value="East">East</option>
            <option value="West">West</option>
            <option value="South">South</option>
        </select>

        <label>Entry Year:</label>
        <input type="text" name="entry_year" required>

        <label>Current School Term:</label>
        <input type="text" name="current_school_term" required>

        <label>Current School Year:</label>
        <input type="text" name="current_school_year" required>

        <label>Profile Image:</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Register Student</button>
    </form>
</div>

</body>
</html>
