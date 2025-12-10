## How to deploy

main ブランチに push すると自動でデプロイされるようになっています。
以下のようにdevelop to mainのPRを作成してmergeしてください。
https://github.com/YamamotoNagito/l10dev/pull/441

### 細かい操作コマンド

#### DB への初期データ格納

太郎広大の google drive から以下の csv ファイルを取ってくる

```
lectures.csv
lectureDetailTimes.csv
lectureLables.csv
labels.csv
lectureDetails.csv
```

`database`配下に`csv`というフォルダ名でフォルダを作成し，上記の csv ファイルを格納

##### DB の再起動コマンド

```
php artisan migrate:fresh --seed
```

##### Docker の再起動コマンド

```
docker-compose down
docker-compose build; docker-compose up -d
```

##### Docker の mysql コマンド

1. ログインコマンド

```
mysql -h l10dev-mysql -u root -p
```

この後に求められるパスワードは`root`

2. データベース選択

```
use l10dev
```

3.  全体のテーブルを確認する

```
show tables;
```

3. (例)格納された授業データを確認する

```
select * from lectures;
```

#### docker 関連のコマンド

Makefile に記載されているコマンドを実行することで，docker の操作を行うことができます。
Makefile が入っていない場合は DL してください。

```bash
# l10dev-app のログを確認する
$ make app.logs
# l10dev-app のコンテナに入る
$ make app.shell
```
