# 🚀 Kashiwazaki SEO Published & Last Updated Dates

[![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.2%2B-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL--2.0--or--later-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Version](https://img.shields.io/badge/Version-1.0.0-orange.svg)](https://github.com/TsuyoshiKashiwazaki/wp-plugin-kashiwazaki-seo-published-last-updated-dates/releases)

A WordPress SEO plugin that automatically displays published and last updated dates for posts and pages. Features include responsive design with horizontal layout, shortcode support, PHP functions for theme integration, non-conflicting DigitalDocument schema markup, Last-Modified HTTP headers, and customizable styling options.

> 🎯 **Enhance your SEO with clear date display and structured data that doesn't conflict with existing markup**

## 主な機能 / Key Features

- **📅 Automatic Date Display** - Shows published and updated dates before/after posts with beautiful horizontal layout
- **🏗️ Non-conflicting Schema Markup** - Uses DigitalDocument format to avoid conflicts with existing structured data
- **🔌 Multiple Integration Methods** - Shortcodes, PHP functions, and automatic display
- **📱 Responsive Design** - Horizontal layout on desktop, vertical on mobile
- **⚡ Last-Modified Headers** - Automatically outputs HTTP Last-Modified headers for better SEO
- **🎨 Customizable Styling** - Choose from icon+text, text-only, or icon-only display styles
- **📝 Flexible Post Type Support** - Works with posts, pages, and custom post types
- **🌐 Custom Date Formats** - Fully customizable date formatting options

## 🚀 Quick Start / クイックスタート

### Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the WordPress admin panel
3. Navigate to the plugin settings page to configure display options

### Basic Usage

The plugin automatically displays dates based on your settings. You can also use:

**Shortcodes:**
```
[published_date] - Display published date
[updated_date] - Display updated date
[publish_update_dates] - Display both dates
```

**PHP Functions:**
```php
<?php KSPLUD_Display::display_both_dates(); ?>
<?php echo KSPLUD_Display::get_published_date(null, 'Y-m-d'); ?>
```

## Shortcode Parameters

### published_date / updated_date
- `format` - Date format (e.g., format="Y/m/d")
- `icon` - Show/hide icon (e.g., icon="false")
- `label` - Label text (e.g., label="Posted on")
- `class` - Additional CSS class (e.g., class="my-custom-date")

### publish_update_dates
- `separator` - Separator character (e.g., separator=" | ")
- `wrapper_class` - Wrapper CSS class (e.g., wrapper_class="date-container")

## PHP Functions

**Display with HTML:**
- `KSPLUD_Display::display_published_date($post_id, $echo)`
- `KSPLUD_Display::display_updated_date($post_id, $echo)`
- `KSPLUD_Display::display_both_dates($post_id, $echo)`

**Get text only:**
- `KSPLUD_Display::get_published_date($post_id, $format)`
- `KSPLUD_Display::get_updated_date($post_id, $format)`

## Structured Data Format

This plugin uses a unique **DigitalDocument + CreateAction + UpdateAction** schema that doesn't conflict with existing Article, BlogPosting, or WebPage markup:

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

## Technical Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.2 or higher
- **License**: GPL v2.0 or later

## Changelog

### Version 1.0.0 - 2025-09-22
- Initial release
- Automatic date display functionality
- Three shortcode types
- PHP function support for direct calls
- Non-conflicting structured data (DigitalDocument format)
- Last-Modified header automatic output
- Responsive horizontal layout design
- Comprehensive admin settings panel
- Custom CSS support

## License

This plugin is licensed under the GPL v2.0 or later.

## Support & Developer

**Developer**: 柏崎剛 (Tsuyoshi Kashiwazaki)
**Website**: https://www.tsuyoshikashiwazaki.jp/
**Support**: For questions or bug reports, please visit the developer's website.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit issues or pull requests.

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## 📞 Support

For support, please visit the developer's website or create an issue in this repository.

---

<div align="center">

**🔍 Keywords**: SEO, WordPress, published date, updated date, last modified, schema markup, structured data, responsive design

Made with ❤️ by [Tsuyoshi Kashiwazaki](https://github.com/TsuyoshiKashiwazaki)

</div>