<?php

session_start();
require_once('funcs.php');
loginCheck();

//1. POSTデータ取得
$name   = $_POST['name'];
$email  = $_POST['email'];
$content = $_POST['content'];
$age    = $_POST['age'];
$id     = $_POST['id'];

//2. DB接続します
$pdo = db_conn();

//３．データ登録SQL作成
$stmt = $pdo->prepare('UPDATE gs_an_table SET name=:name, email=:email, age=:age, content=:content WHERE id=:id;');
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':age', $age, PDO::PARAM_INT);
$stmt->bindValue(':content', $content, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute(); //実行

//４．データ登録処理後
if ($status === false) {
    sql_error($stmt);
} else {
    redirect('select.php');
}
