<?php
// パラメーター
const DB_HOST = "db";
const DB_NAME = "db_test";
const DB_PORT = "3306";
const DB_USER = "docker";
const DB_PASS = "docker";

function create_pdo()
{
    $options = [
        // 接続した後に実行されるコマンド
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET 'utf8'",
        // エラー時の処理 -> 例外をスロー
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // データのフェッチした後のスタイル -> カラム名をキーとする連想配列
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
    try {
        $pdo = new PDO(
            "mysql:dbname=".DB_NAME."; host=".DB_HOST."; port=".DB_PORT."; charset=utf8",
            DB_USER,
            DB_PASS,
            $options
        );
    } catch (Exception $e) {
        error_log($e);
    }
    return $pdo;
}
