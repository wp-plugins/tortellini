=== Tortellini ===
Contributors: thcxthcx
Donate link: http://thcxthcx.net/donate.php
Tags: tor, anonymity, activism
Requires at least: 3.0.5
Tested up to: 3.0.5
Stable tag: 0.1.0

Fetches Tor bridges and displays them in different ways with a shortcode

== Description ==

This plugin fetches Tor bridges and displays them in three different ways:

* as links added to random words in a piece of text
* as normal text
* as an HTML comment

The idea is that you install the plugin, use Tortellini to display Tor bridges
in a less popular page/post in a corner of your site, and then you change
robots.txt so well-behaved robots won't index that page or post.

That way, people that know about it can get Tor bridges from you (preferably over
https), if they are blocked from getting them somewhere else.

== Installation ==

* Unzip the plugin archive into the "wp-content/plugins/" directory.
* Activate the plugin in the "Plugins" menu.
* Create a page or post in an obscure corner of your site with one of these shortcodes:

1. [tortellini text="lots of text"]
(This will use random words in your text as links to the bridges.
The text may not contain HTML, but you can write "\\\\n" for a new line.)
1. [tortellini list="y"]
(This will display the bridges with linebreaks between them.)
1. [tortellini comment="y"]
(This will put the bridges in an HTML comment.)

* Put the page or post in robots.txt, so it won't be indexed by well-behaved robots.
* Tell net activists and freedom fighters about your page or post.

== Frequently Asked Questions ==

None yet

== Screenshots ==

None yet

== Changelog ==

= 0.1.0 =
* First real release

= 0.0.1 =
* Only announced on Twitter to a smaller group of beta testers

== Upgrade Notice ==

= 0.1.0 =

Users of 0.0.1 should upgrade to 0.1.0, as it's the first real release.
