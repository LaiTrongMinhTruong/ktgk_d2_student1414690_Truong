<?php
include "db_connect.php";
$tensach_err = "";
$tacgia_err = "";
$date_err = "";
$gia_err = "";
$soluong_err = "";
$danhmuc_err = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $book_id = $_GET['id'];
    $query = "select book_id , title , author , publication_date , price , quantity , category_id 
        from books where book_id = $book_id;";
    $data = mysqli_query($conn, $query);
    if (!$data) {
        die("Query failed: " . mysqli_error($conn));
    }
    $result = mysqli_fetch_array($data);

    $tensach = $result['title'];
    $tacgia = $result['author'];
    $date = $result['publication_date'];
    $gia = $result['price'];
    $soluong = $result['quantity'];
    $danhmuc = $result['category_id'];
} else {
    $book_id = $_POST['book_id'];
    if (empty($_POST['tensach'])) {
        $tensach_err = "Ten sach khong duoc de trong";
        $tensach = "";
    } else {
        $tensach = $_POST['tensach'];
    }

    if (empty($_POST['tacgia'])) {
        $tacgia_err = "Tac gia khong duoc de trong";
        $tacgia = "";
    } else {
        $tacgia = $_POST['tacgia'];
    }

    if (empty($_POST['date'])) {
        $date_err = "Ngay xuat ban khong duoc de trong";
        $date = "";
    } else {
        $date = $_POST['date'];
    }

    if (empty($_POST['gia'])) {
        $gia_err = "Gia khong duoc de trong";
        $gia = "";
    } else if ($_POST['gia'] < 0) {
        $gia_err = "Gia khong duoc nho hon 0";
        $gia = "";
    } else {
        $gia = $_POST['gia'];
    }

    if (empty($_POST['soluong'])) {
        $soluong_err = "So luong khong duoc de trong";
        $soluong = "";
    } else if ($_POST['soluong'] < 0) {
        $soluong_err = "So luong khong duoc nho hon 0";
        $soluong = "";
    } else {
        $soluong = $_POST['soluong'];
    }

    if (empty($tensach_err) && empty($tacgia_err) && empty($date_err) && empty($gia_err) && empty($soluong_err)) {
        $sql = "update books set title=?, author=?, publication_date=?, price=?, quantity=?, category_id=? where book_id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sssiiii', $tensach, $tacgia, $date, $gia, $soluong, $danhmuc, $book_id);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Sua thong tin sach thanh cong";
        } else {
            echo "Sua thong tin sach khong thanh cong" . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Trang sửa sách</title>
</head>

<body>

    <h2>Edit book</h2>

    <form action="edit_book.php" method="post">
        <label for="book_id">Book ID</label>
        <input type="text" id="book_id" name="book_id" value="<?= $book_id; ?>" readonly><br>

        <label for="tensach">Ten sach</label>
        <?= $tensach_err; ?>
        <br>
        <input type="text" id="tensach" name="tensach" value="<?= $tensach; ?>"><br>

        <label for="tacgia">Tac gia</label>
        <?= $tacgia_err; ?>
        <br>
        <input type="text" id="tacgia" name="tacgia" value="<?= $tacgia; ?>"><br>

        <label for="date">Ngay xuat ban</label>
        <?= $date_err; ?>
        <br>
        <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($date); ?>"><br>

        <label for="gia">Gia</label>
        <?= $gia_err; ?>
        <br>
        <input type="number" name="gia" id="gia" value="<?= $gia; ?>"><br>

        <label for="soluong">So luong</label>
        <?= $soluong_err; ?>
        <br>
        <input type="number" name="soluong" id="soluong" value="<?= $soluong; ?>"><br>

        <label for="danhmuc">Danh muc</label>
        <select name="danhmuc" id="danhmuc">
            <?php
            $sql = "select * from categories";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
                }
            } else {
                echo "<option value=''>Khong co danh muc</option>";
            }
            ?>
        </select><br>

        <input type="submit" value="Sua thong tin sach">
    </form>

</body>

</html>


<?php mysqli_close($conn); ?>