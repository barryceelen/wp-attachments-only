Attachments Only
================

Sometimes you don’t need all the bells and whistles of the WordPress attachment media view. For instance when you'd want all media files related to a post to actually be attachments of that post. This plugin takes that to the extreme and replaces the default WordPress media uploader view with a simplified version: it only allows adding, removing and rearranging attachments to a post.

## Gotchas:

The plugin in its current state, due to my lack of experience with the subject matter, is a bit of a hack and could use some improvement:

- Currently does not support (or even allow) setting a featured image, this definitely needs fixing.
- As the default media viewer is replaced, any plugins extending that will not work.
- Ideally a future version would optionally allow all other default actions like inserting media and galleries, except when using unattached media files.

## Look ma, no settings!

However, you can modify the post types it applies to, the media button label and the media view title via a [filter](http://codex.wordpress.org/Plugin_API#Filters) on `attachments_only_options`, for example in your theme's `functions.php` file:


```
	function prefix_filter_attachments_only_options( $options ) {

		// Add a custom post type.
		$options['post_types'][] = 'my_custom_post_type';
		// Change the button label.
		$options['media_button_label'] = __( 'My Cool Button Label', 'my-text-domain' );
		// Change the view title.
		$options['media_library_title'] = __( 'My Cool Title', 'my-text-domain' );

		return $options;
	}

	add_filter( 'attachments_only_options', 'prefix_filter_attachments_only_options' );
```

## Pull requests

Pull requests are very welcome, please use the `dev` branch.