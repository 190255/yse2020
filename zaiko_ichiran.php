<?php
/* 
【機能】
書籍テーブルより書籍情報を取得し、画面に表示する。
商品をチェックし、ボタンを押すことで入荷、出荷が行える。
ログアウトボタン押下時に、セッション情報を削除しログイン画面に遷移する。

【エラー一覧（エラー表示：発生条件）】
入荷する商品が選択されていません：商品が一つも選択されていない状態で入荷ボタンを押す
出荷する商品が選択されていません：商品が一つも選択されていない状態で出荷ボタンを押す
*/

//①セッションを開始する
session_start();
session_regenerate_id(true);
 
//②SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
if ($_SESSION['login'] == false){
	//③SESSIONの「error2」に「ログインしてください」と設定する。
	$_SESSION['error2'] = 'ログインしてください';
	//④ログイン画面へ遷移する。
	header('location:login.php');
}

//⑤データベースへ接続し、接続情報を変数に保存する
$sql = "SELECT * FROM books";
//⑥データベースで使用する文字コードを「UTF8」にする
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

//⑦書籍テーブルから書籍情報を取得するSQLを実行する。また実行結果を変数に保存する
$query = $pdo->query($sql);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>書籍一覧</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<div id="header">
		<h1>書籍一覧</h1>
	</div>
	<form action="zaiko_ichiran.php" method="post" id="myform" name="myform">
		<div id="pagebody">
			<!-- エラーメッセージ表示 -->
			<div id="error">
				<?php
				/*
				 * ⑧SESSIONの「success」にメッセージが設定されているかを判定する。
				 * 設定されていた場合はif文の中に入る。
				 */ 
				if(!empty($_SESSION['success'])){
					//⑨SESSIONの「success」の中身を表示する。
					echo $_SESSION['success'];
				}
				?>
			</div>
			
			<!-- 左メニュー -->
			<div id="left">
				<p id="ninsyou_ippan">
					<?php
						echo @$_SESSION["account_name"];
					?><br>
					<button type="button" id="logout" onclick="location.href='logout.php'">ログアウト</button>
				</p>
				<button type="submit" id="btn1" formmethod="POST" name="decision" value="3" formaction="nyuka.php">入荷</button>

				<button type="submit" id="btn1" formmethod="POST" name="decision" value="4" formaction="syukka.php">出荷</button>
			</div>
			<!-- 中央表示 -->
			<div id="center">

				<!-- 書籍一覧の表示 -->
				<table>
					<thead>
						<tr>
							<th></th>
							<th>ID</th>
							<th>書籍名</th>
							<th>著者名</th>
							<th>発売日</th>
							<th>金額</th>
							<th>在庫数</th>
						</tr>
					</thead>
					<tbody>
						<?php
						//⑩SQLの実行結果の変数から1レコードのデータを取り出す。レコードがない場合はループを終了する。
						while($extract = $query->fetch(PDO::FETCH_ASSOC)){
							//⑪extract変数を使用し、1レコードのデータを渡す。

							echo "<tr>";
							echo "<td><input type='checkbox' name='books[]'value=".$extract['id']."></td>";
							echo "<td>{$extract['id']}</td>";
							echo "<td>{$extract['title']}</td>";
							echo "<td>{$extract['author']}</td>";
							echo "<td>{$extract['salesDate']}</td>";
							echo "<td>{$extract['price']}</td>";
							echo "<td>{$extract['stock']}</td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</form>
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>
</html>
