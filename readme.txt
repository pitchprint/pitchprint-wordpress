=== Plugin Name ===
Contributors: flexcubed
Tags: customizer, photo album, print shop, web2print, gift print, diy print, product customizer, web-to-print, print software, print solution, HTML5 WYSIWYG, t-shirt designer, wysiwyg print editor, business card
Requires at least: 3.8
Tested up to: 6.4
Stable tag: 10.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

PitchPrint is a Web2Print plugin solution that provides an easy to use interface for creating artworks for prints like Business Card, TShirt, Banners. A beautiful web based print customization app for your online store. Integrates with WooCommerce.

== Description ==

PitchPrint is a plugin solution that runs on WordPress + WooCommerce as a Software service providing your clients the ability to create their designs on the fly. It basically provides printing clients an easy to use WYSIWYG (What you see is what you get) “Do it yourself” interface for creating artworks for print.

It is an HTML5 based solution that allows you to create templates for products like Business Card, TShirt, Banners, Phone Templates etc.

This solution is fully based on pre-designed templates. Design templates are created in the editor which are then loaded by individual clients based on taste and choice, then modified to fit their needs and requirements. Based on our studies, it is far easier for majority of clients to edit an existing design template than create a whole design artwork from scratch especially for people with little background in graphics. In addition, it significantly reduces the overall time frame a client spends from landing on your site to placing an order.

The plugin allows your site to connect to our servers, loading the app tool for your users to create with. What's more.. it's Free and you can integrate in minutes.

Please learn more about this service from our site: [PitchPrint.com](https://pitchprint.com)

== Installation ==

= Minimum Requirements =

* WordPress 3.8 or greater
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater
* WooCommerce 3.0.*

This plugin requires you to have WooCommerce installed. You can download [WooCommerce here:](http://www.woothemes.com/woocommerce) or install via the plugins section of your WordPress installation.

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of PitchPrint, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "PitchPrint" and click Search Plugins. Once you’ve found our plugin, you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

The manual installation method involves downloading our plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Configuration =

1. Using an FTP application like FileZilla, login to your server and change the following folder permissions to 777 (ensure you set every folder / file listed here):
	* plugins/pitchprint/system/settings.php
	* plugins/pitchprint/uploader/files
	* plugins/pitchprint/uploader/files/thumbnail

2. Next, you need to install PitchPrint API Key. On the left side of the admin menu, you should find "PitchPrint" link. Click it and in the admin page, you will find a link labelled "PLEASE INSTALL PITCHPRINT APIKEY".
3. Generate and supply the API Key from [our site:](https://admin.pitchprint.com/domains)
4. Submit the form and once complete, please delete the install folder as instructed.
5. To administer a product, go to Products section in the admin and click the "Add Product" link.
6. There in the Product Data section, you will find "PitchPrint" design template option; select your desired template to assign.

Once an order is placed and a Web2Print design is customized, the order details includes all the PitchPrint details, like high resolution image files, the link to load the project as well as link to download the PDF file. If you do not find these, kindly check to see that the design has these options set to render in the design template section.

= Updating =

After updating, you may need to check your PitchPrint tab again enter in your API and Secret keys.


== Frequently Asked Questions ==

= Does it work on Pad? =

Yes it does work on iPad and tablets. It's built on HTML5.

= How do I get support? =

We provide support via our [Helpscout Portal:](https://pitchprint.com) where you get to make suggestions, discuss with other users on the forum report any bug as well as request a support to getting your store properly working.

= Does the product come with templates and clipart images? =

The product comes with few templates and cliparts. However, you are advised to purchase your own library to suit your client base and product needs. Also, there's a marketplace where designers get to share template ideas. You can start from there and pick your choice as well as create and share with others.

= I have an existing shop. Can I still install PitchPrint on it? =

Absolutely. You can install it over your existing OpenCart, WordPress, PrestaShop, Shopify or personal custom site without doing a fresh cart installation.

= Where will our files be hosted? =

PitchPrint is a Software as Service platform with dedicated servers for processing and storage. Your Picture and PDF files are stored on Amazon S3 storage servers. Design files are stored on our dedicated SSD based servers for swift random access and you can request for your files at anytime.
You can also use our Runtime API service to connect to the server and download any of your files with proper authentication.

= Where can I get more information? =

Check out our [website for more details](https://pitchprint.com)

== Screenshots ==

1. Editor Application.
2. Admin pictures manager.
3. Admin theme manager.
4. Admin settings.

== Changelog ==

== 10.2.2 =
Tested up to WordPress 6.3

== 10.2.2 =
Minor release bug fix

== 10.2.1 =
Tested up to WordPress 6.3

== 10.2.1 =
Updated to using adminUrl in non-product pages.

== 10.2.0 =
Tested up to WordPress 6.3

== 10.2.0 =
Added compatibility for WooCommerce High Performance Order Storage Upgrade
Tested up to WooCommerce 8.0.2

== 10.1.9 =
Tested up to WordPress 6.3

== 10.1.8 =
Updates made to project preview in cart.

== 10.1.7 =
Decoding process improved.

== 10.1.6 =
Fixed session bug.

== 10.1.5 =
Fixed session bug.

== 10.1.4 =
Now using admin-ajax to save and reset project.

== 10.1.2 =
Added a new workflow

== 10.0.28 =
Cleared a few warning logs by checking post keys are set.

== 10.0.27 =
Storing customer project against customer id, if project was created as guest, then customer registered when placing order.

== 10.0.25 =
Now attaching order ID to project.

== 10.0.24 =
Tested upto WordPress 6.2 and WooCommerce 7.5.1

== 10.0.23 =
Fixed glue and pieces implode warning

== 10.0.22 =
Cart image fix.

== 10.0.20 =
Improved metadata display.
It is now possible to change the initialization designId via a filter: set_pitchprint_design_id

== 10.0.19 =
Display pitchprint meta data in cart for newer cart items in later versions of woocommerce.

== 10.0.18 =
Add plugin links

== 10.0.17 =
Category customization added.

== 10.0.16 =
Minor update - initiation.

== 10.0.14 =
Improved saveproject - permissions.

== 10.0.13 =
You can now allow the PitchPrint design preview to act as a product image.

== 10.0.12 =
Made uploaded file names unqiue

== 10.0.11 =
Fixed an issue with WC 5.6

== 10.0.9 =
Checking array offset exists on uploadhandler

== 10.0.8 =
Only showing the duplicate design button on the cart page.

== 10.0.7 =
Tested up to WP 5.7

== 10.0.6 =
Displaying warning message to switch browser if IE browser is detected.

== 10.0.5 =
Displaying warning message to switch browser if IE browser is detected.

== 10.0.4 = 
Minor bug fix relating to session and cookies

== 10.0.3 = 
Minor bug fix relating to cloned projects

== 10.0.2 = 
If cookies are not allowed, we use session.

== 10.0.1 = 
Fixed WC 5.2 compatibility issue.

= 10.0.0 =
No longer using session to store projects. Now storing projects in WP database.

= 9.0.33 =
Tested WP up to 4.3.2

= 9.0.31 =
Updated tested up to 5.4

= 9.0.30 =
Check session on init hook

= 9.0.29 =
Checked headers before session start

= 9.0.28 =
Changed the way session is used, to prevent site health errors.

= 9.0.27 =
Update to make PitchPrint compatible with WordPress version 5.4

= 9.0.26 =
Forced hiding of pp data in item meta

= 9.0.25 = 
Fix for Divi theme

= 9.0.24 =
We tested Woocommerce compatibility up to version 3.6.5

= 9.0.22 =
Set browser valid to true.

= 9.0.21 =
Fixed some minor issue.

= 9.0.20 =
Individually set product to require customization & pdf download.

= 9.0.19 =
Check item empty more better for webhook to proceed.

= 9.0.17 =
Addedd billing address and billing name to webhooks.

= 9.0.16 =
Prevented error on empty post.

= 9.0.15 =
Fixed underfined property 'pp_projects' notice on line 296 of pitchprint.php file near add to car.

= 9.0.14 =
Fixed intval issue on line 341 of uploadHandler.php

= 9.0.13 =
Fixed underfined property 'pp_projects' notice on line 330 of pitchprint.php file.

= 9.0.12 =
Added Order Processing Webhook

= 9.0.11 =
Added Mini Display Mode, please see documentation here:
https://docs.pitchprint.com/article/107-display-modes

= 9.0.10 =
Attach Pitchprint main buttons only once when before add to cart button.

= 9.0.9 =
* Bug fix - related to old php version - more shorthand code .

= 9.0.8 =
* More bug fixes - shorthand code was not compatibable with older php versions.

= 9.0.7 =
* Bug fixes.

= 9.0.6 =
Made sure order-complete webhook only triggers if an order contains a PitchPrint project.

= 9.0.5 = 
Added order-completed webhook.

= 9.0.3 =
Added utils folder for checking non-supported browsers.

= 9.0.2 =
Added warning message for non-supported browsers like IE 11.

= 7.1 - 26/08/2014 =
* Included file upload feature. With this, customers can upload and attach their files to the product instead of using the design app
* Customers can view their recent designs in account page
* Click to duplicate and re-order designs
* Minor bug fixes

= 7.1.1 - 26/08/2014 =
* Added design thumbnails in cart and recent designs
* Added Duplicate Design into custom designed items in shopping cart

= 7.2.0 - 20/03/2015 =
* Major upgrade on both server side and client app which includes:
* Setting loaded Image as Bakcground
* New Nginx and NodeJS based servers for faster scaling
* Variable Data plugin to allow for CSV / Excel sheet uploads which generates products based on the number of rows in the file
* Canvas Adjuster gives customers ability to adjust the canvas at design time or before design
* Color Templates module makes users change template images like TShirt colors within a project design
* Global Instagram App without need for registering your own Instagram App
* Design based default text color
* Create multiple layouts and assign layouts based on designs
* Custom PDF Rendering based on requests
* New Image editor from Adobe Creative Cloud
* Security fixes limiting files that can be uploaded to non-executables
* Streamlined Image Tab with Customer Uploads on a separate tab and option to select default Image tab. This is useful for designs that by default requires users to upload their pictures. So the first tab they get to see is Upload your Picture
* Pixabay Image library search
* Javascripts and stylesheets now loads from Amazon cloud CDN for faster edge delivery
* Free anual premium license for Charities and NGOs
* Ability to add custom JS codes straight from the admin panel

= Minor modifications ==
* Render PDF, Render Raster has been removed. By default, all projects have PDFs rendered while Raster PNG images are generated off the PDF at high resolution based on demand (i.e when you click to download Raster).


= 7.2.1 - 23/03/2015 =
* Fixed conflict issues with WooCommerce PDF Invoices & Packing Slips plugin

= 7.2.2 - 28/03/2015 =
* Fixed a security bug that allows image files uploaded as scripts to run on the server. It's important that .htaccess is enabled on your site. A .htaccess file is included in pitchprint/uploader/files/ directory that prevents PHP from running any uploaded file, no matter the extension but to simply allow file downloads.

= 7.2.4 - 09/04/2015 =
* Minor bug fixes


= 8.0.0 - 12/09/2015 =
* New App build based on Box' T3 Framework (http://t3js.org), so your old layout files will automatically be replaced, but all project and designs will load seamlessly well.
* Added TextBox element
* New Text-Styles feature that allows grouped text and shapes to be added and edited by customers.
* Help system
* Customer can now add their own custom colors
* App loads inline within the page, and of course you still have the option to pop it on a modal window.
* App can load on startup
* Ability to create your own modules and attach to the App using events
* New Form Data module
* In-Context text editor
* App loads way faster with improved caching
* Theme caching is improved so them changes are reflected instantly
* We migrated a lot of static files to CDN, notably the language files
* Improved and Faster PDF generation using more primed servers
* PDF cut lines are now rendered correctly, far off the bleed area
* Lots of bug fixes and optimizations


= 8.0.1 - 01/10/2015 =
* Minor fix for Notice warnings


= 8.2.0 - 01/03/2016 =
* Upgrade to new and faster API 8.2.0
* Fixed bug with preview thumbnails not showing
* Other minor bug fixes


= 8.2.1 - 03/04/2016 =
* Escape slashes for names and addresses with aprostophes
* Expanded user data to include addresses and full name
* Other minor bug fixes

= 8.3.0 - 31/01/2017 =
* New features such as Mobile layout, Item hooks, Thumbnail Navigation, Remix etc
* Kindly note, the custom JavaScript and other options in the plugin page have been moved to PitchPrint settings page: https://admin.pitchprint.io/settings
* Other minor bug fixes
* Kindly read more here: http://docs.pitchprint.com/release-8-3/

= 8.3.1 - 31/01/2017 =
* Minor bug fix

= 8.3.2 - 31/01/2017 =
* Fixes issue with url pointing to PitchPrint domains

= 8.3.3 - 01/02/2017 =
* Minor bug fixes

= 8.3.4 - 08/04/2017 =
* Minor fixes to work with WooCommerce 3.0
* Integration with upcoming Zapier integration and Projects page

= 8.3.6 - 10/06/2017 =
* Minor bug fixes
* After this update, settings are stored in WordPress and will no longer need to re-enter Api & Secret keys. But you need to provide them again just this last time.

= 8.3.8 - 09/10/2017 =
* Updated for WooCommerce 3.1+
* Fixed issue with Zapier integration
* Minor bug fixes

== Upgrade Notice ==

= 7.2.0 =
Kindly re-enter in your API and Sercret keys after the upgrade.

= 8.0.0 =
Kindly re-enter in your API and Sercret keys after the upgrade.
Also note, you need to upgrade your Domain to use version 8 here: https://admin.pitchprint.io/domains.
You can read more on this upgrade here: http://docs.pitchprint.com/article/how-to-upgrade-to-version-8/

= 9.0.0 =
This is a major release. You need to migrate your data to version 9 platform before upgrading.
To migrate, navigate to: https://pitchprint.net/admin/domains
Click on the button: Copy Data to V9
Your data will be copied along with designs in a queue
Once data is copied, you can log in to version 9:
https://admin.pitchprint.io/login with the same credentials you used in version 8
