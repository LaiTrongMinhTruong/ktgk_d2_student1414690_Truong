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
    $danhmuc = $_POST['danhmuc'];

    if (empty($tensach_err) && empty($tacgia_err) && empty($date_err) && empty($gia_err) && empty($soluong_err)) {
        $sql = "update books set title=?, author=?, publication_date=?, price=?, quantity=?, category_id=? where book_id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sssiiii', $tensach, $tacgia, $date, $gia, $soluong, $danhmuc, $book_id);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<div class='text-3xl font-bold uppercase'>Sửa thông tin sách thành công</div>";
            echo '<button
            class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg hover:cursor-pointer mt-4 w-fit flex justify-center"><a href="list_books.php">Quay lại trang danh sách</a></button>';
        } else {
            echo "<div class='text-3xl font-bold uppercase'>Sửa thông tin sách không thành công, </div>";
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

<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">

    <h3 class="uppercase m-auto w-full text-2xl font-bold text-center my-4">Edit book</h3>

    <form action="edit_book.php" method="post">
        <ul
            class="flex flex-col items-center justify-center w-fit gap-4 border-1 border-gray-300 p-4 rounded-lg shadow-md m-auto">
            <li class="flex flex-row items-stretch justify-between gap-4 w-full">
                <label for="book_id">Book ID</label>
                <input class="px-2 bg-gray-400 border border-gray-500 rounded-lg" type="text" id="book_id"
                    name="book_id" value="<?= $book_id; ?>" readonly>
            </li>

            <li class="flex flex-row items-stretch justify-between gap-4 w-full">
                <div class="w-1/2 flex flex-col justify-start text-left">
                    <label for="tensach">Tên sách</label>
                    <p class="text-red-500 italic font-light text-sm"><?= $tensach_err; ?></p>
                </div>

                <input class="px-2 bg-gray-400 border border-gray-500 rounded-lg" type="text" id="tensach"
                    name="tensach" value="<?= $tensach; ?>">
            </li>

            <li class="flex flex-row items-stretch justify-between gap-4 w-full">
                <div class="w-1/2 flex flex-col justify-start text-left">
                    <label for="tacgia">Tác giả</label>
                    <p class="text-red-500 italic font-light text-sm"><?= $tacgia_err; ?></p>
                </div>

                <input class="px-2 bg-gray-400 border border-gray-500 rounded-lg" type="text" id="tacgia" name="tacgia"
                    value="<?= $tacgia; ?>">
            </li>

            <li class="flex flex-row items-stretch justify-between gap-4 w-full">
                <div class="w-1/2 flex flex-col justify-start text-left">
                    <label for="date">Ngày xuất bản</label>
                    <p class="text-red-500 italic font-light text-sm"><?= $date_err; ?></p>
                </div>

                <input class="px-2 bg-gray-400 border border-gray-500 rounded-lg" type="date" name="date" id="date"
                    value="<?php echo htmlspecialchars($date); ?>">
            </li>

            <li class="flex flex-row items-stretch justify-between gap-4 w-full">
                <div class="w-1/2 flex flex-col justify-start text-left">
                    <label for="gia">Giá</label>
                    <p class="text-red-500 italic font-light text-sm"><?= $gia_err; ?></p>
                </div>

                <input class="px-2 bg-gray-400 border border-gray-500 rounded-lg" type="number" name="gia" id="gia"
                    value="<?= $gia; ?>">
            </li>

            <li class="flex flex-row items-stretch justify-between gap-4 w-full">
                <div class="w-1/2 flex flex-col justify-start text-left">
                    <label for="soluong">Số lượng</label>
                    <p class="text-red-500 italic font-light text-sm"><?= $soluong_err; ?></p>
                </div>

                <input class="px-2 bg-gray-400 border border-gray-500 rounded-lg" type="number" name="soluong"
                    id="soluong" value="<?= $soluong; ?>">
            </li>

            <li class="flex flex-row items-stretch justify-between gap-4 w-full">
                <label for="danhmuc">Danh mục</label>
                <select name="danhmuc" id="danhmuc">
                    <?php
                        $sql = "select * from categories";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $choice = $row['category_id'] == $danhmuc ? "selected" : "";
                                echo "<option value='" . $row['category_id'] . "' " . $choice .">" . $row['category_name'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>Không có danh mục</option>";
                        }
                        ?>
                </select>
            </li>

            <input type="submit" value="Sửa thông tin sách"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg hover:cursor-pointer mt-4 w-full flex justify-center">
    </form>
    <a class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg hover:cursor-pointer mt-4 w-full flex justify-center"
        href="list_books.php">Huỷ</a>

</body>

</html>


<?php mysqli_close($conn); ?>