(function ($) {
    'use strict';

    $(document).ready(function () {
        // カラーピッカーの初期化
        if ($.fn.wpColorPicker) {
            $('.color-picker').wpColorPicker({
                change: function (event, ui) {
                    // 色が変更された時の処理
                },
                clear: function () {
                    // 色がクリアされた時の処理
                }
            });
        }

        var $hideIfNotModified = $('input[name="ksplud_settings[hide_if_not_modified]"]');
        // 「更新日を表示」は投稿タイプ別のチェックボックス (同名の hidden 入力は除く)
        var $showUpdated = $('input[type="checkbox"][name^="ksplud_settings[post_type_settings]"][name$="[show_updated]"]');
        var $postTypeCheckboxes = $('.ksplud-post-type-checkbox');

        // 有効な投稿タイプのどれかで更新日を表示するときだけ「更新日の表示条件」を出す
        function toggleHideIfNotModified() {
            var anyUpdatedShown = $showUpdated.filter(function () {
                var postType = $(this).closest('.ksplud-post-type-settings').data('post-type');
                var $typeCheckbox = $postTypeCheckboxes.filter(function () {
                    return $(this).data('post-type') === postType;
                });
                return this.checked && $typeCheckbox.is(':checked');
            }).length > 0;

            if (anyUpdatedShown) {
                $hideIfNotModified.closest('tr').show();
            } else {
                $hideIfNotModified.closest('tr').hide();
            }
        }

        toggleHideIfNotModified();

        $showUpdated.on('change', toggleHideIfNotModified);
        $postTypeCheckboxes.on('change', toggleHideIfNotModified);

        var $displayStyle = $('select[name="ksplud_settings[display_style]"]');
        var $labelInputs = $('input[name="ksplud_settings[published_text]"], input[name="ksplud_settings[updated_text]"]');

        function toggleLabelInputs() {
            if ($displayStyle.val() === 'icon_only') {
                $labelInputs.closest('tr').hide();
            } else {
                $labelInputs.closest('tr').show();
            }
        }

        toggleLabelInputs();

        $displayStyle.on('change', function () {
            toggleLabelInputs();
        });

        $('#submit').on('click', function (e) {
            var checkedPostTypes = $('input[name="ksplud_settings[post_types][]"]:checked');
            if (checkedPostTypes.length === 0) {
                e.preventDefault();
                alert('少なくとも1つの投稿タイプを選択してください。');
                return false;
            }
        });

        // 対象投稿タイプのチェックボックス変更時に詳細設定を表示/非表示
        $('.ksplud-post-type-checkbox').on('change', function() {
            var postType = $(this).data('post-type');
            var $settings = $('.ksplud-post-type-settings[data-post-type="' + postType + '"]');

            if ($(this).is(':checked')) {
                $settings.slideDown(200);
            } else {
                $settings.slideUp(200);
            }
        });
    });

})(jQuery);
