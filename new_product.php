<?php

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

if($_SESSION['login']==false){
	$_SESSION['error2']='ログインしてください';
	header('location:login.php');
}

$db_name = 'zaiko2020_yse';
$host = 'localhost';
$user_name = 'root';
$password = '';
$dsn = "mysql:dbname={$db_name};host={$host};charset=utf8";
try {
    $pdo = new PDO($dsn, $user_name, $password);
} catch (PDOException $e) {
    exit;
}

function getId($con){
$sql = "SELECT MAX(id) + 1 as id FROM books";
    $query = $con->query($sql);
    $extract = $query->fetch(PDO::FETCH_ASSOC);
    return $extract;
    // $sql = "SELECT max(id) as mx from books";
    // $query = $con->query($sql);
    // return $query->fetch_assoc();
}

function addRecord($con){
    $id=$book['id'];
    $title=$_POST['title'];
    $author=$_POST['author'];
    $salesDate=$_POST["salesDate"];
    $price=$_POST['price'];
    $stock=$_POST['stock'];

    $sql = "INSERT INTO books(id,title, author, salesDate, price, stock)VALUES('{$id}','{$title}', '{$author}','{$salesDate}','{$price}', '{$stock}')";
    $con->query($sql);
}
if(!empty($_POST['newbook']) == 'ok'){
    addRecord($pdo);
	$_SESSION["success"] = "新商品の追加が完了しました";
    header('location: zaiko_ichiran.php');
	exit;
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>新商品追加</title>
<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
<!-- ヘッダ -->
<div id="header">
	<h1>新商品追加</h1>
</div>

<!-- メニュー -->
<div id="menu">
	<nav>
		<ul>
			<li><a href="zaiko_ichiran.php?page=1">追加書籍</a></li>
		</ul>
	</nav>
</div>
<form action="new_product.php" method="post">
	<div id="pagebody">
		<!-- エラーメッセージ -->
		<div id="error">
		<?php
		/*
		 * ⑬SESSIONの「error」にメッセージが設定されているかを判定する。
		 * 設定されていた場合はif文の中に入る。
		 */ 
		if(!empty($_SESSION['error'])){
			//⑭SESSIONの「error」の中身を表示する。
			echo ($_SESSION['error']);
		}
        ?>
        </div>
        <div id="center">
            <table>
                <thead>
                    <tr>
                        <th id="id">ID</th>
                        <th id="book_name">書籍名</th>
                        <th id="author">著者名</th>
                        <th id="salesDate">発売日</th>
                        <th id="itemPrice">金額(円)</th>
                        <th id="stock">在庫数</th>
                        <!-- <th id="new">入荷数</th> -->
                    </tr>
                </thead>
                <?php 
                $book = getId($pdo);
                ?>
                <input type="hidden" value="<?php echo $book["id"]; ?>" name="books[]">
                <tr>
                    <td><?php echo	$book['id'];?></td>
                    <td><input type='text' name='title' size='5' maxlength='11' required></td>
                    <td><input type='text' name='author' size='5' maxlength='11' required></td>
                    <td><input type='text' name='salesDate'size='5' maxlength='11' required></td>
                    <td><input type='text' name='price' size='5' maxlength='11' required></td>
                    <td><input type='text' name='stock' size='5' maxlength='11' required></td>
                    <!-- <td><input type='text' name='new[]' size='5' maxlength='11' required></td> -->
                </tr>
            </table>
            <button type="submit" id="kakutei" formmethod="POST" name="newbook" value="ok">確定</button>
		</div>
	</div>
</form>
<!-- フッター -->
<div id="footer">
	<footer>株式会社アクロイト</footer>
</div>
</body>
</html>