# アプリケーション名

COACHTECH 模擬案件 フリマアプリ（coachtech-furima）
アイテムの出品・購入ができるフリマアプリです。

---

## 環境構築

本アプリケーションは Docker を使用して Laravel / MySQL 環境を構築します。
以下の手順に沿ってセットアップしてください。

### Docker ビルド

- git clone https://github.com/amihira03/coachtech-furima.git
- cd coachtech-furima
- docker compose up -d --build

### Laravel 環境構築

- docker compose exec php bash
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate --seed

---

## 開発環境（URL）

- 商品一覧（トップ）：http://localhost/
- 商品一覧（マイリスト）：http://localhost/?tab=mylist
- 会員登録：http://localhost/register
- ログイン：http://localhost/login
- 商品詳細：http://localhost/item/{item_id}
- 商品購入：http://localhost/purchase/{item_id}
- 送付先住所変更：http://localhost/purchase/address/{item_id}
- 商品出品：http://localhost/sell
- マイページ：http://localhost/mypage
- プロフィール編集：http://localhost/mypage/profile
- 購入した商品一覧：http://localhost/mypage?page=buy
- 出品した商品一覧：http://localhost/mypage?page=sell
- phpMyAdmin：http://localhost:8080（※用意した場合のみ）

※ `{item_id}` は数字に置き換えてください（例：`/item/1`）

---

## 使用技術（実行環境）

- PHP：
- Laravel：
- MySQL：
- Nginx：
- Docker：
- 認証：Laravel Fortify
- バリデーション：FormRequest

---

## 認証について（アクセス制御）

- 未認証でも閲覧可能
  - 商品一覧（トップ）`/`
  - 商品詳細 `/item/{item_id}`
  - 会員登録 `/register`
  - ログイン `/login`
- ログイン必須
  - 商品購入 `/purchase/{item_id}`（GET/POST）
  - 送付先住所変更 `/purchase/address/{item_id}`（GET/PATCH）
  - 商品出品 `/sell`（GET/POST）
  - マイページ `/mypage`
  - プロフィール編集 `/mypage/profile`（GET/PATCH）

※ マイリスト（`/?tab=mylist`）は、未認証の場合「何も表示されない」挙動にします。

---

## ダミーデータ（Seeder）

- items は「商品データ一覧」に一致する内容で投入します（画像 URL 含む）
- categories は固定データとして投入します

---

## テスト

- docker compose exec php bash
- php artisan test

---

## ER 図

![ER図](docs/er_furima1.png)
