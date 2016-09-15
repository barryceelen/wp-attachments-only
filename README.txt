=== Attachments Only ===
Contributors: barryceelen
Tags: attachments, admin, upload, media view
Requires at least: 3.5
Tested up to: 4.1.1
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Simplify the default WordPress media view by removing all functionality except adding, deleting and rearranging attachments.

== Description ==

Sometimes you don’t need all the bells and whistles of the WordPress attachment media view. This plugin takes that to the extreme and replaces the default WordPress media uploader view with a simplified version. It only allows adding, removing and rearranging attachments to a post.

**Gotchas:**

The plugin in its current state is, due to my lack of experience with the subject matter, a bit of a hack and could use some improvement:

- Currently disables uploading attachments by dragging them onto the tinymce editor.
- As the default media viewer is replaced, any plugins extending that will not work.
- Ideally a future version would optionally allow all other default actions like inserting media and galleries, except when using unattached media files.

= Github =

Issues and pull requests welcome on [Github](https://github.com/barryceelen/wp-attachments-only).

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'attachments-only'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `attachments-only.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `attachments-only.zip`
2. Extract the `attachments-only` directory to your computer
3. Upload the `attachments-only` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard

== Changelog ==

= 0.0.1 =
* Initial release.

