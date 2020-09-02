# 【PHPコーディング】

## 使用書籍
<a href="https://www.amazon.co.jp/%E3%82%88%E3%81%8F%E3%82%8F%E3%81%8B%E3%82%8BPHP%E3%81%AE%E6%95%99%E7%A7%91%E6%9B%B8-%E3%80%90PHP7%E5%AF%BE%E5%BF%9C%E7%89%88%E3%80%91-%E6%95%99%E7%A7%91%E6%9B%B8%E3%82%B7%E3%83%AA%E3%83%BC%E3%82%BA-%E3%81%9F%E3%81%AB%E3%81%90%E3%81%A1-%E3%81%BE%E3%81%93%E3%81%A8-ebook/dp/B07C3QQKTX/">よくわかるPHPの教科書【PHP7対応版】/たにぐちまこと著</a>


## ブランチ詳細

- [feature/php-pre-challenge1/html/php-pre-challenge/...掲示板への２種の機能追加（いいね、リツイート機能）](https://github.com/KakoFujimoto/quelcode-php/blob/feature/php-challenge/README.md)
- <a href="https://github.com/KakoFujimoto/quelcode-php/blob/feature/php-pre-challenge1/README.md">feature/php-pre-challenge1/html/php-pre-challenge1/..条件分岐に関する基本的な問題（FizzBuzz）</a>
- [feature/php-pre-challenge1/html/php-pre-challenge2/..配列操作に関する問題（ソート関数を使わずに並び替えを行う）](https://github.com/KakoFujimoto/quelcode-php/blob/feature/php-pre-challenge2/README.md)

- feature/php-pre-challenge1/html/php-pre-challenge3/..掲示板への２種の機能追加（データーベースへの接続と探索アルゴリズムに関する問題）



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
