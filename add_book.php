<?php
include "db_connect.php";
$tensach_err = "";
$tacgia_err = "";
$date_err = "";
$gia_err = "";
$soluong_err = "";
$danhmuc_err = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $tensach = "";
    $tacgia = "";
    $date = "";
    $gia = "";
    $soluong = "";
    $danhmuc = "";
} else {
    if (empty($_POST['tensach'])) {
        $tensach_err = "Tên sách không được để trốngtrống";
        $tensach = "";
    } else {
        $tensach = $_POST['tensach'];
    }

    if (empty($_POST['tacgia'])) {
        $tacgia_err = "Tác giả không được để trống";
        $tacgia = "";
    } else {
        $tacgia = $_POST['tacgia'];
    }

    if (empty($_POST['date'])) {
        $date_err = "Ngày xuất bản không được để trống";
        $date = "";
    } else {
        $date = $_POST['date'];
    }

    if (empty($_POST['gia'])) {
        $gia_err = "Giá không được để trống";
        $gia = "";
    } else if ($_POST['gia'] < 0) {
        $gia_err = "Gía không được nhỏ hơn 0";
        $gia = "";
    } else {
        $gia = $_POST['gia'];
    }

    if (empty($_POST['soluong'])) {
        $soluong_err = "Số lượng không được để trống";
        $soluong = "";
    } else if ($_POST['soluong'] < 0) {
        $soluong_err = "Số lượng không được nhỏ hơn 0";
        $soluong = "";
    } else {
        $soluong = $_POST['soluong'];
    }

    if (empty($tensach_err) && empty($tacgia_err) && empty($date_err) && empty($gia_err) && empty($soluong_err)) {
        $sql = "insert into books (title, author, publication_date, price, quantity, category_id) values(?, ?, ?, ?, ?, ?);";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssiii", $tensach, $tacgia, $date, $gia, $soluong, $danhmuc);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Them sach thanh cong";
        } else {
            echo "Them sach khong thanh cong";
        }
        mysqli_stmt_close($stmt);
        $tensach = "";
        $tacgia = "";
        $date = "";
        $gia = "";
        $soluong = "";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Trang thêm sách</title>
</head>

<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">

    <h3 class="uppercase m-auto w-full text-2xl font-bold text-center my-4">Add book</h3>

    <form action="add_book.php" method="post" class="">
        <ul class="flex flex-col items-center justify-center w-fit gap-4 border-1 border-gray-300 p-4 rounded-lg shadow-md m-auto">
            <li class="flex flex-row items-center justify-center gap-4">
                <div class="w-1/3 flex flex-col items-center justify-start">
                    <label for="tensach">Tên sách</label>
                    <p><?= $tensach_err; ?></p>
                </div>
                <input class="" type="text" id="tensach" name="tensach" value="<?= $tensach; ?>">
            </li>
            <li class="flex flex-row items-center justify-center gap-4">
                <div class="w-1/2 flex flex-col items-center justify-start">
                    <label for="tacgia">Tác giả</label>
                    <p><?= $tacgia_err; ?></p>
                </div>
                <input class="" type="text" id="tacgia" name="tacgia" value="<?= $tacgia; ?>">
            </li>
            <li class="flex flex-row items-center justify-center gap-4">
                <div class="w-1/2 flex flex-col items-center justify-start">
                    <label for="date">Ngày xuất bản</label>
                    <p><?= $date_err; ?></p>
                </div>
                <input class="" type="date" name="date" id="date" value="<?php echo htmlspecialchars($date); ?>">
            </li>
            <li class="flex flex-row items-center justify-center gap-4">
                <div class="w-1/2 flex flex-col items-center justify-start">
                    <label for="gia">Giá</label>
                    <p><?= $gia_err; ?></p>
                </div>
                <input class="" type="number" name="gia" id="gia" value="<?= $gia; ?>">
            </li>
            <li class="flex flex-row items-center justify-center gap-4">
                <div class="w-1/2 flex flex-col items-center justify-start">
                    <label for="soluong">Số lượng</label>
                    <p><?= $soluong_err; ?></p>
                </div>
                <input class="" type="number" name="soluong" id="soluong" value="<?= $soluong; ?>">
            </li>
            <li class="flex flex-row items-center justify-center gap-4">
                <label for="danhmuc">Danh mục</label>
                <select name="danhmuc" id="danhmuc">
                    <?php
                    $sql = "select * from categories";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Không có danh mục</option>";
                    }
                    ?>
                </select>
            </li>
        </ul>

        <input type="submit" value="Thêm sách" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg hover:cursor-pointer mt-4 w-full flex justify-center">
    </form>
</body>

</html>

<?php mysqli_close($conn); ?>