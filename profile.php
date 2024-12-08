<?php
session_start();
include("database.php");

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];

// Fetch user's novels and chapters
$novels_query = "SELECT Novels.novel_id, Novels.title, COUNT(Chapters.chapter_id) AS chapter_count 
                 FROM Novels 
                 LEFT JOIN Chapters ON Novels.novel_id = Chapters.novel_id 
                 WHERE Novels.author = ? 
                 GROUP BY Novels.novel_id";
$stmt = mysqli_prepare($conn, $novels_query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$novels_result = mysqli_stmt_get_result($stmt);

$novels = [];
while ($row = mysqli_fetch_assoc($novels_result)) {
    $novels[] = $row;
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $username; ?>'s Profile</title>
</head>

<body>
    <h1>Welcome, <?php echo $username; ?>!</h1>
    <p>Email: <?php echo $email; ?></p>
    <p>Number of Novels: <?php echo count($novels); ?></p>

    <h2>Your Novels</h2>
    <ul>
        <?php foreach ($novels as $novel) : ?>
            <li>
            <strong><?php echo htmlspecialchars($novel['title']); ?></strong>
            <p>Chapters: <?php echo $novel['chapter_count']; ?></p>
            <a href="view_chapters.php?novel_id=<?php echo $novel['novel_id']; ?>">View Chapters</a> |
            <a href="add_chapter.php?novel_id=<?php echo $novel['novel_id']; ?>">Add New Chapter</a>
            </li>

        <?php endforeach; ?>
    </ul>

    <h2>Add a New Novel</h2>
    <form action="insert_novel.php" method="POST">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>
        <label for="description">Description:</label>
        <textarea name="description" id="description"></textarea>
        <label for="photo">Photo URL:</label>
        <input type="text" name="photo" id="photo">
        <input type="hidden" name="author" value="<?php echo $username; ?>">
        <button type="submit">Add Novel</button>
    </form>
</body>

</html>
