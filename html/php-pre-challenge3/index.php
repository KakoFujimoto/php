<?php

// URLで入力されるtarget値$limitに代入している
$limit = $_GET['target'];

// db接続情報
$dsn = 'mysql:dbname=test;host=mysql';
$dbuser = 'test';
$dbpassword = 'test';

// dbへ接続と接続できなかった際の表示を設定
try {
    // pdoクラスのインスタンス化
    $pdo = new PDO($dsn , $dbuser, $dbpassword);
} catch (PDOException $e) {
    echo 'DBに接続できていません' .$e->getMessage();
}

// prechallenege内の数字をaryListに格納
$sql = "SELECT value FROM prechallenge3";
$sth = $pdo -> query($sql);
$aryList = $sth -> fetchAll(PDO::FETCH_COLUMN);

// 選ぶ元となる全体を配列で与え、抜き取る数を与えると全ての組み合わせを配列で返す関数
function kumiawase($zentai,$nukitorisu){
  $zentaisu=count($zentai);
  if($zentaisu<$nukitorisu){
    return;
  }elseif($nukitorisu==1){
    for($i=0;$i<$zentaisu;$i++){
      $arrs[$i]=array($zentai[$i]);
    }
  }elseif($nukitorisu>1){
    $j=0;
    for($i=0;$i<$zentaisu-$nukitorisu+1;$i++){
      $ts=kumiawase(array_slice($zentai,$i+1),$nukitorisu-1);
      foreach($ts as $t){
        array_unshift($t,$zentai[$i]);
        $arrs[$j]=$t;
        $j++;
      }
    }
  }
  return $arrs;
}

// 合計数$numと組み合わせ処理で得た数が同一かどうか調べ、同一であれば答えの配列に入れる関数
function find_combinations($lis, $num){
  $ans = [];
  for($i=1; $i< count($lis)+1; $i++){
      foreach(kumiawase($lis, $i) as $val){
          if(array_sum($val)==$num){
              array_push($ans, $val);
          } 
      }
  }
  return $ans;
}

// DBから取ってきた配列とtargetで入力された数字での組み合わせを表示
$answer = find_combinations($aryList,$limit);
$json_answer = json_encode($answer);
if($limit >= 1){
  echo ($json_answer);
} else {
  echo 'HTTP400エラー';
}