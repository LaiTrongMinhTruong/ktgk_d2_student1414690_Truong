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

    <body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">
        <h3 class="uppercase m-auto w-full text-2xl font-bold text-center my-4">Xóa sách</h3>
        <p class="font-bold text-xl text-gray-600 uppercase">Lệnh xóa sách có ID = <?php echo $book_id; ?> được thực
            hiện
            <?php echo $error ? "thất bại" : "thành công"; ?></p>
        <a href="list_books.php"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg hover:cursor-pointer mt-4 w-full flex justify-center">Quay
            trở lại danh sách</a>
    </body>

    </html>
    <?php mysqli_close($conn); ?>