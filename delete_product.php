<?php
/* 
ベースをsyukka.phpからコピー
*/

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

//③SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
if($_SESSION['login'] == false){
	//④SESSIONの「error2」に「ログインしてください」と設定する。
	$_SESSION['error2']='ログインしてください';
	//⑤ログイン画面へ遷移する。
	header('location:login.php');
}


//⑥データベースへ接続し、接続情報を変数に保存する
//⑦データベースで使用する文字コードを「UTF8」にする
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

//⑧POSTの「books」の値が空か判定する。空の場合はif文の中に入る。
if(empty($_POST['books'])){
 	//⑨SESSIONの「success」に「出荷する商品が選択されていません」と設定する。
	$_SESSION['success']='削除する商品が選択されていません';
	//⑩在庫一覧画面へ遷移する。
	header('location:zaiko_ichiran.php');
}


function getById($id,$con){
	/* 
	 * ⑪書籍を取得するSQLを作成する実行する。
	 * その際にWHERE句でメソッドの引数の$idに一致する書籍のみ取得する。
	 * SQLの実行結果を変数に保存する。
	 */

$sql = "SELECT * FROM books WHERE id = {$id}";
	$query = $con->query($sql);
	$extract = $query->fetch(PDO::FETCH_ASSOC);
	//⑫実行した結果から1レコード取得し、returnで値を返す。
	return $extract;
}

function deleteByid($id,$con){

	$sql = "DELETE FROM books WHERE id = {$id}";
	$query = $con->query($sql);
	$extract = $query->fetch(PDO::FETCH_ASSOC);

	return $extract;
}


if(!empty($_POST['delete']) == 'ok'){
	//㉕POSTの「books」から値を取得し、変数に設定する。
	foreach($_POST['books'] as $book_id){
		//㉘「deleteByid」関数を呼び出す。コメント外すと実際に削除される
		// $delete_book = deleteByid($book_id, $pdo);
	}

	//㉚SESSIONの「success」に「入荷が完了しました」と設定する。
	$_SESSION['success'] = '削除が完了しました';
	//㉛「header」関数を使用して在庫一覧画面へ遷移する。
	header('location:zaiko_ichiran.php');
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>出荷</title>
<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
<!-- ヘッダ -->
<div id="header">
	<h1>商品削除</h1>
</div>

<!-- メニュー -->
<div id="menu">
	<nav>
		<ul>
			<li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
		</ul>
	</nav>
</div>

<form action="delete_product.php" method="post">
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
					</tr>
				</thead>
				<?php 
				/*
				 * ⑮POSTの「books」から一つずつ値を取り出し、変数に保存する。
				 */
				foreach($_POST['books'] as $value){
					// ⑯「getId」関数を呼び出し、変数に戻り値を入れる。その際引数に⑮の処理で取得した値と⑥のDBの接続情報を渡す。
					$book = getById($value, $pdo);
				?>
				<input type="hidden" value="<?php echo $book['id'];?>" name="books[]">
				<tr>
					<td><?php echo	$book['id'];?></td>
					<td><?php echo	$book['title'];?></td>
					<td><?php echo	$book['author'];?></td>
					<td><?php echo	$book['salesDate'];?></td>
					<td><?php echo	$book['price'];?></td>
					<td><?php echo	$book['stock'];?></td>
				</tr>
				<?php
				}
				?>
			</table>
			<button type="submit" id="kakutei" formmethod="POST" name="delete" value="ok">確定</button>
		</div>
	</div>
</form>
<!-- フッター -->
<div id="footer">
	<footer>株式会社アクロイト</footer>
</div>
</body>
</html>
