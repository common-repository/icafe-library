=== iCafe Library ===
Contributors: chrisdnilsson@gmail.com
Tags: library, resource management, staff development, icafe, iCafe Library, groundwork, ground work
Requires at least: 3.6
Tested up to: 3.8.1
Stable tag: 1.8.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

iCafe Library creates beautiful functional organization of resource materials for Professional Development or Training.

== Description ==

The iCafe Library plugin was designed to create beautiful functional resource libraries for Professional Development delivery.

With a few steps you can create highly organized resource libraries designed to allow users to easily find the support documents, videos, or links they need.
 
If you are tired of managing hundreds of how-to guides that users can't find then iCafe Library is for you. Primarily designed to be used by Public Education Staff Development Departments but any department responsible for organizational training can benefit.

Visit a live and growing iCafe Library on the original [iCafe site] [icafe]
**The Promethean and Edmodo books are good examples to look through.** 

*Features:*

*  Simple Resource Tile Creation
*  Organize Resources into Books>Chapters>Sections>Tiles
*  Drag and Drop Reordering

[icafe]: http://icafe.lcisd.org/resources "iCafe"

== Installation ==

1. Upload `iCafe Library` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Make sure you are using "Post Name" permalinks
1. Watch the included tutorial on the main plugin dashboard menu
1. Create some content
1. Place the shortcode '[icafe-library]' on a page
1. Enjoy your library!

== Screenshots ==

1. Your Bookshelf
2. Easy Organization and Navigation
3. Creating a Book
4. Organizing Books, Chapters, Sections
5. Creating a Resource Tile
6. Adding a Tile to a Section


== Frequently asked questions ==

= How do I get started? =

Activate the plugin and click on iCafe Library from the side menu.

= How are resources organized? =

The concept of a bookshelf is used. You create books which can contain Chapters which can contain Sections.

= Where to I put the resources? =

Resource Tiles can be added to any Chapter or Section.

= What are "Resource Tiles" =

Resource should be very narrow in their focus. A "Tile" is a simple container that holds related Text, Video, Embed code, or Links together in a organizational and visual way.

= Can I reuse a Tile? =

As many times as you like!

= How do I change the order of my "Bookshelf" or chapters, sections, resources? =

Simply Drag and Drop to the correct order or organization...Changes are automatically saved.

= Can I display different books on different pages? =

Yes, just go to the custom bookshelf page in the plugin admin menu and select the books you wish to display. Then copy the shortcode provide to your page.

= Can I link to a particular Resource Tile to share with a user? =

Yes, just navigate to that tile and copy the URL from the browser.

= My tiles are appearing below the navigation instead of to the right? =

Your template is too narrow. iCafe Library requires a full width template with no sidebars or widget areas.

= How do I set a default tile for a book to open to? =

In the "Manage Books" admin menu navigate to the book, chapter, or section that contains the tile you want to make default. Click on "Add Resource Tiles" for the desired node and select the radio button beside the desired tile. If you are adding tiles during this operation then you must refresh the "Add Resource Tiles" button to see the radio buttons.

= When I click a book I'm being directed to my home page not the book? =

iCafe Library currently requires your permalink structure to be "Post name". There are many reasons why this permalink structure is the most popular choice among Wordpress sites. You can change your permalinks in your admin menu under Settings>Permalinks.

== Changelog ==

= 1.8.3 =
* Fixed nesting of sections and chapters into books caused by the wordpress core upgrade to JqueryUI 1.10+

= 1.8.2 =
* Fixed an issue that caused some embedded YouTube videos to not display

= 1.8.1 =
* Fixed an issue that caused sections to not fully expand in the accordian navigation menu. Caused by WP updating jquery version

= 1.8 =
* Compatible with Wordpress 3.8+ This is not compatible with versions older than 3.6 due to a change in the version of Jquery included in the core of wordpress
* Fixed bug that caused URL included in tiles to wrap too soon when now other embedded content was included
* Fixed an issue that caused the Book title to not display in WP 3.6+

= 1.7.1 =
* Compatible with Wordpress 3.6+ This is not compatible with older versions due to Wordpress changed the version of Jquery included in the core
* Fixed bug that affected sorting of books and resources

= 1.7 =
* Compatible with Wordpress 3.6+ This is not compatible with older versions due to Wordpress changed the version of Jquery included in the core


= 1.6.2 =
* Fixed a bug that 1.6 introduced causing resource tiles that were public to not display to non-logged in users


= 1.6.1 =
* Fixed a bug that 1.6 introduced on new installations that prevented new books from being created. Did not affect users who upgraded from previous versions


= 1.6 =
* Greatly enhanced the restricted book feature.
* Books can now be restricted to selected users only
* Books can now be restricted to selected roles only
* Roles restriction is compataible with popular roles plugins like "Members"

= 1.5.1 =
* Added the option to embed any HTML code in a tile for advanced users

= 1.5 =
* You can now set a default tile to open to when a user clicks a book from your library


= 1.4 =
* Books, Chapters, or Sections can now be hidden from public view while being developed


= 1.3.1 =
* Minor CSS fix to remove underline from menu links in 1.3.0


= 1.3.0 =
* Create custom bookshelves on multiple pages. You don't need to display all books on all bookshelves
* Simplified YouTube embed
* CSS improvments


= 1.2.0 =
* Added an Admin Menu to allow users to customize the colors of their library
* Simplified the CSS files
* Fixed a layout bug in some themes causing the menu layout to break
* Fixed a bug that caused tiles with an embedded picture to reset to media type NONE on plugin upgrades


= 1.1.3 =
* Minor CSS bug causing 18 character book titles to flow onto two lines on the front end but not the preview window


= 1.1.2 =
* Additional bug fix for users on Windows servers


= 1.1.1 =
* Added the option to include pictures on resource tiles rather than just videos
* Changed styling on sections in the menu to make them more readable
* Added hover tooltip to all tile lists that show the details of the tile
* Fixed bug that caused adding new sections to fail unless they were marked restricted and had a picture. This mainly affected Windows servers but could have impacted anyone running mySQL 5+ not in traditional mode
* Fixed a bug that added slashes to special char (apostrophe) on a tile edit


= 1.0.3 =
* fixed bug that caused white bars to appear above and below the main page in certain themes
* added gracefull deactivation and uninstall. Data will remain intact during an upgrade now


= 1.0.2 =
* added support for legecy groundwork shortcode


= 1.0.1 =
* fix edit section bug


= 1.0 =
* First Public Release.



== Upgrade Notice ==

= 1.8.3 =
* Fixed nesting of sections and chapters into books caused by the wordpress core upgrade to JqueryUI 1.10+

= 1.8.2 =
* Fixed an issue that caused some embedded YouTube videos to not display

= 1.8.1 =
* Fixed an issue that caused sections to not fully expand in the accordian navigation menu. Caused by WP updating jquery version

= 1.8 =
* Compatible with Wordpress 3.8+ This is not compatible with versions older than 3.6 due to a change in the version of Jquery included in the core of wordpress
* Fixed bug that caused URL included in tiles to wrap too soon when now other embedded content was included
* Fixed an issue that caused the Book title to not display in WP 3.6+

= 1.7.1 =
* Compatible with Wordpress 3.6+ This is not compatible with older versions due to Wordpress changed the version of Jquery included in the core
* Fixed bug that affected sorting of books and resources

= 1.7 =
* Compatible with Wordpress 3.6+ This is not compatible with older versions due to Wordpress changed the version of Jquery included in the core


= 1.6.2 =
* Fixed a bug that 1.6 introduced causing resource tiles that were public to not display to non-logged in users


= 1.6.1 =
* Fixed a bug that 1.6 introduced on new installations that prevented new books from being created. Did not affect users who upgraded from previous versions


= 1.6 =
* Greatly enhanced the restricted book feature.
* Books can now be restricted to selected users only
* Books can now be restricted to selected roles only
* Roles restriction is compataible with popular roles plugins like "Members"

= 1.5.1 =
* Added the option to embed any HTML code in a tile for advanced users

= 1.5 =
* You can now set a default tile to open to when a user clicks a book from your library

= 1.4 =
* Books, Chapters, or Sections can now be hidden from public view while being developed


= 1.3.1 =
* Minor CSS fix to remove underline from menu links in 1.3.0


= 1.3.0 =
* Create custom bookshelves on multiple pages. You don't need to display all books on all bookshelves
* Simplified YouTube embed
* CSS improvments


= 1.2.0 =
* Added an Admin Menu to allow users to customize the colors of their library
* Simplified the CSS files
* Fixed a layout bug in some themes causing the menu layout to break
* Fixed a bug that caused tiles with an embedded picture to reset to media type NONE on plugin upgrades


= 1.1.3 =
* Minor CSS bug causing 18 character book titles to flow onto two lines on the front end but not the preview window


= 1.1.2 =
* Additional bug fix for users on Windows servers


= 1.1.1 =
* Added the option to include pictures on resource tiles rather than just videos
* Major bug fix affecting some Windows server users
* Minor Style updates


= 1.0.1 =
*fix bug when editing existing sections or books