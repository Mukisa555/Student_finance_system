<?php
include 'db.php';

$code = $_GET['code'];
$student = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['student_name'];
    $age = $_POST['student_age'];
    $gender = $_POST['student_gender'];
    $image = $_FILES['image']['name'] ?? '';

    $stmt = $conn->prepare("SELECT image FROM students WHERE student_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $stmt->bind_result($existing_image);
    $stmt->fetch();
    $stmt->close();

    $newImage = $existing_image;

    if (!empty($image)) {
        $targetDir = "uploads/";
        $fileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes) && $_FILES['image']['size'] <= 2 * 1024 * 1024) {
            $newImage = time() . "_" . basename($image);
            move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $newImage);
        } else {
            $error = "❌ Invalid image file. Only JPG, PNG, GIF under 2MB allowed.";
        }
    }

    if (!isset($error)) {
        $update = $conn->prepare("UPDATE students SET student_name=?, student_age=?, student_gender=?, image=? WHERE student_code=?");
        $update->bind_param("sisss", $name, $age, $gender, $newImage, $code);
        $update->execute();
        $success = "✅ Student updated successfully!";
    }
} else {
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
}

$imagePath = (!empty($student['image']) && file_exists('uploads/' . $student['image']))
    ? 'uploads/' . $student['image']
    : 'assets/images/default.png'; // make sure this image exists
?>



<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <style>
        body {
            background: #f9f9fb;
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
            color: #333;
        }

        .form-container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="number"],
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .preview {
            text-align: center;
            margin-bottom: 20px;
        }

        .preview img {
            max-width: 150px;
            border-radius: 8px;
        }

        .message {
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
    <script>
        function previewImage(event) {
            const [file] = event.target.files;
            if (file) {
                const img = document.getElementById('imgPreview');
                img.src = URL.createObjectURL(file);
                img.style.display = 'block';
            }
        }
    </script>
</head>
<body>

<div class="form-container">
    <h2>Edit Student</h2>

    <?php if (isset($success)): ?>
        <div class="message success"><?= $success ?></div>
    <?php elseif (isset($error)): ?>
        <div class="message error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="student_name" value="<?= htmlspecialchars($student['student_name']) ?>" required>

        <label>Age:</label>
        <input type="number" name="student_age" value="<?= $student['student_age'] ?>" required>

        <label>Gender:</label>
        <select name="student_gender" required>
            <option value="male" <?= $student['student_gender'] == 'male' ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= $student['student_gender'] == 'female' ? 'selected' : '' ?>>Female</option>
        </select>

        <label>Age:</label>
        <input type="number" name="student_age" required min="3">

        <label>Father's Name:</label>
        <input type="text" name="father_name" required>
        
        <label>Father's Occupation:</label>
        <input type="text" name="father_occupation" required>

        <label>Father's Contact:</label>
        <input type="text" name="father_contact" required>

        <label>Father's Alternative  Contact:</label>
        <input  type="text" name="father_alt_contact" required>

        <label>Mother's Name:</label>
        <input type="text" name="mother_name" required>
        
        <label>Mother's Occupation:</label>
        <input type="text" name="mother's  occupation" required>

        <label>Mother's Contact:</label>
        <input type="text"  name="mother_contact" required>

        <label>Mother's Alternative Contact:</label>
        <input type="text" name="mother_alt_contact" required>

        <label>Guardian's  Name:</label>
        <input type="text" name="guardian_name">

        <label>Guardian's Occupation:</label>
        <input type="text" name="guardian_name">
        
        <label>Guardian's Contact:</label>
        <input type="text" name="guardian_contact">

        <label>Guardian's Alternative Contact:</label>
        <input type="text" name="guardian_alt_contact">

        <label>School_Term:</label>
        <select name="school_term" required>
            <option value="">--Select--</option>
            <option value="term 1">Term 1</option>
            <option value="term 2">Term 2</option>
            <option value="term 3">Term 3</option>
        </select>


        <label>School_Year:</label>
        <input type="text" name="school_year" required>

        <label>Expected Income:</label>
        <input type="number" name="expected_income" step="0.01">

        <label>Received Income:</label>
        <input type="number" name="received_income" step="0.01">

        <label>Balance Income:</label>
        <input type="number" name="balance_income" step="0.01">

        <label>School_Stream:</label>
        <select name="school_stream" required>
            <option value="">--Select--</option>
            <option value="North">North</option>
            <option value="East">East</option>
            <option value="West">West</option>
            <option value="South">South</option>
        </select>


        <label>Entry_Year:</label>
        <input  type="text" name="entyr_year" required>

        <label>Current_School_Term:</label>
        <input  type="text" name="current_school_term" required>

        <label>Current_School_Year:</label>
        <input  type="text" name="current_school_year" required>

        <label>Current Image:</label>
        <div class="preview">
        <img id="imgPreview" src="<?= $imagePath ?>" alt="Student Image">
        </div>

        <label>Upload New Image (optional):</label>
        <input type="file" name="image" accept="image/*" onchange="previewImage(event)">

        <button type="submit">Update Student</button>
    </form>
</div>

</body>
</html>
