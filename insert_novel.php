<?php
session_start();
include("database.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $photo = $_POST['photo'];
    $author = $_SESSION['username'];

    $query = "INSERT INTO Novels (title, author, description, photo) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $title, $author, $description, $photo);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: profile.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
