# Kashiwazaki SEO Published & Last Updated Dates

[![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.2%2B-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL--2.0--or--later-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Version](https://img.shields.io/badge/Version-1.0.3-orange.svg)](https://github.com/TsuyoshiKashiwazaki/wp-plugin-kashiwazaki-seo-published-last-updated-dates/releases)

投稿や固定ページの公開日と更新日を自動で表示する、WordPress の SEO 対策プラグインです。横並びのレスポンシブなデザイン、ショートコード、テーマに組み込むための PHP 関数、既存のマークアップとぶつからない DigitalDocument 形式の構造化データ、HTTP の Last-Modified ヘッダー、表示スタイルの細かな設定を備えています。

> **日付をはっきり表示し、既存のマークアップとぶつからない構造化データで SEO を強化します**

## 主な機能

- **日付の自動表示** - 記事の前後に、公開日と更新日を横並びで表示します
- **ぶつからない構造化データ** - DigitalDocument 形式を使い、既存の構造化データと競合しません
- **いろいろな組み込み方** - ショートコード、PHP 関数、自動表示に対応します
- **レスポンシブデザイン** - パソコンでは横並び、スマートフォンでは縦並びになります
- **Last-Modified ヘッダー** - HTTP の Last-Modified ヘッダーを自動で出力します
- **表示スタイルの選択** - アイコン + テキスト、テキストのみ、アイコンのみから選べます
- **投稿タイプごとの設定** - 投稿・固定ページ・カスタム投稿タイプごとに、公開日・更新日の表示を切り替えられます
- **日付の書式** - 日付の書式を自由に設定できます

## はじめかた

### インストール

1. プラグインのフォルダを `/wp-content/plugins/` にアップロードします
2. WordPress の管理画面でプラグインを有効化します
3. プラグインの設定画面で表示の設定をします

### 基本的な使い方

設定に従って日付が自動で表示されます。次の方法でも表示できます。

**ショートコード:**
```
[published_date] - 公開日を表示
[updated_date] - 更新日を表示
[publish_update_dates] - 両方の日付を表示
```

**PHP 関数:**
```php
<?php KSPLUD_Display::display_both_dates(); ?>
<?php echo KSPLUD_Display::get_published_date(null, 'Y-m-d'); ?>
```

## ショートコードの属性

### published_date / updated_date
- `format` - 日付の書式 (例: format="Y/m/d")
- `icon` - アイコンの表示・非表示 (例: icon="false")
- `label` - ラベルの文字 (例: label="投稿日")
- `class` - 追加する CSS クラス (例: class="my-custom-date")

### updated_date のみ
- `hide_if_not_modified` - 公開から 24 時間以内の更新を表示しない (例: hide_if_not_modified="false")。省略すると設定画面の「公開直後の場合は更新日を表示しない」に従います

### publish_update_dates
- `separator` - 区切りの文字 (例: separator=" | ")
- `wrapper_class` - 全体を囲む要素の CSS クラス (例: wrapper_class="date-container")

## PHP 関数

**HTML 付きで表示:**
- `KSPLUD_Display::display_published_date($post_id, $echo)`
- `KSPLUD_Display::display_updated_date($post_id, $echo)`
- `KSPLUD_Display::display_both_dates($post_id, $echo)`

**日付の文字だけを取得:**
- `KSPLUD_Display::get_published_date($post_id, $format)`
- `KSPLUD_Display::get_updated_date($post_id, $format)`

## 構造化データの形式

このプラグインは、既存の Article・BlogPosting・WebPage のマークアップとぶつからない **DigitalDocument + CreateAction + UpdateAction** の形式で出力します。

```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "DigitalDocument",
      "@id": "https://example.com/post-url#doc",
      "datePublished": "2024-01-15T10:30:00+09:00",
      "dateModified": "2024-01-20T15:45:00+09:00"
    }
  ]
}
```

## 動作環境

- **WordPress**: 5.0 以上
- **PHP**: 7.2 以上
- **ライセンス**: GPL v2.0 以降

## ライセンス

このプラグインは GPL v2.0 以降のライセンスで提供しています。

## サポート・開発者

**開発者**: 柏崎剛 (Tsuyoshi Kashiwazaki)
**ウェブサイト**: https://www.tsuyoshikashiwazaki.jp/
**サポート**: ご質問や不具合の報告は、開発者のウェブサイトからお寄せください。

## 開発への参加

Issue や Pull Request を歓迎します。

1. リポジトリをフォークする
2. 作業用のブランチを作る
3. 変更をコミットする
4. ブランチをプッシュする
5. Pull Request を作る

## サポート

開発者のウェブサイトか、このリポジトリの Issue からお問い合わせください。

---

**キーワード**: SEO, WordPress, 公開日, 更新日, 最終更新日, 構造化データ, スキーママークアップ, レスポンシブデザイン

Made by [Tsuyoshi Kashiwazaki](https://github.com/TsuyoshiKashiwazaki)
