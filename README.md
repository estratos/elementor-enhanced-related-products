Elementor Enhanced Related Products
Version: 1.1.0
Tested up to: WordPress 6.9, WooCommerce 10.4, Elementor 3.34, PHP 8.3
License: GPL v2 or later
Plugin URI: https://github.com/your-username/elementor-enhanced-related-products
Tags: elementor, woocommerce, related products, product grid, ecommerce

📖 Description
Elementor Enhanced Related Products is an advanced widget for Elementor Pro that provides complete control over the logic for displaying related products in WooCommerce. It solves the issues of Elementor's native widget (which shows random products) by offering multiple precise filtering methods and manual selection.

✨ Key Features
5 different filtering logics: Category, Tag, Both (AND), Combined (OR), and Manual

Manual product selection: Absolute control product by product

Native WooCommerce integration: Respects your theme's templates and styles

Complete responsive controls: Independent configuration for mobiles and tablets

CSS Grid design: Maximum performance and flexibility

Fully compatible: Elementor 3.5+, PHP 7.4+, WordPress 6.0+

Optimized code: Specifically for PHP 8.3 and WordPress 6.9

🚀 Installation
Method 1: Direct Upload (Recommended)
Download the ZIP file from GitHub

Go to Plugins > Add New > Upload Plugin in your WordPress

Upload the ZIP file and activate the plugin

Method 2: Via FTP
Unzip the ZIP file

Upload the elementor-enhanced-related-products folder to /wp-content/plugins/

Activate the plugin from the WordPress panel

Method 3: Git Clone
bash
cd /wp-content/plugins/
git clone https://github.com/your-username/elementor-enhanced-related-products.git
🛠 Configuration and Usage
System Requirements
WordPress 6.0 or higher

Elementor Pro 3.5 or higher

WooCommerce 6.0 or higher

PHP 7.4 or higher (PHP 8.0+ recommended)

Steps to Use the Widget
Edit a Single Product Template in Elementor

Search for the widget "Enhanced Related Products" in the search bar

Drag it to your design

Configure the parameters:

Parameter	Description	Values
Filter Logic	Method to find related products	Category Only, Tag Only, Both (AND), Combined (OR), Manual Selection
Products Count	Number of products to display	1-50 (default: 4)
Columns	Number of columns in the grid	1-6 (default: 4)
Order By	Sorting criteria	Random, Date, Title, Price, Popularity, Rating, Menu Order
Show Heading	Display section title	Yes/No
Heading Text	Custom text for the title	Any text
Filtering Logics Explained
Logic	Behavior	Ideal Use Case
Category Only	Shows products from the SAME category	Stores with well-defined categories
Tag Only	Shows products with the SAME tags	Products related by features (e.g., "summer", "sale")
Both (AND)	Products that share category AND tag	Very specific recommendations (WooCommerce default)
Combined (OR)	Products that share category OR tag	More recommendations, higher conversion rate
Manual Selection	Manually selected products	Full control for featured products or bundles
📁 Plugin Structure
text
elementor-enhanced-related-products/
├── elementor-enhanced-related-products.php      # Main file
├── widget-enhanced-related-products.php         # Elementor widget
├── assets/
│   ├── css/
│   │   └── frontend.css                        # Additional styles
│   └── js/
│       └── admin.js                            # Control scripts
├── languages/                                   # Translations
│   ├── elementor-enhanced-related-es_ES.po
│   └── elementor-enhanced-related-es_ES.mo
└── README.md                                    # This file
🎨 Customization
CSS Styles
The plugin generates the following CSS classes for customization:

css
/* Main container */
.eerp-enhanced-related-products {}

/* Section title */
.eerp-heading {}

/* Products grid */
.eerp-products-grid {
    display: grid;
    gap: 20px; /* Configurable from the widget */
}

/* Individual product */
.eerp-products-grid .product {}
Available Filters and Actions
php
// Filter query arguments
add_filter('eerp/query_args', function($args, $product_id, $settings) {
    // Modify $args before executing the query
    $args['posts_per_page'] = 6; // Show 6 products instead of 4
    return $args;
}, 10, 3);

// Filter manually selected products
add_filter('eerp/manual_product_ids', function($product_ids, $settings) {
    // Add additional products to manual selection
    $product_ids[] = 123; // Additional product ID
    return $product_ids;
}, 10, 2);

// Action before rendering
add_action('eerp/before_render', function($product, $settings) {
    // Code to execute before rendering the widget
    error_log('Rendering related products for: ' . $product->get_id());
});
🔧 Troubleshooting
Common Issues and Solutions
Problem	Likely Cause	Solution
Widget doesn't appear in Elementor	Elementor Pro not active	Activate Elementor Pro and update to latest version
No products displayed	Product has no categories/tags	Assign categories or tags to the product
Error "widget works only on single product pages"	Widget used on wrong page	Use widget only in Single Product templates
Broken layout on mobile	Incorrect responsive settings	Adjust "Columns Gap" and "Rows Gap" in Style tab
Random products (original problem)	Incorrect native widget configuration	Replace native widget with this plugin
Debugging
Add this to your wp-config.php to see detailed errors:

php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);
Errors will be saved to /wp-content/debug.log

📝 Changelog
1.1.0 (Current Version)
✅ Added manual product selection (overrides automatic logic)

✅ Improved integration with native WooCommerce templates

✅ Responsive controls for column/row gaps

✅ Specific optimization for PHP 8.3

✅ Configurable column system (1-6 columns)

1.0.0
✅ Initial release with 4 automatic filtering logics

✅ Basic integration with Elementor framework

✅ Support for WordPress 6.0+ and WooCommerce 6.0+

🤝 Contributions
Contributions are welcome. Please follow these steps:

Fork the repository

Create a branch for your feature (git checkout -b feature/NewFeature)

Commit your changes (git commit -m 'Add NewFeature')

Push to the branch (git push origin feature/NewFeature)

Open a Pull Request

Code Style Guide
Follow WordPress coding standards

Use English comments for code

Document new functions with PHPDoc

Add tests for new features when possible

📄 License
This plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

🆘 Support
For technical support:

GitHub Issues: Report an issue

Check first: Make sure to:

Have required versions (WordPress 6.0+, PHP 7.4+)

Have tried deactivating other plugins (common conflict)

Have cleared site and browser cache

Need immediate help?

Provide your WordPress, PHP, Elementor, and WooCommerce versions

Describe exactly what happens and what you expected to happen

Include screenshots if possible

⭐ Acknowledgments
Elementor team for their excellent widget framework

WooCommerce community for stable APIs

Open source contributors who make these projects possible

Like this plugin? Consider giving us a star on GitHub! ⭐

Did it solve your random products problem? Tell us about your experience!
