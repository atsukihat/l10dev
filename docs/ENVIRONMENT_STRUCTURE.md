# 環境構成

## ランタイムバージョン

### 開発環境

| 項目 | バージョン | 備考 |
|------|-----------|------|
| PHP | ^8.1 | - |
| Composer | - | Dockerfileで最新版が取得される（具体的なバージョン指定なし） |
| Node.js | 16.16.0 |  |
| npm | 8.11.0 | Node.jsのバージョンに付属するバージョンが取得される |

### 本番環境

| 項目 | バージョン | 備考 |
|------|-----------|------|
| PHP | 8.2.9 |  |
| Composer | 2.6.6 |  |
| Node.js | 16.16.0 |  |
| npm | 8.11.0 |  |

### 開発環境バージョン確認コマンド

```bash
# PHPバージョン
docker compose exec l10dev-app php -v

# Composerバージョン
docker compose exec l10dev-app composer -V

# Node.jsバージョン
docker compose exec l10dev-app node -v

# npmバージョン
docker compose exec l10dev-app npm -v
```

## バックエンド（PHP/Laravel）

> [!NOTE]
> バックエンドにインストールされるパッケージの指定は `composer.json` で確認することができます。
> 以下は同ファイルの内容のまとめです。

### 本番環境パッケージ（require）

| パッケージ名 | バージョン | 用途 |
|-------------|-----------|------|
| laravel/framework | ^10.10 | Laravelフレームワーク本体 |
| laravel/sanctum | ^3.2 | API認証機能 |
| laravel/tinker | ^2.8 | 対話型REPL |
| spatie/laravel-permission | ^6.3 | 権限・ロール管理 |
| guzzlehttp/guzzle | ^7.2 | HTTPクライアント |

### 開発環境パッケージ（require-dev）

| パッケージ名 | バージョン | 用途 |
|-------------|-----------|------|
| phpunit/phpunit | ^10.1 | PHPテストフレームワーク |
| laravel/pint | ^1.0 | コードフォーマッター |
| laravel/sail | ^1.18 | Docker開発環境 |
| fakerphp/faker | ^1.9.1 | テストデータ生成 |
| mockery/mockery | ^1.4.4 | モックライブラリ |
| nunomaduro/collision | ^7.0 | エラーハンドリング |
| spatie/laravel-ignition | ^2.0 | デバッグツール |

### 開発環境Composerパッケージ確認コマンド

- 実際にインストールされているパッケージ情報を確認するためのコマンド

```bash
# インストール済みパッケージ一覧
docker compose exec l10dev-app composer show

# 本番環境用パッケージのみ
docker compose exec l10dev-app composer show --no-dev

# 依存関係ツリー表示
docker compose exec l10dev-app composer show --tree

# アップデート可能なパッケージ確認
docker compose exec l10dev-app composer outdated
```

## フロントエンド（JavaScript/Vue）

### 本番依存パッケージ（dependencies）

| パッケージ名 | バージョン | 用途 |
|-------------|-----------|------|
| @mdi/font | ^7.2.96 | Material Design Icons（フォント） |
| @mdi/js | ^7.2.96 | Material Design Icons（JS） |
| @types/jest | ^29.5.3 | Jest型定義 |
| @vuelidate/core | ^2.0.3 | バリデーションライブラリ |
| @vuelidate/validators | ^2.0.4 | バリデーションルール集 |
| chart.js | ^4.4.8 | グラフ描画ライブラリ |
| ts-jest | ^29.1.1 | TypeScript用Jestプリセット |
| vue-chartjs | ^5.3.2 | Chart.jsのVueラッパー |
| vue-router | ^4.2.4 | Vue.jsルーティング |
| vuex | ^4.0.2 | Vue.js状態管理 |
| vuex-persistedstate | ^4.1.0 | Vuex永続化 |
| vue | ^3.4.15 | Vue.jsフレームワーク本体 |
| vuetify | ^3.5.5 | マテリアルデザインUIライブラリ |

### 開発依存パッケージ（devDependencies）

| パッケージ名 | バージョン | 用途 |
|-------------|-----------|------|
| @types/chart.js | ^2.9.41 | Chart.js型定義 |
| @typescript-eslint/eslint-plugin | ^7.0.1 | TypeScript用ESLintプラグイン |
| @typescript-eslint/parser | ^7.0.1 | TypeScript用ESLintパーサー |
| @vitejs/plugin-vue | ^4.6.2 | Vite用Vueプラグイン |
| @vue/eslint-config-typescript | ^12.0.0 | Vue+TypeScript用ESLint設定 |
| axios | ^1.1.2 | HTTPクライアント |
| eslint | ^8.56.0 | JavaScriptリンター |
| eslint-config-prettier | ^9.1.0 | ESLint-Prettier連携 |
| eslint-plugin-vue | ^9.21.1 | Vue用ESLintプラグイン |
| husky | ^8.0.3 | Gitフック管理 |
| jest | ^29.6.2 | JavaScriptテストフレームワーク |
| laravel-vite-plugin | ^0.7.5 | Vite用Laravelプラグイン |
| lint-staged | ^15.2.2 | ステージングファイルリント |
| prettier | ^3.2.5 | コードフォーマッター |
| vite | ^4.0.0 | 高速ビルドツール |
| vue | ^3.4.15 | Vue.jsフレームワーク本体（開発依存にも指定） |
| vuetify | ^3.5.5 | マテリアルデザインUIライブラリ（開発依存にも指定） |

### 開発環境npmパッケージ確認コマンド

```bash
# インストール済みパッケージ一覧（第1階層のみ）
docker compose exec l10dev-app npm list --depth=0

# すべての依存関係を表示
docker compose exec l10dev-app npm list

# アウトデートなパッケージ確認
docker compose exec l10dev-app npm outdated
```
