/**
 * Kashiwazaki SEO Published & Last Updated Dates
 * カスタム要素定義 - published-date と updated-date
 */

(function () {
    'use strict';

    // ブラウザがカスタム要素をサポートしているかチェック
    if (!window.customElements) {
        console.warn('KSPLUD: カスタム要素がサポートされていません。古いブラウザではtime要素として表示されます。');
        return;
    }

    /**
     * 公開日カスタム要素 <published-date>
     */
    class PublishedDate extends HTMLElement {
        connectedCallback() {
            // 要素がDOMに追加された時の初期化
            this.style.display = 'inline';
            this.setAttribute('role', 'time');
            this.setAttribute('data-date-type', 'published');

            // アクセシビリティ向上
            if (!this.hasAttribute('title') && this.hasAttribute('datetime')) {
                const datetime = this.getAttribute('datetime');
                if (datetime) {
                    const date = new Date(datetime);
                    this.setAttribute('title', `公開日: ${date.toLocaleDateString('ja-JP')}`);
                }
            }

            // Schema.org microdata
            this.setAttribute('itemprop', 'datePublished');
        }

        static get observedAttributes() {
            return ['datetime'];
        }

        attributeChangedCallback(name, oldValue, newValue) {
            if (name === 'datetime' && newValue !== oldValue) {
                // datetime属性が変更された時の処理
                const date = new Date(newValue);
                if (!isNaN(date.getTime())) {
                    this.setAttribute('title', `公開日: ${date.toLocaleDateString('ja-JP')}`);
                }
            }
        }
    }

    /**
     * 更新日カスタム要素 <updated-date>
     */
    class UpdatedDate extends HTMLElement {
        connectedCallback() {
            // 要素がDOMに追加された時の初期化
            this.style.display = 'inline';
            this.setAttribute('role', 'time');
            this.setAttribute('data-date-type', 'updated');

            // アクセシビリティ向上
            if (!this.hasAttribute('title') && this.hasAttribute('datetime')) {
                const datetime = this.getAttribute('datetime');
                if (datetime) {
                    const date = new Date(datetime);
                    this.setAttribute('title', `更新日: ${date.toLocaleDateString('ja-JP')}`);
                }
            }

            // Schema.org microdata
            this.setAttribute('itemprop', 'dateModified');
        }

        static get observedAttributes() {
            return ['datetime'];
        }

        attributeChangedCallback(name, oldValue, newValue) {
            if (name === 'datetime' && newValue !== oldValue) {
                // datetime属性が変更された時の処理
                const date = new Date(newValue);
                if (!isNaN(date.getTime())) {
                    this.setAttribute('title', `更新日: ${date.toLocaleDateString('ja-JP')}`);
                }
            }
        }
    }

    // カスタム要素をブラウザに登録
    try {
        customElements.define('published-date', PublishedDate);
        customElements.define('updated-date', UpdatedDate);

        // デバッグ用ログ（開発時のみ）
        if (window.location.hostname === 'localhost' || window.location.hostname.includes('dev')) {
            console.log('KSPLUD: カスタム要素が正常に登録されました');
        }
    } catch (error) {
        console.error('KSPLUD: カスタム要素の登録に失敗しました', error);
    }

    /**
     * レガシーブラウザ用のフォールバック
     * カスタム要素が使えない場合は通常のspan要素として動作
     */
    function fallbackForLegacyBrowsers() {
        // カスタム要素が定義されていない場合のフォールバック
        const publishedDates = document.querySelectorAll('published-date');
        const updatedDates = document.querySelectorAll('updated-date');

        // published-date要素をspan要素に変換
        publishedDates.forEach(function (element) {
            const span = document.createElement('span');
            span.className = 'ksplud-published-date-fallback';
            span.setAttribute('data-datetime', element.getAttribute('datetime') || '');
            span.innerHTML = element.innerHTML;
            if (element.parentNode) {
                element.parentNode.replaceChild(span, element);
            }
        });

        // updated-date要素をspan要素に変換
        updatedDates.forEach(function (element) {
            const span = document.createElement('span');
            span.className = 'ksplud-updated-date-fallback';
            span.setAttribute('data-datetime', element.getAttribute('datetime') || '');
            span.innerHTML = element.innerHTML;
            if (element.parentNode) {
                element.parentNode.replaceChild(span, element);
            }
        });
    }

    // DOM読み込み完了後にフォールバック処理を実行
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(fallbackForLegacyBrowsers, 100);
        });
    } else {
        setTimeout(fallbackForLegacyBrowsers, 100);
    }

})();



