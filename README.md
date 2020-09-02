# 配列操作に関する問題

[コードを見る](https://github.com/KakoFujimoto/quelcode-php/blob/feature/php-pre-challenge2/html/php-pre-challenge2/index.php)

- GETパラーメータ[array]で受け取った配列を、ソート関数を使わず並べ替えるプログラムです。

- リクエスト(例)
```
http://localhost:10080/php-pre-challenge2/index.php?array=3,2,1,4,15,18,13,99,77,66,1,100,0
```


- 出力(例)
print_rをした際に、次の形式で出力がなされます。

```
Array
(

    [0] => 0
    [1] => 1
    [2] => 1
    [3] => 2
    [4] => 3
    [5] => 4
    [6] => 13
    [7] => 15
    [8] => 18
    [9] => 66
    [10] => 77
    [11] => 99
    [12] => 100
)
```

## ファイル情報
```
feature/php-pre-challenge2/html/php-pre-challenge2/index.php
```
