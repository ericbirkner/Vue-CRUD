<?php
/**
 * Vue.js with PHP Api
 * @author Gökhan Kaya <0x90kh4n@gmail.com>
 */
error_reporting(0);
// Veritabanı bağlantısı
$db = new PDO("mysql:host=localhost;dbname=test;charset=utf8", "root", "");

// Yardımcı fonksiyon
function safeInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);

  return $data;
}

$json = ['status' => 'ok'];

// Post Request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  //die('es post');
  if($_POST['method']=="add"){
      $name = safeInput($_POST['name']);
      $surname = safeInput($_POST['surname']);

      if ($name && $surname) {

        $query = $db->prepare("INSERT INTO users (name, username) VALUES (:name, :surname)");
        $query->execute(['name' => $name, 'surname' => $surname]);

        $json['users'] = [
          'id' => $db->lastInsertId(),
          'name' => $name,
          'surname' => $surname
        ];

      }else{
        $json['status'] = 'fail';
      }

  }


  if($_POST['method']=="edit"){
      $name = safeInput($_POST['name']);
      $username = safeInput($_POST['username']);
      $user_id = safeInput($_POST['id']);

      if ($name && $username) {

        $query = $db->prepare("UPDATE users set name = :name, username= :username WHERE user_id =:id");
        $query->execute(['name' => $name, 'username' => $username, 'id' => $user_id]);

        $json['status'] = 'ok';

      }else{
        $json['status'] = 'fail';
      }

  }

  if($_POST['method']=="delete"){
    $id = safeInput($_POST['id']);
    $query = $db->prepare("delete from users where user_id =:id");
    $query->execute(['id' => $id]);

  }


// Get Request
} else {

  $query = $db->query("SELECT * FROM users");

  if ($query->rowCount() > 0) {

    $users = $query->fetchAll(PDO::FETCH_OBJ);

    foreach ($users as $user) {
      $json['users'][] = [
        'id' => $user->user_id,
        'name' => $user->name,
        'surname' => $user->username
      ];
    }

  }

}

echo json_encode($json);
