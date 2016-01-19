Attachments Only
================

Sometimes you don’t need all the bells and whistles of the WordPress attachment media view. For instance when you'd want all media files related to a post to actually be attachments of that post. This plugin takes that to the extreme and replaces the default WordPress media uploader view with a simplified version: it only allows adding, removing and rearranging attachments to a post.

## Gotchas:

The plugin in its current state is, due to my lack of experience with the subject matter, a bit of a hack and could use some improvement:

- Currently disables uploading attachments by dragging them onto the tinymce editor.
- As the default media viewer is replaced, any plugins extending that will not work.
- Ideally a future version would optionally allow all other default actions like inserting media and galleries, except when using unattached media files.

## Look ma, no settings!

However, you can modify the post types it applies to, the media button label and the media view title via hooks, for example in your theme's `functions.php` file:

### Add supported post types

```
	/**
	 * Add supported post types.
	 */
	function prefix_add_post_type_support() {
		
		$my_post_types = array( 'post', 'page' );
		
		foreach( $my_post_types as $post_type ) {
			add_post_type_support( $post_type, 'attachments_only' );
		}
	}

	add_action( 'admin_init', 'prefix_add_post_type_support' );

```

### Filter strings

```
	/**
	 * Filter labels.
	 */
	function prefix_filter_attachments_only_labels( $string ) {

		switch( current_filter() ) {
			case 'attachments_only_media_button_label' :
				$string = __( 'Add attachments button', 'my-text-domain' );
				break;
			case 'attachments_only_media_library_title' :
				$string = __( 'Media Library Title', 'my-text-domain' );
				break;
			case 'attachments_only_media_library_button_title'
				$string = __( 'Save Media Libray Selection', 'my-text-domain' );
				break;	

		return $options;
	}

	add_filter( 'attachments_only_media_button_label', 'prefix_filter_attachments_only_labels' );
	add_filter( 'attachments_only_media_library_title', 'prefix_filter_attachments_only_labels' );
	add_filter( 'attachments_only_media_library_button_title', 'prefix_filter_attachments_only_labels' );

```

