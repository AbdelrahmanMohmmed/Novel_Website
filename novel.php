<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Include the database connection file
include("database.php");

// Fetch the novel based on ID from URL and validate it
$novel_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : null;

if (!$novel_id) {
    // Redirect if no valid novel_id is found in URL
    header("Location: index.php");
    exit;
}

// Fetch novel details
$novel_query = "SELECT title, author, created_at AS date, description FROM Novels WHERE novel_id = ?";
$stmt = mysqli_prepare($conn, $novel_query);
if (!$stmt) {
    die("Query preparation failed: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "i", $novel_id);
mysqli_stmt_execute($stmt);
$novel_result = mysqli_stmt_get_result($stmt);
$novel = mysqli_fetch_assoc($novel_result);
mysqli_stmt_close($stmt);

// Redirect if the novel is not found
if (!$novel) {
    header("Location: index.php");
    exit;
}

// Fetch chapters for the selected novel
$chapters_query = "SELECT chapter_number, title FROM Chapters WHERE novel_id = ? ORDER BY chapter_number ASC";
$stmt = mysqli_prepare($conn, $chapters_query);
if (!$stmt) {
    die("Query preparation failed: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "i", $novel_id);
mysqli_stmt_execute($stmt);
$chapters_result = mysqli_stmt_get_result($stmt);

// Store chapters in an array
$chapters = [];
while ($chapter = mysqli_fetch_assoc($chapters_result)) {
    $chapters[] = $chapter;
}
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($novel['title']); ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="navlist">
        <a href="index.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="contactUs.php">Contact Us</a>
        <div class="search">
            <form action="">
                <input type="text" placeholder="Search Novels" name="search">
            </form>
        </div>
    </div>
    <main>
        <h1><?php echo htmlspecialchars($novel['title']); ?></h1>
        <p><strong>Author:</strong> <?php echo htmlspecialchars($novel['author']); ?></p>
        <p><strong>Published Date:</strong> <?php echo htmlspecialchars($novel['date']); ?></p>
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($novel['description'])); ?></p>

        <h3>Chapters</h3>
        <ul id="chapters-list">
            <?php foreach ($chapters as $chapter): ?>
                <li>
                    <a href="chapter.php?id=<?php echo $novel_id; ?>&chapter=<?php echo $chapter['chapter_number']; ?>">
                        <?php echo htmlspecialchars($chapter['title']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
    <footer>
        <div>This Website is made by College students as a project for their Web Programming course<br>
            Contact Us<br>
            01228774305</div>
    </footer>
</body>
</html>

<?php
// Free the result sets
mysqli_free_result($chapters_result);
mysqli_free_result($novel_result);

// Close the database connection
mysqli_close($conn);
?>
