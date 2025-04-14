<div align="center">
  <a href="https://hirodai-kaede.com/">
    <img alt="kaede" src="https://github.com/YamamotoNagito/l10dev/blob/feature/%23484_readme/resources/assets/img/kaedeIcon.png">
  </a>
</div>

<h3 align="center">
  広島大学授業レビューサイト
</h3>

<p align="center">
  <a href="https://hirodai-kaede.com/">サイトへ</a>
</p>

<p align="center">
  <a href="https://www.hiroshima-u.ac.jp/iagcc/news/80635">プロジェクト概要</a>
</p>

---

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## 使用技術一覧

<!-- シールド一覧 -->
<!-- 該当するプロジェクトの中から任意のものを選ぶ-->
<p style="display: inline">
  <!-- フロントエンドのフレームワーク一覧 -->
  <img src="https://img.shields.io/badge/-vue.js-000000.svg?logo=vue.js&style=for-the-badge">
  <img src="https://img.shields.io/badge/-vuetify-000000.svg?logo=vuetify&style=for-the-badge">
  <!-- バックエンドのフレームワーク一覧 -->
  <img src="https://img.shields.io/badge/-Laravel-092E20.svg?logo=laravel&style=for-the-badge">
  <!-- バックエンドの言語一覧 -->
  <img src="https://img.shields.io/badge/-PHP-F2C63C.svg?logo=php&style=for-the-badge">
  <!-- ミドルウェア一覧 -->
  <img src="https://img.shields.io/badge/-Nginx-269539.svg?logo=nginx&style=for-the-badge">
  <img src="https://img.shields.io/badge/-MySQL-4479A1.svg?logo=mysql&style=for-the-badge&logoColor=white">
  <!-- インフラ一覧 -->
  <img src="https://img.shields.io/badge/-Docker-1488C6.svg?logo=docker&style=for-the-badge">
  <img src="https://img.shields.io/badge/-githubactions-FFFFFF.svg?logo=github-actions&style=for-the-badge">
</p>

## 開発環境構築

### ターミナルで操作する場合

1. .env ファイルの作成

2. Docker Desktopのインストール
    - 参考: [【Docker Desktop】Windowsにインストール（WSL2）](https://chigusa-web.com/blog/windows%E3%81%ABdocker%E3%82%92%E3%82%A4%E3%83%B3%E3%82%B9%E3%83%88%E3%83%BC%E3%83%AB%E3%81%97%E3%81%A6python%E7%92%B0%E5%A2%83%E3%82%92%E6%A7%8B%E7%AF%89/)
      - 手順 1: wsl2 のインストール(windows のみ)
      - 手順 2: Docker Desktop のインストール

3. Dockerの起動

    ```txt
    docker compose up -d
    ```

    - 旧式のDockerの場合は以下のコマンド

      ```txt
      docker-compose up -d
      ```

4. Docker の app サーバーに入る
    - 参考: [Laravel 10 の開発環境をdockerで実現する方法 > Dockerコンテナ起動時に毎回実行すべきコマンド](https://qiita.com/hitotch/items/869070c3a9f474a358ea#comment-8632d9b827cb0190f769)

    ```txt
    docker compose exec l10dev-app bash
    ```

5. srcディレクトリに移動
    - すでにsrcディレクトリにいる場合はスキップ

    ```txt
    cd /src
    ```

6. Composerおよびnpmのインストール．初回はseederの流し込み

    - 初回

      ```txt
      php artisan key:generate
      composer install
      npm install
      php artisan migrate:fresh --seed
      npm run dev
      ```

    - 2回目以降

      ```txt
      composer install
      php artisan migrate
      npm install
      npm run dev
      ```

      - 2回目以降について，composer，npm，モデルの変更がない場合については`npm run dev`だけで良い

6: `localhost:9001` を確認して、画面が移っていれば成功

### VS CodeのDev Containerを利用する場合

1. 「ターミナルで操作する場合」欄の`1. .envファイルの作成`および`2. Docker Desktopの作成`を実行

2. VS CodeにDev Containersの拡張機能を導入
    - 導入済みの場合はスキップ
    - パッケージの検索欄で「Dev Containers」または「ms-vscode-remote.remote-containers」と検索

3. 「コンテナーで再度開く」
    - VS Codeの画面左下のボタンをクリック
    - 画面上部に次の動作の選択肢が出てくるので「コンテナーで再度開く」または「Reopen in Container」をクリック

4. サーバーを立ち上げる

    ```text
    npm run dev
    ```

> [!NOTE]
> 本プロジェクトでは依存パッケージ（node_modules, vendor）はコンテナ内に存在しており、ホスト環境と分離された環境で開発を実施しています。Dev Containerを利用すると、TypeScriptやESLint、Vue用の型解釈エンジンなどの静的解析ツールが、正確にコンテナ内の依存パッケージを参照できるようになるため、補完やエラー検出の精度が向上します。

## 参考サイト

- [【Docker Desktop】Windowsにインストール（WSL2）](https://chigusa-web.com/blog/windows%E3%81%ABdocker%E3%82%92%E3%82%A4%E3%83%B3%E3%82%B9%E3%83%88%E3%83%BC%E3%83%AB%E3%81%97%E3%81%A6python%E7%92%B0%E5%A2%83%E3%82%92%E6%A7%8B%E7%AF%89/)

- [Laravel 10 の開発環境をdockerで実現する方法](https://qiita.com/hitotch/items/869070c3a9f474a358ea)

- [Laravel 10 と Vue 3 の開発環境を Docker で構築する](https://qiita.com/yuto_dev/items/d1cc909897ac8277baea)

