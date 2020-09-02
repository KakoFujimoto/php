# FizzBuzz問題

## プログラムの詳細

1から100までの数をforで繰り返しプリントするプログラムです。
但し、3の倍数の時には数の代わりに「3の倍数」とプリントしてください。
更に、5の倍数の時には数の代わりに「5の倍数」とプリントしてください。
最後に、3と5両方の倍数だった場合には、数の代わりに「3の倍数であり、5の倍数」とプリントするようにしてください。

## ディレクトリ解説

```
quelcode-php
├── html ....................... ドキュメントルート
├── mysql5.7
│   ├── mysql .................. 起動すると作られる。データ永続化用
│   ├── mysqlvolume ............ mysqlコンテナにマウントされる。ホストとのファイル受け渡し用
│   └── my.cnf ................. mysqlコンテナの設定ファイル
├── php7.2
│   ├── Dockerfile ............. phpコンテナのDockerファイル
│   └── php.ini ................ phpの設定ファイル
├── .gitignore
├── docker-compose.yml
└── README.md
```

## データベース接続情報
MySQL バージョン 5.7.x


### コンテナ内部から接続する場合
```
host:mysql
port:3306
user:test
password:test
dbname:test
```

### Macから接続する場合
```
host:localhost
port:13306
user:test
password:test
dbname:test
```
