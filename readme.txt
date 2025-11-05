=== Kashiwazaki SEO Published & Last Updated Dates ===
Contributors: tsuyoshikashiwazaki
Tags: seo, published date, updated date, schema markup, structured data, last-modified
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0.1
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

記事の公開日と更新日を美しく表示し、SEO対策に貢献する構造化マークアップとLast-Modifiedヘッダーも出力するプラグイン

== Description ==

**Kashiwazaki SEO Published & Last Updated Dates** は、WordPressサイトの記事に公開日と更新日を自動的に表示するプラグインです。検索エンジン最適化（SEO）を重視し、既存テーマとの干渉を避けた独自の構造化マークアップとLast-Modifiedヘッダー出力に対応しています。

= 主な機能 =

* **自動表示機能** - 記事の前後または両方に日付を美しく横並びで表示
* **ショートコード対応** - 投稿内や任意の場所に日付を表示可能
* **PHP関数対応** - テンプレートファイルから直接呼び出し可能
* **投稿タイプ選択** - 投稿、固定ページ、カスタム投稿タイプごとに有効/無効を設定
* **表示スタイル選択** - アイコン付き、テキストのみ、アイコンのみから選択
* **干渉しない構造化マークアップ** - DigitalDocument形式で既存マークアップと競合しない
* **Last-Modifiedヘッダー** - HTTPレスポンスヘッダーに更新日を自動出力
* **レスポンシブデザイン** - デスクトップは横並び、モバイルは縦並び
* **カスタマイズ可能** - 日付フォーマット、ラベルテキスト、カスタムCSSなど

= 使用可能なショートコード =

* `[published_date]` - 公開日を表示
* `[updated_date]` - 更新日を表示
* `[publish_update_dates]` - 公開日と更新日を両方表示

= ショートコードのパラメータ =

**published_date / updated_date**
* `format` - 日付フォーマット（例: format="Y/m/d"）
* `icon` - アイコンの表示/非表示（例: icon="false"）
* `label` - ラベルテキスト（例: label="投稿日"）
* `class` - 追加のCSSクラス（例: class="my-custom-date"）

**publish_update_dates**
* `separator` - 区切り文字（例: separator=" | "）
* `wrapper_class` - ラッパーのCSSクラス（例: wrapper_class="date-container"）

= PHP関数での直接呼び出し =

**HTML込みで表示**
* `KSPLUD_Display::display_published_date()` - 公開日を表示
* `KSPLUD_Display::display_updated_date()` - 更新日を表示
* `KSPLUD_Display::display_both_dates()` - 両方を表示

**テキストのみ取得**
* `KSPLUD_Display::get_published_date()` - 公開日のテキストを取得
* `KSPLUD_Display::get_updated_date()` - 更新日のテキストを取得

**使用例**
```php
<?php KSPLUD_Display::display_both_dates(); ?>
<?php echo KSPLUD_Display::get_published_date(null, 'Y年n月j日'); ?>
```

= 構造化マークアップについて =

従来のArticle、BlogPosting、WebPageタイプとは異なり、**DigitalDocument + CreateAction + UpdateAction**の組み合わせを使用しているため、既存のテーマやプラグインの構造化マークアップと競合しません。

```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "DigitalDocument",
      "@id": "https://example.com/post-url#doc",
      "datePublished": "2024-01-15T10:30:00+09:00",
      "dateModified": "2024-01-20T15:45:00+09:00"
    },
    {
      "@type": "CreateAction",
      "startTime": "2024-01-15T10:30:00+09:00",
      "object": {"@id": "https://example.com/post-url#doc"}
    },
    {
      "@type": "UpdateAction",
      "startTime": "2024-01-20T15:45:00+09:00",
      "object": {"@id": "https://example.com/post-url#doc"}
    }
  ]
}
```

= なぜこのプラグインを使うべきか？ =

1. **SEO効果** - Googleは新鮮なコンテンツを好みます。更新日を明示することで、コンテンツの鮮度をアピール
2. **Last-Modifiedヘッダー** - HTTPレスポンスヘッダーでクローラーに正確な更新日を伝達
3. **ユーザビリティ向上** - 読者は記事がいつ書かれ、いつ更新されたかを一目で確認可能
4. **干渉しない設計** - 既存のテーマやプラグインとの競合を回避
5. **柔軟な実装** - ショートコード、PHP関数、自動表示の3つの方法で利用可能
6. **美しいデザイン** - 横並びレイアウトでモダンな表示

== Installation ==

1. プラグインファイルを `/wp-content/plugins/kashiwazaki-seo-published-last-updated-dates` ディレクトリにアップロード
2. WordPressの「プラグイン」メニューからプラグインを有効化
3. 管理画面に「Kashiwazaki SEO Published & Last Updated Dates」メニューが追加される
4. 設定画面から表示方法やスタイルを設定

または

1. WordPress管理画面から「プラグイン」→「新規追加」を選択
2. 「Kashiwazaki SEO Published & Last Updated Dates」を検索
3. 「今すぐインストール」をクリックし、有効化

== Frequently Asked Questions ==

= 更新日が表示されない場合は？ =

デフォルトでは、公開から24時間以内の更新は表示されません。設定画面の「更新日の表示条件」で変更可能です。

= 既存のテーマの構造化マークアップと競合しませんか？ =

このプラグインはDigitalDocument形式を使用しており、一般的なArticle、BlogPosting、WebPageタイプとは異なるため競合しません。

= カスタム投稿タイプでも使えますか？ =

はい、設定画面でカスタム投稿タイプを含むすべての公開投稿タイプを選択できます。

= テーマのテンプレートファイルに直接追加したい場合は？ =

PHP関数を使用してください。例：`<?php KSPLUD_Display::display_both_dates(); ?>`

= Last-Modifiedヘッダーとは何ですか？ =

HTTPレスポンスヘッダーに記事の最終更新日を追加する機能です。検索エンジンのクローラーが記事の更新状況を正確に把握できます。

= 日付のフォーマットを変更できますか？ =

はい、PHPの日付フォーマットを使用して自由にカスタマイズできます。例：`Y年n月j日`、`Y/m/d H:i`など

= モバイルでの表示はどうなりますか？ =

デスクトップでは横並び、タブレットでは必要に応じて折り返し、スマートフォンでは縦並びで表示されます。

== Screenshots ==

1. デスクトップでの横並び表示例
2. 管理画面の設定ページ
3. ショートコード使用例
4. PHP関数の実装例
5. 構造化マークアップの出力例

== Changelog ==

= 1.0.1 =
* Improved: 対象投稿タイプの設定UIを改善し、投稿タイプ別の公開日・更新日表示設定を統合
* Improved: Schema.orgマークアップ出力箇所にHTMLコメントで署名を追加
* Fixed: URL衝突時のクエリ処理を最適化

= 1.0.0 =
* 初回リリース
* 基本的な日付表示機能
* ショートコード機能（3種類）
* PHP関数での直接呼び出し機能
* 干渉しない構造化マークアップ（DigitalDocument形式）
* Last-Modifiedヘッダー自動出力
* レスポンシブ対応の横並びデザイン
* 管理画面での詳細設定
* カスタムCSS対応

== Upgrade Notice ==

= 1.0.1 =
設定画面のUIが改善され、より使いやすくなりました。Schema.orgマークアップの識別も容易になります。

= 1.0.0 =
初回リリース

== 開発者向け情報 ==

このプラグインは以下のクラスとメソッドを提供しています：

**メインクラス**
* `KSPLUD_Display` - 表示処理とPHP関数
* `KSPLUD_Settings` - 設定管理
* `KSPLUD_Shortcode` - ショートコード処理
* `KSPLUD_Schema` - 構造化マークアップ
* `KSPLUD_Admin` - 管理画面

**よく使用される関数**
* `KSPLUD_Display::display_both_dates($post_id, $echo)`
* `KSPLUD_Display::display_published_date($post_id, $echo)`
* `KSPLUD_Display::display_updated_date($post_id, $echo)`
* `KSPLUD_Display::get_published_date($post_id, $format)`
* `KSPLUD_Display::get_updated_date($post_id, $format)`

**パラメータ**
* `$post_id` - 投稿ID（省略時は現在の投稿）
* `$format` - 日付フォーマット（省略時はプラグイン設定）
* `$echo` - true=出力、false=戻り値として返す

詳細な使用方法は、[プラグインのウェブサイト](https://tsuyoshikashiwazaki.jp)をご覧ください。

== サポート ==

ご質問やバグ報告は、[SEO対策研究室](https://tsuyoshikashiwazaki.jp)までお願いします。

== クレジット ==

* 作者: 柏崎 剛
* ウェブサイト: [SEO対策研究室](https://tsuyoshikashiwazaki.jp)
* 開発理念: 既存環境との調和を重視した実用的なSEO対策ツール 