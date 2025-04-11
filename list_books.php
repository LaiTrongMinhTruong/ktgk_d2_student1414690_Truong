<?php
include 'db_connect.php';

$limit = 10;

if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$total_query = "SELECT COUNT(*) AS total from books b left join categories c on c.category_id = b.category_id ";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_books = $total_row['total'];

$total_pages = ceil($total_books / $limit);

if ($_SERVER['REQUEST_METHOD'] ==  'GET') {
    $query = "select book_id , title , author , publication_date , price , quantity , category_name 
        from books b left join categories c on c.category_id = b.category_id 
        order by price asc 
        LIMIT $limit OFFSET $offset;";
    $data = mysqli_query($conn, $query);
    if (!$data) {
        die("Query failed: " . mysqli_error($conn));
    }
    $sort = 'asc';
    $search_title = "";
    $search_author = "";
    $search_category = "";
    
} else {

    $sort = $_POST['sort'];
    $search_title = $_POST['search_title'];
    $search_author = $_POST['search_author'];
    $search_category = $_POST['search_category'];

    $total_query = "SELECT COUNT(*) AS total from books b left join categories c on c.category_id = b.category_id where b.title like '%$search_title%' and b.author like '%$search_author%' and c.category_name like '%$search_category%'";
    $total_result = mysqli_query($conn, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_books = $total_row['total'];
    
    $total_pages = ceil($total_books / $limit);

    $query = "select book_id , title , author , publication_date , price , quantity , category_name 
    from books b left join categories c on c.category_id = b.category_id 
    where b.title like '%$search_title%' and b.author like '%$search_author%' and c.category_name like '%$search_category%'
    order by price $sort
    LIMIT $limit OFFSET $offset;";

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

    .pagination {
        margin-top: 20px;
    }

    .pagination a,
    .pagination span.current {
        padding: 8px 12px;
        border: 1px solid #ccc;
        text-decoration: none;
        margin-right: 5px;
    }

    .pagination a:hover {
        background-color: #f0f0f0;
    }

    .pagination span.current {
        background-color: #ddd;
        color: #333;
    }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script>
    function areYouSure() {
        if (confirm("ARE YOU SURE THAT YOU WANT TO DELETE?")) {
            return true;
        } else {
            return false;
        }
    }
    </script>
</head>

<body class="bg-gray-100 flex flex-col items-center justify-center w-4/5 m-auto">
    <h2 class="uppercase m-auto w-full text-2xl font-bold text-center my-4">Danh sách sách</h2>
    <form action="list_books.php" method="post" class="flex flex-col items-center justify-center w-fit gap-4 border-1 border-gray-300 p-4 rounded-lg shadow-md m-auto">
        <div
            class="flex flex-row items-center justify-center w-fit gap-4 m-auto">
            <label for="sort">Sắp xếp theo giá: </label>
            <select id="sort" name="sort" class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:cursor-pointer">
                <option value="asc" <?php echo $sort == 'asc' ? "selected" : "" ?>
                    class="block px-4 py-2 hover:bg-gray-100 bg-white text-black">Tăng dần</option>
                <option value="desc" <?php echo $sort == 'desc' ? "selected" : "" ?>
                    class="block px-4 py-2 hover:bg-gray-100 bg-white text-black">Giảm dần</option>
            </select>
        </div>
        <div>
            <input class="bg-gray-200 rounded-lg p-2" type="text" name="search_title" placeholder="Tìm kiếm theo tên sách" value = <?= $search_title ?>>
            <input class="bg-gray-200 rounded-lg p-2" type="text" name="search_author" placeholder="Tìm kiếm theo tác giả" value = <?= $search_author ?>>
            <input class="bg-gray-200 rounded-lg p-2" type="text" name="search_category" placeholder="Tìm kiếm theo danh mục" value = <?= $search_category ?>>
        </div>
        <input type="submit" value="Tìm kiếm và sắp xếp"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg hover:cursor-pointer">
    </form>
    <form action="add_book.php" method="get">
        <input type="submit" value="Thêm sách"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg hover:cursor-pointer my-4">
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
                echo "<td><a href='delete_book.php?id=" . $row['book_id'] . "' class='p-2 rounded-lg bg-red-500 hover:cursor-pointer font-bold text-white hover:bg-red-300' onclick='return areYouSure()'>Delete</a></td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
    <?php
        echo "<div class='pagination'>";
        if ($page > 1) {
            echo "<a href='?page=" . ($page - 1) . "'>&laquo; Trước</a> ";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo "<span class='current'>" . $i . "</span> ";
            } else {
                echo "<a href='?page=" . $i . "'>" . $i . "</a> ";
            }
        }

        if ($page < $total_pages) {
            echo "<a href='?page=" . ($page + 1) . "'>Sau &raquo;</a>";
        }
        echo "</div>";
        ?>

</body>

</html>

<?php mysqli_close($conn); ?>