=== Accordion Slider ===
Contributors: bqworks
Tags: accordion slider, responsive slider, responsive accordion, image accordion, accordion plugin, vertical accordion
Requires at least: 3.6
Tested up to: 5.8.1
Stable tag: 1.8.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Fully responsive and touch-enabled accordion slider plugin for WordPress.

== Description ==

Accordion Slider combines the look and functionality of a slider with that of an accordion, allowing you to create horizontal or vertical accordion sliders which are fully responsive and mobile-friendly.

Features:

* Fully responsive
* Touch support
* Animated and static layers, which can contain text, images or any HTML content
* Horizontal and Vertical orientation
* Possibility to change the aspect of the accordion for different screen sizes (using breakpoints)
* Pagination
* Keyboard navigation
* Mouse wheel navigation
* Retina support
* Lazy loading
* Deep linking
* Lightbox integration
* Load images and content dynamically, from posts (including custom post types), WordPress galleries and Flickr
* Preview accordion sliders directly in the admin area
* Drag and drop panel sorting
* Publish accordion sliders in any post (including pages and custom post types), in PHP code, and widget areas
* Caching system for quick loading times
* Action and filter hooks
* Import and export accordion sliders

[These videos](http://bqworks.net/accordion-slider/screencasts/) demonstrate the full capabilities of the plugin.

== Installation ==

To install the plugin:

1. Install the plugin through Plugins > Add New > Upload or by copying the unzipped package to wp-content/plugins/.
2. Activate the Accordion Slider plugin through the 'Plugins > Installed Plugins' menu in WordPress.

To create accordion sliders:

1. Go to Accordion Slider > Add New and click the 'Add Panels' button.
2. Select one or more images from the Media Library and click 'Insert into post'. 
3. After you customized the accordion slider, click the 'Create' button.

To publish accordion sliders:

Copy the [accordion_slider id="1"] shortcode in the post or page where you want the accordion to appear. You can also insert it in PHP code by using <?php do_shortcode( '[accordion_slider id="1"]' ); ?>, or in the widgets area by using the built-in Accordion Slider widget.

== Frequently Asked Questions ==

= How can I set the size of the images? =

When you select an image from the Media Library, in the right columns, under 'ATTACHMENT DISPLAY SETTINGS', you can use the 'Size' option to select the most appropriate size for the images.

== Screenshots ==

1. Accordion slider with text layers.
2. Simple accordion slider.
3. Vertical accordion slider.
4. Accordion slider with mixed content.
5. The admin interface for creating and editing an accordion.
6. The preview window in the admin area.
7. The layer editor in the admin area.
8. The background image editor in the admin area.
9. Adding dynamic tags for accordions generated from posts.

== Changelog ==

= 1.8.0 =
* initial WordPress.org release

= 1.8.1 =
* fixed type of Width and Height from 'number' to 'mixed' to address validation issue

= 1.8.2 =
* fixed the saving/updating functionality