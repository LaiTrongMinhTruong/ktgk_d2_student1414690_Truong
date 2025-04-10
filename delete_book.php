<?php
include "db_connect.php";
$book_id = $_GET['id'];
$sql = "delete from books where book_id = $book_id;";
$data = mysqli_query($conn, $sql);
if (!$data) {
    $error = true;
    die("Query failed: " . mysqli_error($conn));
} else {
    $error = false;
}
?>
<doctype html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <title>Trang xóa sách</title>
    </head>

    <body>
        <h2>Xóa sách</h2>
        <p>Lệnh xóa sách có ID = <?php echo $book_id; ?> được thực hiện <?php echo $error ? "thất bại" : "thành công"; ?></p>
        <a href="list_books.php">Quay trở lại danh sách</a>
    </body>

    </html>
    <?php mysqli_close($conn); ?>