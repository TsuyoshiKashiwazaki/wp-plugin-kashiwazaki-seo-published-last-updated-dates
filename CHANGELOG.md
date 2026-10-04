# 更新履歴

Kashiwazaki SEO Published & Last Updated Dates の主な変更をこのファイルに記録します。

書式は [Keep a Changelog](https://keepachangelog.com/ja/1.0.0/) にもとづき、
バージョン番号は [セマンティック バージョニング](https://semver.org/lang/ja/) に従います。

## [1.0.3] - 2026-10-04

### 修正
- Last-Modified ヘッダーと、日付の要素の datetime 属性が、サイトのタイムゾーンの分だけずれていた問題を修正 (日本時間のサイトでは 9 時間後の時刻になっていた)。本物の Unix 時刻 (`get_post_time( 'U', true )` / `get_post_modified_time( 'U', true )`) から作るようにした
- 設定画面の「公開直後の場合は更新日を表示しない」が常に隠れていて、変更できなかった問題を修正 (投稿タイプ別の「更新日を表示」のチェックに連動して表示する)
- 一覧・検索・404 などのページで、URL の末尾が同じスラッグの記事に置き換わることがあった問題を修正。メインクエリを書き換える処理 (`fix_query_conflicts()`) を削除し、WordPress 本来の URL の解決に任せる
- カスタム CSS に `>` や引用符を書くと、出力の際に HTML の実体参照に変換されて CSS が効かなかった問題を修正
- 公開日・更新日をどちらも表示しない設定でも日付が表示されていた問題を修正。更新日だけを表示する設定で更新日が表示条件で隠れたときは、従来どおり日付を 1 つ表示し、その値を公開日ではなく実際の最終更新日時にした (「更新日」のラベル・dateModified と合う値)
- 投稿タイプの「公開日を表示」「更新日を表示」を両方外した状態を保存できなかった問題を修正。設定画面に送られない旧形式の全体設定は、保存のたびに「表示しない」になっていたのを「表示する」にした
- 更新の有無を表示用の日付の文字で判定していたため、同じ日の更新などが表示されなかった問題を修正 (自動表示で、実際の時刻で判定する)
- ショートコード `[updated_date]`・`[publish_update_dates]` が、設定画面の「公開直後の場合は更新日を表示しない」を見ていなかった問題を修正 (`hide_if_not_modified` 属性を省略したときは設定に従う)
- 日付の要素に付けていた microdata (itemprop・itemscope・itemtype) が HTML の規格に合っていなかったため削除。構造化データは JSON-LD で出力する
- 古いバージョンで保存した設定に項目が欠けていると、設定画面で PHP の Notice が出て、その文がラベルとして保存されてしまう問題を修正。欠けた項目は既定値で補い、保存済みのエラー文のラベルは既定値で表示する

### 変更
- 公開メソッド `KSPLUD_Display::get_single_date_html()` の `$timestamp` は、本物の Unix 時刻 (UTC) を受け取るようにした

## [1.0.2] - 2025-11-26

### 修正
- クエリの衝突を直す処理が、投稿タイプのアーカイブページを個別記事に変換しないようにした
- カスタム投稿タイプのアーカイブページが、誤って個別記事として表示されないようにした

### 改善
- `fix_query_conflicts()` で、アーカイブページ (投稿タイプアーカイブ・カテゴリー・タグ・タクソノミー・日付) をスキップするようにした
- `is_post_type_archive()`・`is_archive()`・`is_category()`・`is_tag()`・`is_tax()`・`is_date()` で早めに処理を終えるようにした

## [1.0.1] - 2025-11-05

### 改善
- 対象の投稿タイプの設定画面に、投稿タイプ別の表示設定 (公開日・更新日を表示のチェック) をまとめ、使いやすくした
- Schema.org のマークアップの前後に、見分けやすいよう HTML コメントの署名 (`<!-- Kashiwazaki SEO Published & Last Updated Dates -->`) を付けた

### 修正
- カスタム投稿のスラッグと投稿タイプのアーカイブの URL が衝突したときのクエリの処理を見直した
- 一部の条件で日付が表示されなかった原因の、不要な `is_main_query()` の判定を外した

## [1.0.0] - 2025-09-22

### 追加
- 最初のリリース
- 投稿・固定ページの公開日と更新日の自動表示
- 3 種類のショートコード: `[published_date]`、`[updated_date]`、`[publish_update_dates]`
- テーマに直接組み込むための PHP 関数
- DigitalDocument 形式の、ぶつからない構造化データ
- SEO のための Last-Modified ヘッダーの自動出力
- 横並びのレスポンシブなレイアウト (パソコンでは横並び、スマートフォンでは縦並び)
- 表示の設定ができる管理画面
- カスタム投稿タイプへの対応
- 日付の書式とラベルの文字の変更
- 細かな見た目の調整のためのカスタム CSS
- 表示位置の選択 (記事の前・記事の後・両方)
- 表示スタイルの選択 (アイコン + テキスト・テキストのみ・アイコンのみ)
- 更新日を表示する条件の設定 (既定は公開から 24 時間後から)

### 技術的な情報
- 対応する WordPress: 5.0 以上
- 対応する PHP: 7.2 以上
- ライセンス: GPL-2.0-or-later
- テキストドメイン: kashiwazaki-seo-published-last-updated-dates

### 開発者向けの補足
- 主なクラスはシングルトンの形で作っている
- WordPress のコーディング規約に沿っている
- 国際化に対応している
- データベースへの問い合わせを少なくして動作を軽くしている

[1.0.3]: https://github.com/TsuyoshiKashiwazaki/wp-plugin-kashiwazaki-seo-published-last-updated-dates/releases/tag/v1.0.3-dev
[1.0.2]: https://github.com/TsuyoshiKashiwazaki/wp-plugin-kashiwazaki-seo-published-last-updated-dates/releases/tag/v1.0.2
[1.0.1]: https://github.com/TsuyoshiKashiwazaki/wp-plugin-kashiwazaki-seo-published-last-updated-dates/releases/tag/v1.0.1
[1.0.0]: https://github.com/TsuyoshiKashiwazaki/wp-plugin-kashiwazaki-seo-published-last-updated-dates/releases/tag/v1.0.0
