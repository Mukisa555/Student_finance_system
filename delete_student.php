<?php
include 'db.php';

if (!isset($_GET['code'])) {
    die("No student code specified.");
}

$code = $_GET['code'];

// Optional: delete image file
$stmt = $conn->prepare("SELECT image FROM students WHERE student_code=?");
$stmt->bind_param("s", $code);
$stmt->execute();
$imageResult = $stmt->get_result()->fetch_assoc();

if ($imageResult && $imageResult['image']) {
    $imagePath = 'uploads/' . $imageResult['image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

$stmt = $conn->prepare("DELETE FROM students WHERE student_code=?");
$stmt->bind_param("s", $code);

if ($stmt->execute()) {
    header("Location: search_student.php?msg=deleted");
    exit;
} else {
    echo "Failed to delete student.";
}
?>
