<?php

session_start();
require_once('funcs.php');
loginCheck();

//1. POSTデータ取得
$name   = $_POST['name'];
$email  = $_POST['email'];
$content = $_POST['content'];
$age    = $_POST['age'];


// 画像アップロードの処理

// 若有圖片檔的話
// 圖片名做リネーム
// 圖片path確認
// 移動至暫存圖片資料夾裡面
$image = '';
if (isset($_FILES['image'])) {
    // 圖片名做リネーム
    //暫存
    $upload_file = $_FILES['image']['tmp_name'];
    //上傳名確認
    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $new_name = uniqid() . '.' . $extension;

    $image_path = 'img/' . $new_name;

    // 暫存檔傳送至img資料夾(保存)
    if (move_uploaded_file($upload_file, $image_path)) {
        $image = $image_path;
    }

}


//2. DB接続します
require_once('funcs.php');
$pdo = db_conn();

//３．データ登録SQL作成
$stmt = $pdo->prepare('INSERT INTO 
                    gs_an_table(name, email, age, content, indate, image)
                    VALUES(:name, :email, :age, :content, sysdate(), :image);');
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':age', $age, PDO::PARAM_INT);
$stmt->bindValue(':content', $content, PDO::PARAM_STR);
$stmt->bindValue(':image', $image, PDO::PARAM_STR);

$status = $stmt->execute(); //実行

//４．データ登録処理後
if ($status == false) {
    sql_error($stmt);
} else {
    redirect('select.php');
}
