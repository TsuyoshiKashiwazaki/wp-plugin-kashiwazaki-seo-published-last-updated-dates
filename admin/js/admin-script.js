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
        var $showUpdated = $('input[name="ksplud_settings[show_updated]"]');

        function toggleHideIfNotModified() {
            if ($showUpdated.is(':checked')) {
                $hideIfNotModified.closest('tr').show();
            } else {
                $hideIfNotModified.closest('tr').hide();
            }
        }

        toggleHideIfNotModified();

        $showUpdated.on('change', function () {
            toggleHideIfNotModified();
        });

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
    });

})(jQuery);
