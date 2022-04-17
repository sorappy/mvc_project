<?php

//管理ページのログインパスワード
define( 'PASSWORD', 'adminPassword');

// データベースの接続情報
define( 'DB_HOST', 'mysql');
define( 'DB_USER', 'root');
define( 'DB_PASS', 'root');
define( 'DB_NAME', 'board');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// 変数の初期化
$current_date = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();
$pdo = null;
$stmt = null;
$res = null;
$option = null;
$limit = null;


session_start();

//取得件数
if(!empty($_GET['limit'])){
    if($_GET['limit'] === "10"){
        $limit = 10;
    } elseif ( $_GET['limit'] === "30" ){
        $limit = 30;
    }
}

if( !empty($_SESSION['admin_login']) && $_SESSION['admin_login'] === true ) {
    //データベースに接続
    try {
        $option = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
        );
        $pdo = new PDO('mysql:charset=UTF8;dbname='.DB_NAME.';host='.DB_HOST , DB_USER, DB_PASS, $option);

        //メッセージのデータを取得する
//        $sql = "SELECT * FROM message ORDER BY post_date ASC";
//        $message_array = $pdo->query($sql);
        if( !empty($limit) ) {
            //SQL作成
            $stmt = $pdo->prepare("SELECT * FROM message ORDER BY post_date ASC LIMIT :limit");
            //値をセット
            $stmt->bindValue( ':limit', $limit, PDO::PARAM_INT );
        } else {
            $stmt = $pdo->prepare("SELECT * FROM message ORDER BY post_date ASC");
        }

        //SQLクエリの実行
        $stmt->execute();
        $message_array = $stmt->fetchAll();

        // データベースの接続を閉じる
        $stmt = null;
        $pdo = null;
    } catch(PDOException $e) {
        //管理者ページへリダイレクト
        header("Location: ./admin.php");
        exit;
    }

    //出力の設定
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=メッセージデータ.csv");
    header("Content-Transfer-Encoding: binary");

    //csvデータを作成
    if( !empty($message_array) ) {
        //１行目のラベル作成
        $csv_data .= '"ID","表示名","メッセージ","投稿日時"."\n"';

        foreach( $message_array as $value ) {
            //データを１行ずつCSVファイルに書き込む
            $csv_data .= '""' .$value['id'] . '","' . $value['view_name'] . '","' . $value['message'] . '","' . $value['post_data'] . "\"\n";
        }
    } 
    // ファイルを出力	
	echo $csv_data;
} else {
    //ログインページリダイレクト
    header("Location: ./admin.php");
    exit;
}

return;
?>
