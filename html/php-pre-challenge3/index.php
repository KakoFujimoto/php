<?php

// URLで入力されるtarget値$limitに代入している
$limit = $_GET['target'];

// もしlimitが１以上の整数でなければ400を返す
if (preg_match('/^([1-9]\d*|0)\.(\d+)?$/', $limit) || $limit < 1) {
  http_response_code(400);
  $errMsg = 'invalid limit: ' . $limit;
  echo json_encode($errMsg, JSON_UNESCAPED_UNICODE);
  die();
}

// db接続情報
$dsn = 'mysql:dbname=test;host=mysql';
$dbuser = 'test';
$dbpassword = 'test';

// dbへ接続と接続できなかった際の表示を設定
try {
  // pdoクラスのインスタンス化
  $pdo = new PDO($dsn, $dbuser, $dbpassword);
} catch (PDOException $e) {
  $errMsg = 'DBに接続できていません' . $e->getMessage();
  echo json_encode($errMsg);
  die();
}

// prechallenege内の数字をaryListに格納
$sql = "SELECT value FROM prechallenge3";
$sth = $pdo->query($sql);
$aryList = $sth->fetchAll(PDO::FETCH_COLUMN);

// 選ぶ元となる全体を配列で与え、抜き取る数を与えると全ての組み合わせを配列で返す関数
function combination($whole, $pickupNumber)
{
  $wholeNumber = count($whole);
  if ($wholeNumber < $pickupNumber) {
    return;
  } elseif ($pickupNumber == 1) {
    for ($i = 0; $i < $wholeNumber; $i++) {
      $arrs[$i] = array($whole[$i]);
    }
  } elseif ($pickupNumber > 1) {
    $j = 0;
    for ($i = 0; $i < $wholeNumber - $pickupNumber + 1; $i++) {
      $cuts = combination(array_slice($whole, $i + 1), $pickupNumber - 1);
      foreach ($cuts as $cut) {
        array_unshift($cut, $whole[$i]);
        $arrs[$j] = $cut;
        $j++;
      }
    }
  }
  return $arrs;
}

// 合計数$numと組み合わせ処理で得た数が同一かどうか調べ、同一であれば答えの配列に入れる関数
function find_combinations($lis, $num)
{
  $answer_list = [];
  for ($i = 1; $i < count($lis) + 1; $i++) {
    foreach (combination($lis, $i) as $val) {
      if (array_sum($val) == $num) {
        array_push($answer_list, $val);
      }
    }
  }
  return $answer_list;
}

// DBから取ってきた配列とtargetで入力された数字での組み合わせを表示
$answer = find_combinations($aryList, $limit);
$json_answer = json_encode($answer);
echo ($json_answer);
