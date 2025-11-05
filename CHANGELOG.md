# Changelog

All notable changes to Kashiwazaki SEO Published & Last Updated Dates will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2025-11-05

### Improved
- Enhanced post type settings UI by integrating per-post-type display settings (show published/updated checkboxes) directly into the target post types section for better usability
- Added HTML comment signatures (`<!-- Kashiwazaki SEO Published & Last Updated Dates -->`) around Schema.org markup output for easier identification in page source

### Fixed
- Optimized query processing for URL conflicts where custom post slugs match post type archive URLs
- Removed unnecessary `is_main_query()` check that was preventing date display in certain edge cases

## [1.0.0] - 2025-09-22

### Added
- Initial release of the plugin
- Automatic display of published and updated dates for posts and pages
- Three shortcode types: `[published_date]`, `[updated_date]`, `[publish_update_dates]`
- PHP functions for direct theme integration
- Non-conflicting structured data using DigitalDocument schema format
- Automatic Last-Modified HTTP header output for better SEO
- Responsive horizontal layout (side-by-side on desktop, vertical on mobile)
- Comprehensive admin settings panel with display options
- Support for custom post types
- Customizable date formats and label texts
- Custom CSS support for advanced styling
- Display position options (before content, after content, or both)
- Display style options (icon+text, text only, icon only)
- Configurable update date display conditions (24 hours after publish by default)

### Technical Details
- Minimum WordPress version: 5.0
- Minimum PHP version: 7.2
- License: GPL-2.0-or-later
- Text domain: kashiwazaki-seo-published-last-updated-dates

### Developer Notes
- Uses singleton pattern for main plugin classes
- Implements WordPress coding standards
- Includes proper internationalization support
- Optimized for performance with minimal database queries