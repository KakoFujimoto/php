# 【PHPコーディング】

## 使用書籍
<a href="https://www.amazon.co.jp/%E3%82%88%E3%81%8F%E3%82%8F%E3%81%8B%E3%82%8BPHP%E3%81%AE%E6%95%99%E7%A7%91%E6%9B%B8-%E3%80%90PHP7%E5%AF%BE%E5%BF%9C%E7%89%88%E3%80%91-%E6%95%99%E7%A7%91%E6%9B%B8%E3%82%B7%E3%83%AA%E3%83%BC%E3%82%BA-%E3%81%9F%E3%81%AB%E3%81%90%E3%81%A1-%E3%81%BE%E3%81%93%E3%81%A8-ebook/dp/B07C3QQKTX/" target="_blank">よくわかるPHPの教科書【PHP7対応版】/たにぐちまこと著</a>

## 追加した機能
<li>いいね機能</li>
<li>リツイート機能</li>

## ブランチ詳細

<li>feature/php-challenge…掲示板への２種の機能追加</li>
<li>feature/php-challenge…掲示板への２種の機能追加</li>
<li>feature/php-challenge…掲示板への２種の機能追加</li>
<li>feature/php-challenge…掲示板への２種の機能追加</li>



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
