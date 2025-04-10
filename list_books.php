<?php
include 'db_connect.php';


if ($_SERVER['REQUEST_METHOD'] ==  'GET') {
    $query = "select book_id , title , author , publication_date , price , quantity , category_name 
        from books b left join categories c on c.category_id = b.category_id order by price asc;";
    $data = mysqli_query($conn, $query);
    if (!$data) {
        die("Query failed: " . mysqli_error($conn));
    }
    $sort = 'asc';
} else {
    $sort = $_POST['sort'];
    if ($sort == 'asc') {
        $query = "select book_id , title , author , publication_date , price , quantity , category_name 
        from books b left join categories c on c.category_id = b.category_id order by price asc;";
    } else {
        $query = "select book_id , title , author , publication_date , price , quantity , category_name 
        from books b left join categories c on c.category_id = b.category_id order by price desc;";
    }
    $data = mysqli_query($conn, $query);
    if (!$data) {
        die("Query failed: " . mysqli_error($conn));
    }
}
$result = mysqli_fetch_array($data);


?>

<!DOCTYPE html>
<html>

<head>
    <title>Danh sách sách</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 flex flex-col items-center justify-center w-4/5 m-auto">
    <h2 class="uppercase m-auto w-full text-2xl font-bold text-center my-4">Danh sách sách</h2>
    <form action="list_books.php" method="post" class="flex flex-row items-center justify-center w-fit gap-4 border-1 border-gray-300 p-4 rounded-lg shadow-md m-auto">
        <label for="sort">Sắp xếp theo giá: </label>
        <select id="sort" name="sort" class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:cursor-pointer">
            <option value="asc" <?php echo $sort == 'asc' ? "selected" : "" ?> class="block px-4 py-2 hover:bg-gray-100 bg-white text-black">Tăng dần</option>
            <option value="des" <?php echo $sort == 'des' ? "selected" : "" ?> class="block px-4 py-2 hover:bg-gray-100 bg-white text-black">Giảm dần</option>
        </select>
        <input type="submit" value="Sắp xếp" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg hover:cursor-pointer">
    </form>
    <form action="add_book.php" method="get">
        <input type="submit" value="Thêm sách" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg hover:cursor-pointer my-4">
    </form>
    <table>
        <tr>
            <th>Book Id</th>
            <th>Tên sách</th>
            <th>Tên tác giả</th>
            <th>Ngày xuất bản</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Tên danh mục</th>
            <th>Thao tác</th>
            <th>Thao tác</th>
        </tr>
        <?php
        if (mysqli_num_rows($data) == 0) {
            echo "<tr><td colspan='7'>No data found</td></tr>";
        } else {
            foreach ($data as $row) {
                $category_name = $row['category_name'];
                if (empty($category_name)) {
                    $category_name = "Không có danh mục";
                }
                echo "<tr>";
                echo "<td>" . $row['book_id'] . "</td>";
                echo "<td>" . $row['title'] . "</td>";
                echo "<td>" . $row['author'] . "</td>";
                echo "<td>" . $row['publication_date'] . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                echo "<td>" . $row['quantity'] . "</td>";
                echo "<td>" . $category_name . "</td>";
                echo "<td><a href='edit_book.php?id=" . $row['book_id'] . "' class='p-2 rounded-lg bg-orange-500 hover:cursor-pointer font-bold text-white hover:bg-orange-300'>Edit</a></td>";
                echo "<td><a href='delete_book.php?id=" . $row['book_id'] . "' class='p-2 rounded-lg bg-red-500 hover:cursor-pointer font-bold text-white hover:bg-red-300'>Delete</a></td>";
                echo "</tr>";
            }
        }
        ?>
    </table>

</body>

</html>

<?php mysqli_close($conn); ?>