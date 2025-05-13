<?php
include 'db.php';

// Total students
$total = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];

// Income summary
$income = $conn->query("SELECT 
    SUM(expected_income) as expected, 
    SUM(received_income) as received, 
    SUM(balance_income) as balance 
    FROM students")->fetch_assoc();

// Students by class
$classes = $conn->query("SELECT student_class, COUNT(*) as count FROM students GROUP BY student_class");

// Recent students
$recent = $conn->query("SELECT student_name, student_class, student_code, image FROM students ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        .flex-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .card h2 {
            margin-top: 0;
        }
        .stat-box {
            flex: 1;
            min-width: 220px;
            background: #fff;
            padding: 20px;
            border-left: 6px solid #007bff;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.07);
        }
        .stat-box h3 {
            margin: 0;
            color: #007bff;
        }
        .stat-box p {
            font-size: 24px;
            font-weight: bold;
            margin: 8px 0 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f0f0f0;
        }
        .student-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Student Dashboard</h1>

    <div class="flex-row">
        <div class="stat-box">
            <h3>Total Students</h3>
            <p><?= $total ?></p>
        </div>
        <div class="stat-box">
            <h3>Expected Income</h3>
            <p>UG
