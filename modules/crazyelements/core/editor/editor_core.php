<?php
namespace CrazyElements\Core\Editor;

use CrazyElements\PrestaHelper;
use Tools;
final class Editor_core {

	public static $mce_locale;

	private static $mce_settings = array();
	private static $qt_settings  = array();
	private static $plugins      = array();
	private static $qt_buttons   = array();
	private static $ext_plugins;
	private static $baseurl;
	private static $first_init;
	private static $this_tinymce       = false;
	private static $this_quicktags     = false;
	private static $has_tinymce        = false;
	private static $has_quicktags      = false;
	private static $has_medialib       = false;
	private static $editor_buttons_css = true;
	private static $drag_drop_upload   = false;
	private static $old_dfw_compat     = false;
	private static $translation;
	private static $tinymce_scripts_printed = false;
	private static $link_dialog_printed     = false;


	private function __construct() {
	}

	public static function ps_parse_args( $args, $defaults = '' ) {
		if ( is_object( $args ) ) {
			$parsed_args = get_object_vars( $args );
		} elseif ( is_array( $args ) ) {
			$parsed_args =& $args;
		} else {
			parse_str( $args, $parsed_args );
		}

		if ( is_array( $defaults ) ) {
			return array_merge( $defaults, $parsed_args );
		}
		return $parsed_args;
	}

	/**
	 * @param string $editor_id ID for the current editor instance.
	 * @return array Parsed arguments array.
	 */
	public static function parse_settings( $editor_id, $settings ) {

		/**
		 * @since 1.0.0
		 * @param array  $settings  Array of editor arguments.
		 * @param string $editor_id ID for the current editor instance.
		 */
		$settings = $settings;

		$set = self::ps_parse_args(
			$settings,
			array(
				// Disable autop if the current post has blocks in it.
				'wpautop'             => false,
				'media_buttons'       => true,
				'default_editor'      => '',
				'drag_drop_upload'    => false,
				'textarea_name'       => $editor_id,
				'textarea_rows'       => 20,
				'tabindex'            => '',
				'tabfocus_elements'   => ':prev,:next',
				'editor_css'          => '',
				'editor_class'        => '',
				'teeny'               => false,
				'dfw'                 => false,
				'_content_editor_dfw' => false,
				'tinymce'             => true,
				'quicktags'           => true,
			)
		);

		self::$this_tinymce = ( $set['tinymce'] );

		if ( self::$this_tinymce ) {
			if ( false !== strpos( $editor_id, '[' ) ) {
				self::$this_tinymce = false;
				_deprecated_argument( 'wp_editor()', '3.9.0', 'TinyMCE editor IDs cannot have brackets.' );
			}
		}

		self::$this_quicktags = (bool) $set['quicktags'];

		if ( self::$this_tinymce ) {
			self::$has_tinymce = true;
		}

		if ( self::$this_quicktags ) {
			self::$has_quicktags = true;
		}

		if ( $set['dfw'] ) {
			self::$old_dfw_compat = true;
		}

		if ( empty( $set['editor_height'] ) ) {
			return $set;
		}

		if ( 'content' === $editor_id && empty( $set['tinymce']['wp_autoresize_on'] ) ) {
			// A cookie (set when a user resizes the editor) overrides the height.
			$cookie = (int) get_user_setting( 'ed_size' );

			if ( $cookie ) {
				$set['editor_height'] = $cookie;
			}
		}

		if ( $set['editor_height'] < 50 ) {
			$set['editor_height'] = 50;
		} elseif ( $set['editor_height'] > 5000 ) {
			$set['editor_height'] = 5000;
		}

		return $set;
	}

	/**
	 * Outputs the HTML for a single instance of the editor.
	 *
	 * @param string $content   The initial content of the editor.
	 * @param string $editor_id ID for the textarea and TinyMCE and Quicktags instances (can contain only ASCII letters and numbers).
	 * @param array  $settings  See _WP_Editors::parse_settings() for description.
	 */
	public static function editor( $content, $editor_id, $settings = array() ) {
		$set            = self::parse_settings( $editor_id, $settings );
		$editor_class   = ' class="' . trim( $set['editor_class'] . ' wp-editor-area' ) . '"';
		$tabindex       = $set['tabindex'] ? ' tabindex="' . (int) $set['tabindex'] . '"' : '';
		$default_editor = 'html';
		$buttons        = '';
		$autocomplete   = '';
		$editor_id_attr = $editor_id;

		if ( $set['drag_drop_upload'] ) {
			self::$drag_drop_upload = true;
		}

		if ( ! empty( $set['editor_height'] ) ) {
			$height = ' style="height: ' . (int) $set['editor_height'] . 'px"';
		} else {
			$height = ' rows="' . (int) $set['textarea_rows'] . '"';
		}

		if ( self::$this_tinymce ) {
			$autocomplete = ' autocomplete="off"';

			if ( self::$this_quicktags ) {
				$default_editor = $set['default_editor'] ? $set['default_editor'] : 'tinymce';
				if ( 'html' !== $default_editor ) {
					$default_editor = 'tinymce';
				}

				$buttons .= '<button type="button" id="' . $editor_id_attr . '-tmce" class="wp-switch-editor switch-tmce"' .
				' data-wp-editor-id="' . $editor_id_attr . '">' . 'Visual' . "</button>\n";
				$buttons .= '<button type="button" id="' . $editor_id_attr . '-html" class="wp-switch-editor switch-html"' .
				' data-wp-editor-id="' . $editor_id_attr . '">' . 'Text' . "</button>\n";
			} else {
				$default_editor = 'tinymce';
			}
		}

		$switch_class = 'html' === $default_editor ? 'html-active' : 'tmce-active';
		$wrap_class   = 'wp-core-ui wp-editor-wrap ' . $switch_class;

		if ( $set['_content_editor_dfw'] ) {
			$wrap_class .= ' has-dfw';
		}

		echo '<div id="wp-' . $editor_id_attr . '-wrap" class="' . $wrap_class . '">';

		if ( self::$editor_buttons_css ) {
			self::$editor_buttons_css = false;
		}

		if ( ! empty( $set['editor_css'] ) ) {
			echo $set['editor_css'] . "\n";
		}

		if ( ! empty( $buttons ) || $set['media_buttons'] ) {
			echo '<div id="wp-' . $editor_id_attr . '-editor-tools" class="wp-editor-tools hide-if-no-js">';

			if ( $set['media_buttons'] ) {
				self::$has_medialib = true;

				echo '<div id="wp-' . $editor_id_attr . '-media-buttons" class="wp-media-buttons">';

				/**
				 * Fires after the default media button(s) are displayed.
				 *
				 * @since 1.0.0
				 *
				 * @param string $editor_id Unique editor identifier, e.g. 'content'.
				 */
				echo "</div>\n";
			}

			echo '<div class="wp-editor-tabs">' . $buttons . "</div>\n";
			echo "</div>\n";
		}

		$quicktags_toolbar = '';

		if ( self::$this_quicktags ) {
			if ( 'content' === $editor_id && ! empty( $GLOBALS['current_screen'] ) && $GLOBALS['current_screen']->base === 'post' ) {
				$toolbar_id = 'ed_toolbar';
			} else {
				$toolbar_id = 'qt_' . $editor_id_attr . '_toolbar';
			}

			$quicktags_toolbar = '<div id="' . $toolbar_id . '" class="quicktags-toolbar"></div>';

		}

		/**
		 * Filters the HTML markup output that displays the editor.
		 *
		 * @since 1.0.0
		 *
		 * @param string $output Editor's HTML markup.
		 */
		$the_editor = '<div id="wp-' . $editor_id_attr . '-editor-container" class="wp-editor-container">' .
		$quicktags_toolbar .
		'<textarea' . $editor_class . $height . $tabindex . $autocomplete . ' cols="40" name="' . $set['textarea_name'] . '" ' .
		'id="' . $editor_id_attr . '">%s</textarea></div>';	

		/**
		 * Filters the default editor content.
		 *
		 * @since 1.0.0
		 *
		 * @param string $content        Default editor content.
		 * @param string $default_editor The default editor for the current user.
		 *                               Either 'html' or 'tinymce'.
		 */
		
		if ( false !== stripos( $content, 'textarea' ) ) {
			$content = preg_replace( '%</textarea%i', '&lt;/textarea', $content );
		}

		printf( $the_editor, $content );
		echo "\n</div>\n\n";

		self::editor_settings( $editor_id, $set );
	}

	/**
	 * @global string $tinymce_version
	 *
	 * @param string $editor_id
	 * @param array  $set
	 */
	public static function editor_settings( $editor_id, $set ) {
		global $tinymce_version;

		
		if ( self::$this_quicktags ) {

			$qtInit = array(
				'id'      => $editor_id,
				'buttons' => '',
			);

			if ( is_array( $set['quicktags'] ) ) {
				$qtInit = array_merge( $qtInit, $set['quicktags'] );
			}

			if ( empty( $qtInit['buttons'] ) ) {
				$qtInit['buttons'] = 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close';
			}

			if ( $set['_content_editor_dfw'] ) {
				$qtInit['buttons'] .= ',dfw';
			}

			/**
			 * Filters the Quicktags settings.
			 *
			 * @since 1.0.0
			 *
			 * @param array  $qtInit    Quicktags settings.
			 * @param string $editor_id The unique editor ID, e.g. 'content'.
			 */
			self::$qt_settings[ $editor_id ] = $qtInit;

			self::$qt_buttons = array_merge( self::$qt_buttons, explode( ',', $qtInit['buttons'] ) );
		}

		if ( self::$this_tinymce ) {

			if ( empty( self::$first_init ) ) {
				$baseurl     = self::get_baseurl();
				$mce_locale  = self::get_mce_locale();
				$ext_plugins = '';

				if ( $set['teeny'] ) {

					/**
					 * Filters the list of teenyMCE plugins.
					 *
					 * @since 1.0.0
					 *
					 * @param array  $plugins   An array of teenyMCE plugins.
					 * @param string $editor_id Unique editor identifier, e.g. 'content'.
					 */
					$plugins = array( 'colorpicker', 'lists', 'fullscreen', 'image' );
				} else {

					/**
					 * @since 1.0.0
					 *
					 * @param array $external_plugins An array of external TinyMCE plugins.
					 */
					$mce_external_plugins = array();

					$plugins = array(
						'charmap',
						'code',
						'colorpicker',
						'hr',
						'advlist',
						'media',
						'paste',
						'tabfocus',
						'textcolor',
						'fullscreen',
						'image',
						'link',
						"table",
						'align',
						
					);

					if ( ! self::$has_medialib ) {
						$plugins[] = 'image';
					}

					/**
					 * @since 1.0.0
					 *
					 * @param array $plugins An array of default TinyMCE plugins.
					 */
					$plugins = array_unique( $plugins );

					$key = array_search( 'spellchecker', $plugins );
					if ( false !== $key ) {
						unset( $plugins[ $key ] );
					}

					if ( ! empty( $mce_external_plugins ) ) {

						/**
						 * @since 1.0.0
						 *
						 * @param array $translations Translations for external TinyMCE plugins.
						 */
						$mce_external_languages = array();

						$loaded_langs = array();
						$strings      = '';

						if ( ! empty( $mce_external_languages ) ) {
							foreach ( $mce_external_languages as $name => $path ) {
								if ( @is_file( $path ) && @is_readable( $path ) ) {
									include_once $path;
									$ext_plugins   .= $strings . "\n";
									$loaded_langs[] = $name;
								}
							}
						}

						foreach ( $mce_external_plugins as $name => $url ) {
							if ( in_array( $name, $plugins, true ) ) {
								unset( $mce_external_plugins[ $name ] );
								continue;
							}

							$url                           = set_url_scheme( $url );
							$mce_external_plugins[ $name ] = $url;
							$plugurl                       = dirname( $url );
							$strings                       = '';
							$ext_plugins .= 'tinyMCEPreInit.load_ext("' . $plugurl . '", "' . $mce_locale . '");' . "\n";
						}
					}
				}

				self::$plugins     = $plugins;
				self::$ext_plugins = $ext_plugins;

				$settings            = self::default_settings();
				$settings['plugins'] = implode( ',', $plugins );

				if ( ! empty( $mce_external_plugins ) ) {
					$settings['external_plugins'] = Tools::jsonEncode( $mce_external_plugins );
				}
				if ( PrestaHelper::apply_filters( 'disable_captions', '' ) ) {
					$settings['wpeditimage_disable_captions'] = true;
				}

				$mce_css = $settings['content_css'];
				$mce_css = trim( PrestaHelper::apply_filters( 'mce_css', $mce_css ), ' ,' );

				if ( ! empty( $mce_css ) ) {
					$settings['content_css'] = $mce_css;
				} else {
					unset( $settings['content_css'] );
				}

				self::$first_init = $settings;
			}

			if ( $set['teeny'] ) {
				$mce_buttons   = array( 'bold', 'italic', 'underline', 'blockquote', 'strikethrough', 'bullist', 'numlist', 'alignleft', 'aligncenter', 'alignright', 'undo', 'redo', 'link', 'fullscreen' );
				$mce_buttons_2 = array();
				$mce_buttons_3 = array();
				$mce_buttons_4 = array();
			} else {
				$mce_buttons = array( 'formatselect', 'bold', 'italic', 'underline','code','blockquote');
				$mce_buttons_2 = array(   'charmap', 'outdent', 'indent', 'undo', 'redo','strikethrough', 'hr', 'fullscreen' );
				$mce_buttons_3 = array( 'pastetext', 'removeformat', 'align','bullist', 'numlist','table' );
				$mce_buttons_4 = array( 'link', 'unlink', 'anchor','image','media', 'colorpicker','forecolor');
			}

			$body_class = $editor_id;
			if ( ! empty( $set['tinymce']['body_class'] ) ) {
				$body_class .= ' ' . $set['tinymce']['body_class'];
				unset( $set['tinymce']['body_class'] );
			}

			$mceInit = array(
				'selector'          => "#$editor_id",
				'wpautop'           => (bool) $set['wpautop'],
				'indent'            => ! $set['wpautop'],
				'toolbar1'          => implode( ',', $mce_buttons ),
				'toolbar2'          => implode( ',', $mce_buttons_2 ),
				'toolbar3'          => implode( ',', $mce_buttons_3 ),
				'toolbar4'          => implode( ',', $mce_buttons_4 ),
				'tabfocus_elements' => $set['tabfocus_elements'],
				'body_class'        => $body_class,
				
			);

			// Merge with the first part of the init array
			$mceInit = array_merge( self::$first_init, $mceInit );

			if ( is_array( $set['tinymce'] ) ) {
				$mceInit = array_merge( $mceInit, $set['tinymce'] );
			}
			if ( empty( $mceInit['toolbar3'] ) && ! empty( $mceInit['toolbar4'] ) ) {
				$mceInit['toolbar3'] = $mceInit['toolbar4'];
				$mceInit['toolbar4'] = '';
			}

			self::$mce_settings[ $editor_id ] = $mceInit;
		} // end if self::$this_tinymce
	}



	private static function wp_is_mobile() {
		if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$is_mobile = false;
		} elseif ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Mobile' ) !== false // many mobile devices (all iPhone, iPad, etc.)
			|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Android' ) !== false
			|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Silk/' ) !== false
			|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Kindle' ) !== false
			|| strpos( $_SERVER['HTTP_USER_AGENT'], 'BlackBerry' ) !== false
			|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mini' ) !== false
			|| strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mobi' ) !== false
		) {
				$is_mobile = true;
		} else {
			$is_mobile = false;
		}

		/**
		 * Filters whether the request should be treated as coming from a mobile device or not.
		 *
		 * @since 1.0.0
		 *
		 * @param bool $is_mobile Whether the request is from a mobile device or not.
		 */
		return $is_mobile;
	}

	/**
	 * @param  array $init
	 * @return string
	 */
	private static function _parse_init( $init ) {
		$options = '';

		foreach ( $init as $key => $value ) {
			if ( is_bool( $value ) ) {
				$val      = $value ? 'true' : 'false';
				$options .= $key . ':' . $val . ',';
				continue;
			} elseif ( ! empty( $value ) && is_string( $value ) && ( ( '{' == $value[0] && '}' == $value[ strlen( $value ) - 1 ] )
				|| ( '[' == $value[0] && ']' == $value[ strlen( $value ) - 1 ] )
				|| preg_match( '/^\(?function ?\(/', $value ) )
			) {

				$options .= $key . ':' . $value . ',';
				continue;
			}

			$options .= $key . ':"' . $value . '",';
		}

		return '{' . trim( $options, ' ,' ) . '}';
	}

	/**
	 *
	 * @static
	 *
	 * @param bool $default_scripts Optional. Whether default scripts should be enqueued. Default false.
	 */
	public static function enqueue_scripts( $default_scripts = false ) {

		$quicktagsL10n = array(
			'closeAllOpenTags'      => PrestaHelper::__( 'Close all open tags' ),
			'closeTags'             => PrestaHelper::__( 'close tags' ),
			'enterURL'              => PrestaHelper::__( 'Enter the URL' ),
			'enterImageURL'         => PrestaHelper::__( 'Enter the URL of the image' ),
			'enterImageDescription' => PrestaHelper::__( 'Enter a description of the image' ),
			'textdirection'         => PrestaHelper::__( 'text direction' ),
			'toggleTextdirection'   => PrestaHelper::__( 'Toggle Editor Text Direction' ),
			'dfw'                   => PrestaHelper::__( 'Distraction-free writing mode' ),
			'strong'                => PrestaHelper::__( 'Bold' ),
			'strongClose'           => PrestaHelper::__( 'Close bold tag' ),
			'em'                    => PrestaHelper::__( 'Italic' ),
			'emClose'               => PrestaHelper::__( 'Close italic tag' ),
			'link'                  => PrestaHelper::__( 'Insert link' ),
			'blockquote'            => PrestaHelper::__( 'Blockquote' ),
			'blockquoteClose'       => PrestaHelper::__( 'Close blockquote tag' ),
			'del'                   => PrestaHelper::__( 'Deleted text (strikethrough)' ),
			'delClose'              => PrestaHelper::__( 'Close deleted text tag' ),
			'ins'                   => PrestaHelper::__( 'Inserted text' ),
			'insClose'              => PrestaHelper::__( 'Close inserted text tag' ),
			'image'                 => PrestaHelper::__( 'Insert image' ),
			'ul'                    => PrestaHelper::__( 'Bulleted list' ),
			'ulClose'               => PrestaHelper::__( 'Close bulleted list tag' ),
			'ol'                    => PrestaHelper::__( 'Numbered list' ),
			'olClose'               => PrestaHelper::__( 'Close numbered list tag' ),
			'li'                    => PrestaHelper::__( 'List item' ),
			'liClose'               => PrestaHelper::__( 'Close list item tag' ),
			'code'                  => PrestaHelper::__( 'Code' ),
			'codeClose'             => PrestaHelper::__( 'Close code tag' ),
			'more'                  => PrestaHelper::__( 'Insert Read More tag' ),
		);
		$wpLinkL10n    = array(
			'title'          => PrestaHelper::__( 'Insert/edit link' ),
			'update'         => PrestaHelper::__( 'Update' ),
			'save'           => PrestaHelper::__( 'Add Link' ),
			'noTitle'        => PrestaHelper::__( '(no title)' ),
			'noMatchesFound' => PrestaHelper::__( 'No results found.' ),
			'linkSelected'   => PrestaHelper::__( 'Link selected.' ),
			'linkInserted'   => PrestaHelper::__( 'Link inserted.' ),
		);
		echo "<script type='text/javascript'>var wpLinkL10n='".Tools::jsonEncode($wpLinkL10n)."'</script>\n";
		echo "<script type='text/javascript'>var quicktagsL10n='".Tools::jsonEncode($quicktagsL10n)."'</script>\n";
		echo '<script src="' .CRAZY_ASSETS_URL .'js/jquery-1.11.0.min.js"></script>';
		echo '<script src="' .CRAZY_ASSETS_URL .'js/tiny_mce/wplink.js"></script>';
		echo '<script src="' ._PS_JS_DIR_ .'tiny_mce/tinymce.min.js"></script>';
		echo '<script src="' .CRAZY_ASSETS_URL .'js/tiny_mce/quicktags.min.js"></script>';
		echo '<script src="' .CRAZY_ASSETS_URL .'js/tiny_mce/editor.js"></script>';
		echo '<link rel="stylesheet" href="'.CRAZY_ASSETS_URL.'css/skin.min.css">';
		echo '<link rel="stylesheet" href="'.CRAZY_ASSETS_URL.'css/editor.icon.min.css">';
		echo "<script type='text/javascript'>\n" . self::wp_mce_translation() . "</script>\n";
	}

	/**
	 * Enqueue all editor scripts.
	 * For use when the editor is going to be initialized after page load.
	 *
	 * @since 1.0.0
	 */
	public static function enqueue_default_editor() {
	}

	/**
	 * Print (output) all editor scripts and default settings.
	 * For use when the editor is going to be initialized after page load.
	 *
	 * @since 1.0.0
	 */
	public static function print_default_editor_scripts() {
		$user_can_richedit = user_can_richedit();
		$settings = self::default_settings();

		$settings['toolbar1']    = 'bold,italic,bullist,numlist,link';
		$settings['wpautop']     = false;
		$settings['indent']      = true;
		$settings['elementpath'] = false;
		$settings['plugins'] = implode(
			',',
			array(
				'charmap',
				'colorpicker',
			)
		);

		$settings = self::_parse_init( $settings );
		?>
		<script type="text/javascript">
		window.wp = window.wp || {};
		window.wp.editor = window.wp.editor || {};
		window.wp.editor.getDefaultSettings = function() {
			return {
				tinymce: <?php echo $settings; ?>,
				quicktags: {
					buttons: 'strong,em,link,ul,ol,li,code'
				}
			};
		};

		<?php

		$suffix  = SCRIPT_DEBUG ? '' : '.min';
		$baseurl = self::get_baseurl();

		?>
			var tinyMCEPreInit = {
				baseURL: "<?php echo $baseurl; ?>",
				suffix: "<?php echo $suffix; ?>",
				mceInit: {},
				qtInit: {},
				load_ext: function(url,lang){var sl=tinymce.ScriptLoader;sl.markDone(url+'langs/'+lang+'.js');sl.markDone(url+'langs/'+lang+'_dlg.js');}
			};
		<?php
		?>
		</script>
		<?php

		if ( $user_can_richedit ) {
			self::print_tinymce_scripts();
		}

		/**
		 * Fires when the editor scripts are loaded for later initialization,
		 * after all scripts and settings are printed.
		 *
		 * @since 1.0.0
		 */
		self::wp_link_dialog();
	}

	public static function get_mce_locale() {
		if ( empty( self::$mce_locale ) ) {
			$context          = \Context::getContext();
			$mce_locale       = $context->language->iso_code;
			self::$mce_locale = empty( $mce_locale ) ? 'en' : strtolower( substr( $mce_locale, 0, 2 ) ); // ISO 639-1
		}

		return self::$mce_locale;
	}

	public static function get_baseurl() {
		return _PS_JS_DIR_."tiny_mce";

		$baseurl = __PS_BASE_URI__ . 'modules/crazyelements/assets/js/tiny_mce';
		return $baseurl;
	}

	/**
	 * Returns the default TinyMCE settings.
	 * Doesn't include plugins, buttons, editor selector.
	 *
	 * @global string $tinymce_version
	 *
	 * @return array
	 */
	private static function default_settings() {
		global $tinymce_version;
		$context = \Context::getContext();

		$shortcut_labels = array();

		foreach ( self::get_translation() as $name => $value ) {
			if ( is_array( $value ) ) {
				$shortcut_labels[ $name ] = $value[1];
			}
		}

		$settings = array(
			'theme'                        => 'modern',
			'skin'                         => 'lightgray',
			'language'                     => self::get_mce_locale(),
			'formats'                      => '{' .
			'alignleft: [' .
						'{selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", styles: {textAlign:"left"}},' .
						'{selector: "img,table,dl.wp-caption", classes: "alignleft"}' .
			'],' .
			'aligncenter: [' .
						'{selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", styles: {textAlign:"center"}},' .
						'{selector: "img,table,dl.wp-caption", classes: "aligncenter"}' .
			'],' .
			'alignright: [' .
						'{selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li", styles: {textAlign:"right"}},' .
						'{selector: "img,table,dl.wp-caption", classes: "alignright"}' .
			'],' .
			'strikethrough: {inline: "del"}' .
			'}',
			'relative_urls'                => false,
			'remove_script_host'           => false,
			'convert_urls'                 => false,
			'browser_spellcheck'           => true,
			'fix_list_elements'            => true,
			'entities'                     => '38,amp,60,lt,62,gt',
			'entity_encoding'              => 'raw',
			'keep_styles'                  => false,
			'cache_suffix'                 => 'wp-mce-' . $tinymce_version,
			'resize'                       => 'vertical',
			'menubar'                      => false,
			'branding'                     => false,

			// Limit the preview styles in the menu/toolbar
			'preview_styles'               => 'font-family font-size font-weight font-style text-decoration text-transform',

			'end_container_on_empty_block' => true,
			'wpeditimage_html5_captions'   => true,
			'wp_lang_attr'                 => $context->shop->name,
			'wp_keep_scroll_position'      => false,
			'wp_shortcut_labels'           => Tools::jsonEncode( $shortcut_labels ),
			'filemanager_title'=> "File manager",
			'external_plugins'=> '{"filemanager":"'. __PS_BASE_URI__ . basename(_PS_ADMIN_DIR_) . '/' . 'filemanager/plugin.min.js"}',
			'external_filemanager_path'=> __PS_BASE_URI__ . basename(_PS_ADMIN_DIR_) . '/' ."filemanager/",
		);

		$suffix  = '';
		$version = 'ver=1.0';

		// Default stylesheets
		$settings['content_css'] = '';

		return $settings;
	}



	private static function get_translation() {
		if ( empty( self::$translation ) ) {
			self::$translation = array(
				// Default TinyMCE strings
				'New document'                         => PrestaHelper::__( 'New document' ),
				'Formats'                              => PrestaHelper::__( 'Formats' ),

				'Headings'                             => PrestaHelper::__( 'Headings' ),
				'Heading 1'                            => array( PrestaHelper::__( 'Heading 1' ), 'access1' ),
				'Heading 2'                            => array( PrestaHelper::__( 'Heading 2' ), 'access2' ),
				'Heading 3'                            => array( PrestaHelper::__( 'Heading 3' ), 'access3' ),
				'Heading 4'                            => array( PrestaHelper::__( 'Heading 4' ), 'access4' ),
				'Heading 5'                            => array( PrestaHelper::__( 'Heading 5' ), 'access5' ),
				'Heading 6'                            => array( PrestaHelper::__( 'Heading 6' ), 'access6' ),

				/* translators: Block tags. */
				'Blocks'                               => PrestaHelper::__( 'Blocks' ),
				'Paragraph'                            => array( PrestaHelper::__( 'Paragraph' ), 'access7' ),
				'Blockquote'                           => array( PrestaHelper::__( 'Blockquote' ), 'accessQ' ),
				'Div'                                  => PrestaHelper::__( 'Div' ),
				'Pre'                                  => PrestaHelper::__( 'Pre' ),
				'Preformatted'                         => PrestaHelper::__( 'Preformatted' ),
				'Address'                              => PrestaHelper::__( 'Address' ),

				'Inline'                               => PrestaHelper::__( 'Inline' ),
				'Underline'                            => array( PrestaHelper::__( 'Underline' ), 'metaU' ),
				'Strikethrough'                        => array( PrestaHelper::__( 'Strikethrough' ), 'accessD' ),
				'Subscript'                            => PrestaHelper::__( 'Subscript' ),
				'Superscript'                          => PrestaHelper::__( 'Superscript' ),
				'Clear formatting'                     => PrestaHelper::__( 'Clear formatting' ),
				'Bold'                                 => array( PrestaHelper::__( 'Bold' ), 'metaB' ),
				'Italic'                               => array( PrestaHelper::__( 'Italic' ), 'metaI' ),
				'Code'                                 => array( PrestaHelper::__( 'Code' ), 'accessX' ),
				'Source code'                          => PrestaHelper::__( 'Source code' ),
				'Font Family'                          => PrestaHelper::__( 'Font Family' ),
				'Font Sizes'                           => PrestaHelper::__( 'Font Sizes' ),

				'Align center'                         => array( PrestaHelper::__( 'Align center' ), 'accessC' ),
				'Align right'                          => array( PrestaHelper::__( 'Align right' ), 'accessR' ),
				'Align left'                           => array( PrestaHelper::__( 'Align left' ), 'accessL' ),
				'Justify'                              => array( PrestaHelper::__( 'Justify' ), 'accessJ' ),
				'Increase indent'                      => PrestaHelper::__( 'Increase indent' ),
				'Decrease indent'                      => PrestaHelper::__( 'Decrease indent' ),

				'Cut'                                  => array( PrestaHelper::__( 'Cut' ), 'metaX' ),
				'Copy'                                 => array( PrestaHelper::__( 'Copy' ), 'metaC' ),
				'Paste'                                => array( PrestaHelper::__( 'Paste' ), 'metaV' ),
				'Select all'                           => array( PrestaHelper::__( 'Select all' ), 'metaA' ),
				'Undo'                                 => array( PrestaHelper::__( 'Undo' ), 'metaZ' ),
				'Redo'                                 => array( PrestaHelper::__( 'Redo' ), 'metaY' ),

				'Ok'                                   => PrestaHelper::__( 'OK' ),
				'Cancel'                               => PrestaHelper::__( 'Cancel' ),
				'Close'                                => PrestaHelper::__( 'Close' ),
				'Visual aids'                          => PrestaHelper::__( 'Visual aids' ),

				'Bullet list'                          => array( PrestaHelper::__( 'Bulleted list' ), 'accessU' ),
				'Numbered list'                        => array( PrestaHelper::__( 'Numbered list' ), 'accessO' ),
				'Square'                               => PrestaHelper::__( 'Square' ),
				'Default'                              => PrestaHelper::__( 'Default' ),
				'Circle'                               => PrestaHelper::__( 'Circle' ),
				'Disc'                                 => PrestaHelper::__( 'Disc' ),
				'Lower Greek'                          => PrestaHelper::__( 'Lower Greek' ),
				'Lower Alpha'                          => PrestaHelper::__( 'Lower Alpha' ),
				'Upper Alpha'                          => PrestaHelper::__( 'Upper Alpha' ),
				'Upper Roman'                          => PrestaHelper::__( 'Upper Roman' ),
				'Lower Roman'                          => PrestaHelper::__( 'Lower Roman' ),

				// Anchor plugin
				'Name'                                 => PrestaHelper::__( 'Name' ),
				'Anchor'                               => PrestaHelper::__( 'Anchor' ),
				'Anchors'                              => PrestaHelper::__( 'Anchors' ),
				'Id should start with a letter, followed only by letters, numbers, dashes, dots, colons or underscores.' =>
				PrestaHelper::__( 'Id should start with a letter, followed only by letters, numbers, dashes, dots, colons or underscores.' ),
				'Id'                                   => PrestaHelper::__( 'Id' ),

				// Fullpage plugin
				'Document properties'                  => PrestaHelper::__( 'Document properties' ),
				'Robots'                               => PrestaHelper::__( 'Robots' ),
				'Title'                                => PrestaHelper::__( 'Title' ),
				'Keywords'                             => PrestaHelper::__( 'Keywords' ),
				'Encoding'                             => PrestaHelper::__( 'Encoding' ),
				'Description'                          => PrestaHelper::__( 'Description' ),
				'Author'                               => PrestaHelper::__( 'Author' ),

				// Media, image plugins
				'Image'                                => PrestaHelper::__( 'Image' ),
				'Insert/edit image'                    => array( PrestaHelper::__( 'Insert/edit image' ), 'accessM' ),
				'General'                              => PrestaHelper::__( 'General' ),
				'Advanced'                             => PrestaHelper::__( 'Advanced' ),
				'Source'                               => PrestaHelper::__( 'Source' ),
				'Border'                               => PrestaHelper::__( 'Border' ),
				'Constrain proportions'                => PrestaHelper::__( 'Constrain proportions' ),
				'Vertical space'                       => PrestaHelper::__( 'Vertical space' ),
				'Image description'                    => PrestaHelper::__( 'Image description' ),
				'Style'                                => PrestaHelper::__( 'Style' ),
				'Dimensions'                           => PrestaHelper::__( 'Dimensions' ),
				'Insert image'                         => PrestaHelper::__( 'Insert image' ),
				'Date/time'                            => PrestaHelper::__( 'Date/time' ),
				'Insert date/time'                     => PrestaHelper::__( 'Insert date/time' ),
				'Table of Contents'                    => PrestaHelper::__( 'Table of Contents' ),
				'Insert/Edit code sample'              => PrestaHelper::__( 'Insert/edit code sample' ),
				'Language'                             => PrestaHelper::__( 'Language' ),
				'Media'                                => PrestaHelper::__( 'Media' ),
				'Insert/edit media'                    => PrestaHelper::__( 'Insert/edit media' ),
				'Poster'                               => PrestaHelper::__( 'Poster' ),
				'Alternative source'                   => PrestaHelper::__( 'Alternative source' ),
				'Paste your embed code below:'         => PrestaHelper::__( 'Paste your embed code below:' ),
				'Insert video'                         => PrestaHelper::__( 'Insert video' ),
				'Embed'                                => PrestaHelper::__( 'Embed' ),

				// Each of these have a corresponding plugin
				'Special character'                    => PrestaHelper::__( 'Special character' ),
				'Right to left'                        => PrestaHelper::__( 'Right to left' ),
				'Left to right'                        => PrestaHelper::__( 'Left to right' ),
				'Emoticons'                            => PrestaHelper::__( 'Emoticons' ),
				'Nonbreaking space'                    => PrestaHelper::__( 'Nonbreaking space' ),
				'Page break'                           => PrestaHelper::__( 'Page break' ),
				'Paste as text'                        => PrestaHelper::__( 'Paste as text' ),
				'Preview'                              => PrestaHelper::__( 'Preview' ),
				'Print'                                => PrestaHelper::__( 'Print' ),
				'Save'                                 => PrestaHelper::__( 'Save' ),
				'Fullscreen'                           => PrestaHelper::__( 'Fullscreen' ),
				'Horizontal line'                      => PrestaHelper::__( 'Horizontal line' ),
				'Horizontal space'                     => PrestaHelper::__( 'Horizontal space' ),
				'Restore last draft'                   => PrestaHelper::__( 'Restore last draft' ),
				'Insert/edit link'                     => array( PrestaHelper::__( 'Insert/edit link' ), 'metaK' ),
				'Remove link'                          => array( PrestaHelper::__( 'Remove link' ), 'accessS' ),

				// Link plugin
				'Link'                                 => PrestaHelper::__( 'Link' ),
				'Insert link'                          => PrestaHelper::__( 'Insert link' ),
				'Target'                               => PrestaHelper::__( 'Target' ),
				'New window'                           => PrestaHelper::__( 'New window' ),
				'Text to display'                      => PrestaHelper::__( 'Text to display' ),
				'Url'                                  => PrestaHelper::__( 'URL' ),
				'The URL you entered seems to be an email address. Do you want to add the required mailto: prefix?' =>
				PrestaHelper::__( 'The URL you entered seems to be an email address. Do you want to add the required mailto: prefix?' ),
				'The URL you entered seems to be an external link. Do you want to add the required http:// prefix?' =>
				PrestaHelper::__( 'The URL you entered seems to be an external link. Do you want to add the required http:// prefix?' ),

				'Color'                                => PrestaHelper::__( 'Color' ),
				'Custom color'                         => PrestaHelper::__( 'Custom color' ),
				'Custom...'                            => PrestaHelper::__( 'Custom...', 'label for custom color' ), // no ellipsis
				'No color'                             => PrestaHelper::__( 'No color' ),
				'R'                                    => PrestaHelper::__( 'R', 'Short for red in RGB' ),
				'G'                                    => PrestaHelper::__( 'G', 'Short for green in RGB' ),
				'B'                                    => PrestaHelper::__( 'B', 'Short for blue in RGB' ),

				// Spelling, search/replace plugins
				'Could not find the specified string.' => PrestaHelper::__( 'Could not find the specified string.' ),
				'Replace'                              => PrestaHelper::__( 'Replace' ),
				'Next'                                 => PrestaHelper::__( 'Next' ),
				/* translators: Previous. */
				'Prev'                                 => PrestaHelper::__( 'Prev' ),
				'Whole words'                          => PrestaHelper::__( 'Whole words' ),
				'Find and replace'                     => PrestaHelper::__( 'Find and replace' ),
				'Replace with'                         => PrestaHelper::__( 'Replace with' ),
				'Find'                                 => PrestaHelper::__( 'Find' ),
				'Replace all'                          => PrestaHelper::__( 'Replace all' ),
				'Match case'                           => PrestaHelper::__( 'Match case' ),
				'Spellcheck'                           => PrestaHelper::__( 'Check Spelling' ),
				'Finish'                               => PrestaHelper::__( 'Finish', 'spellcheck' ),
				'Ignore all'                           => PrestaHelper::__( 'Ignore all', 'spellcheck' ),
				'Ignore'                               => PrestaHelper::__( 'Ignore', 'spellcheck' ),
				'Add to Dictionary'                    => PrestaHelper::__( 'Add to Dictionary' ),

				// TinyMCE tables
				'Insert table'                         => PrestaHelper::__( 'Insert table' ),
				'Delete table'                         => PrestaHelper::__( 'Delete table' ),
				'Table properties'                     => PrestaHelper::__( 'Table properties' ),
				'Row properties'                       => PrestaHelper::__( 'Table row properties' ),
				'Cell properties'                      => PrestaHelper::__( 'Table cell properties' ),
				'Border color'                         => PrestaHelper::__( 'Border color' ),

				'Row'                                  => PrestaHelper::__( 'Row' ),
				'Rows'                                 => PrestaHelper::__( 'Rows' ),
				'Column'                               => PrestaHelper::__( 'Column' ),
				'Cols'                                 => PrestaHelper::__( 'Cols' ),
				'Cell'                                 => PrestaHelper::__( 'Cell' ),
				'Header cell'                          => PrestaHelper::__( 'Header cell' ),
				'Header'                               => PrestaHelper::__( 'Header' ),
				'Body'                                 => PrestaHelper::__( 'Body' ),
				'Footer'                               => PrestaHelper::__( 'Footer' ),

				'Insert row before'                    => PrestaHelper::__( 'Insert row before' ),
				'Insert row after'                     => PrestaHelper::__( 'Insert row after' ),
				'Insert column before'                 => PrestaHelper::__( 'Insert column before' ),
				'Insert column after'                  => PrestaHelper::__( 'Insert column after' ),
				'Paste row before'                     => PrestaHelper::__( 'Paste table row before' ),
				'Paste row after'                      => PrestaHelper::__( 'Paste table row after' ),
				'Delete row'                           => PrestaHelper::__( 'Delete row' ),
				'Delete column'                        => PrestaHelper::__( 'Delete column' ),
				'Cut row'                              => PrestaHelper::__( 'Cut table row' ),
				'Copy row'                             => PrestaHelper::__( 'Copy table row' ),
				'Merge cells'                          => PrestaHelper::__( 'Merge table cells' ),
				'Split cell'                           => PrestaHelper::__( 'Split table cell' ),

				'Height'                               => PrestaHelper::__( 'Height' ),
				'Width'                                => PrestaHelper::__( 'Width' ),
				'Caption'                              => PrestaHelper::__( 'Caption' ),
				'Alignment'                            => PrestaHelper::__( 'Alignment' ),
				'H Align'                              => PrestaHelper::__( 'H Align', 'horizontal table cell alignment' ),
				'Left'                                 => PrestaHelper::__( 'Left' ),
				'Center'                               => PrestaHelper::__( 'Center' ),
				'Right'                                => PrestaHelper::__( 'Right' ),
				'None'                                 => PrestaHelper::__( 'None', 'table cell alignment attribute' ),
				'V Align'                              => PrestaHelper::__( 'V Align', 'vertical table cell alignment' ),
				'Top'                                  => PrestaHelper::__( 'Top' ),
				'Middle'                               => PrestaHelper::__( 'Middle' ),
				'Bottom'                               => PrestaHelper::__( 'Bottom' ),

				'Row group'                            => PrestaHelper::__( 'Row group' ),
				'Column group'                         => PrestaHelper::__( 'Column group' ),
				'Row type'                             => PrestaHelper::__( 'Row type' ),
				'Cell type'                            => PrestaHelper::__( 'Cell type' ),
				'Cell padding'                         => PrestaHelper::__( 'Cell padding' ),
				'Cell spacing'                         => PrestaHelper::__( 'Cell spacing' ),
				'Scope'                                => PrestaHelper::__( 'Scope', 'table cell scope attribute' ),

				'Insert template'                      => PrestaHelper::__( 'Insert template' ),
				'Templates'                            => PrestaHelper::__( 'Templates' ),

				'Background color'                     => PrestaHelper::__( 'Background color' ),
				'Text color'                           => PrestaHelper::__( 'Text color' ),
				'Show blocks'                          => PrestaHelper::__( 'Show blocks' ),
				'Show invisible characters'            => PrestaHelper::__( 'Show invisible characters' ),

				/* translators: Word count. */
				'Words: {0}'                           => sprintf( PrestaHelper::__( 'Words: %s' ), '{0}' ),
				'Paste is now in plain text mode. Contents will now be pasted as plain text until you toggle this option off.' =>
				PrestaHelper::__( 'Paste is now in plain text mode. Contents will now be pasted as plain text until you toggle this option off.' ) . "\n\n" .
				PrestaHelper::__( 'If you&#8217;re looking to paste rich content from Microsoft Word, try turning this option off. The editor will clean up text pasted from Word automatically.' ),
				'Rich Text Area. Press ALT-F9 for menu. Press ALT-F10 for toolbar. Press ALT-0 for help' =>
				PrestaHelper::__( 'Rich Text Area. Press Alt-Shift-H for help.' ),
				'Rich Text Area. Press Control-Option-H for help.' => PrestaHelper::__( 'Rich Text Area. Press Control-Option-H for help.' ),
				'You have unsaved changes are you sure you want to navigate away?' =>
				PrestaHelper::__( 'The changes you made will be lost if you navigate away from this page.' ),
				'Your browser doesn\'t support direct access to the clipboard. Please use the Ctrl+X/C/V keyboard shortcuts instead.' =>
				PrestaHelper::__( 'Your browser does not support direct access to the clipboard. Please use keyboard shortcuts or your browser&#8217;s edit menu instead.' ),

				// TinyMCE menus
				'Insert'                               => PrestaHelper::__( 'Insert' ),
				'File'                                 => PrestaHelper::__( 'File' ),
				'Edit'                                 => PrestaHelper::__( 'Edit' ),
				'Tools'                                => PrestaHelper::__( 'Tools' ),
				'View'                                 => PrestaHelper::__( 'View' ),
				'Table'                                => PrestaHelper::__( 'Table' ),
				'Format'                               => PrestaHelper::__( 'Format' ),

				'Toolbar Toggle'                       => array( PrestaHelper::__( 'Toolbar Toggle' ), 'accessZ' ),
				'Insert Read More tag'                 => array( PrestaHelper::__( 'Insert Read More tag' ), 'accessT' ),
				'Insert Page Break tag'                => array( PrestaHelper::__( 'Insert Page Break tag' ), 'accessP' ),
				'Read more...'                         => PrestaHelper::__( 'Read more...' ), // Title on the placeholder inside the editor (no ellipsis)
				'Distraction-free writing mode'        => array( PrestaHelper::__( 'Distraction-free writing mode' ), 'accessW' ),
				'No alignment'                         => PrestaHelper::__( 'No alignment' ), // Tooltip for the 'alignnone' button in the image toolbar
				'Remove'                               => PrestaHelper::__( 'Remove' ), // Tooltip for the 'remove' button in the image toolbar
				'Edit|button'                          => PrestaHelper::__( 'Edit' ), // Tooltip for the 'edit' button in the image toolbar
				'Paste URL or type to search'          => PrestaHelper::__( 'Paste URL or type to search' ), // Placeholder for the inline link dialog
				'Apply'                                => PrestaHelper::__( 'Apply' ), // Tooltip for the 'apply' button in the inline link dialog
				'Link options'                         => PrestaHelper::__( 'Link options' ), // Tooltip for the 'link options' button in the inline link dialog
				'Visual'                               => PrestaHelper::__( 'Visual', 'Name for the Visual editor tab' ), // Editor switch tab label
				'Text'                                 => PrestaHelper::__( 'Text', 'Name for the Text editor tab (formerly HTML)' ), // Editor switch tab label
				'Add Media'                            => array( PrestaHelper::__( 'Add Media' ), 'accessM' ), // Tooltip for the 'Add Media' button in the block editor Classic block

			// Shortcuts help modal
				'Keyboard Shortcuts'                   => array( PrestaHelper::__( 'Keyboard Shortcuts' ), 'accessH' ),
				'Classic Block Keyboard Shortcuts'     => PrestaHelper::__( 'Classic Block Keyboard Shortcuts' ),
				'Default shortcuts,'                   => PrestaHelper::__( 'Default shortcuts,' ),
				'Additional shortcuts,'                => PrestaHelper::__( 'Additional shortcuts,' ),
				'Focus shortcuts:'                     => PrestaHelper::__( 'Focus shortcuts:' ),
				'Inline toolbar (when an image, link or preview is selected)' => PrestaHelper::__( 'Inline toolbar (when an image, link or preview is selected)' ),
				'Editor menu (when enabled)'           => PrestaHelper::__( 'Editor menu (when enabled)' ),
				'Editor toolbar'                       => PrestaHelper::__( 'Editor toolbar' ),
				'Elements path'                        => PrestaHelper::__( 'Elements path' ),
				'Ctrl + Alt + letter:'                 => PrestaHelper::__( 'Ctrl + Alt + letter:' ),
				'Shift + Alt + letter:'                => PrestaHelper::__( 'Shift + Alt + letter:' ),
				'Cmd + letter:'                        => PrestaHelper::__( 'Cmd + letter:' ),
				'Ctrl + letter:'                       => PrestaHelper::__( 'Ctrl + letter:' ),
				'Letter'                               => PrestaHelper::__( 'Letter' ),
				'Action'                               => PrestaHelper::__( 'Action' ),
				'Warning: the link has been inserted but may have errors. Please test it.' => PrestaHelper::__( 'Warning: the link has been inserted but may have errors. Please test it.' ),
				'To move focus to other buttons use Tab or the arrow keys. To return focus to the editor press Escape or use one of the buttons.' =>
				PrestaHelper::__( 'To move focus to other buttons use Tab or the arrow keys. To return focus to the editor press Escape or use one of the buttons.' ),
				'When starting a new paragraph with one of these formatting shortcuts followed by a space, the formatting will be applied automatically. Press Backspace or Escape to undo.' =>
				PrestaHelper::__( 'When starting a new paragraph with one of these formatting shortcuts followed by a space, the formatting will be applied automatically. Press Backspace or Escape to undo.' ),
				'The following formatting shortcuts are replaced when pressing Enter. Press Escape or the Undo button to undo.' =>
				PrestaHelper::__( 'The following formatting shortcuts are replaced when pressing Enter. Press Escape or the Undo button to undo.' ),
				'The next group of formatting shortcuts are applied as you type or when you insert them around plain text in the same paragraph. Press Escape or the Undo button to undo.' =>
				PrestaHelper::__( 'The next group of formatting shortcuts are applied as you type or when you insert them around plain text in the same paragraph. Press Escape or the Undo button to undo.' ),
			);
		}

		return self::$translation;
	}

	/**
	 * @param  string $mce_locale The locale used for the editor.
	 * @param  bool   $json_only  optional Whether to include the JavaScript calls to tinymce.addI18n() and tinymce.ScriptLoader.markDone().
	 * @return string Translation object, JSON encoded.
	 */
	public static function wp_mce_translation( $mce_locale = '', $json_only = false ) {
		if ( ! $mce_locale ) {
			$mce_locale = self::get_mce_locale();
		}

		$mce_translation = self::get_translation();

		foreach ( $mce_translation as $name => $value ) {
			if ( is_array( $value ) ) {
				$mce_translation[ $name ] = $value[0];
			}
		}

		/**
		 * Filters translated strings prepared for TinyMCE.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $mce_translation Key/value pairs of strings.
		 * @param string $mce_locale      Locale.
		 */

		foreach ( $mce_translation as $key => $value ) {
			// Remove strings that are not translated.
			if ( $key === $value ) {
				unset( $mce_translation[ $key ] );
				continue;
			}

			if ( false !== strpos( $value, '&' ) ) {
				$mce_translation[ $key ] = html_entity_decode( $value, ENT_QUOTES, 'UTF-8' );
			}
		}
		// Set direction
		if ( \Context::getContext()->language->is_rtl ) {
			$mce_translation['_dir'] = 'rtl';
		}

		if ( $json_only ) {
			return Tools::jsonEncode( $mce_translation );
		}

		$baseurl = self::get_baseurl();

		return "tinymce.addI18n( '$mce_locale', " . Tools::jsonEncode( $mce_translation ) . ");\n" .
		"tinymce.ScriptLoader.markDone( '" . $baseurl . "langs/$mce_locale.js' );\n";
	}

	/**
	 * Force uncompressed TinyMCE when a custom theme has been defined.
	 *
	 * The compressed TinyMCE file cannot deal with custom themes, so this makes
	 * sure that we use the uncompressed TinyMCE file if a theme is defined.
	 * Even if we are on a production environment.
	 */
	public static function force_uncompressed_tinymce() {
		$has_custom_theme = false;
		foreach ( self::$mce_settings as $init ) {
			if ( ! empty( $init['theme_url'] ) ) {
				$has_custom_theme = true;
				break;
			}
		}
		if ( ! $has_custom_theme ) {
			return;
		}
	}

	/**
	 * Print (output) the main TinyMCE scripts.
	 *
	 * @since 1.0.0
	 *
	 * @global string $tinymce_version
	 * @global bool   $concatenate_scripts
	 * @global bool   $compress_scripts
	 */
	public static function print_tinymce_scripts() {
		global $concatenate_scripts;

		if ( self::$tinymce_scripts_printed ) {
			return;
		}
		self::$tinymce_scripts_printed = true;

	}

	/**
	 * Print (output) the TinyMCE configuration and initialization scripts.
	 *
	 * @global string $tinymce_version
	 */
	public static function editor_js() {
		global $tinymce_version;

		$tmce_on = ! empty( self::$mce_settings );
		$mceInit = '';
		$qtInit  = '';

		if ( $tmce_on ) {
			foreach ( self::$mce_settings as $editor_id => $init ) {
				$options  = self::_parse_init( $init );
				$mceInit .= "'$editor_id':{$options},";
			}
			$mceInit = '{' . trim( $mceInit, ',' ) . '}';
		} else {
			$mceInit = '{}';
		}

		if ( ! empty( self::$qt_settings ) ) {
			foreach ( self::$qt_settings as $editor_id => $init ) {
				$options = self::_parse_init( $init );
				$qtInit .= "'$editor_id':{$options},";
			}
			$qtInit = '{' . trim( $qtInit, ',' ) . '}';
		} else {
			$qtInit = '{}';
		}

		$ref = array(
			'plugins'  => implode( ',', self::$plugins ),
			'theme'    => 'modern',
			'language' => self::$mce_locale,
		);

		$suffix  = '.min';
		$baseurl = self::get_baseurl();
		$version = 'ver=' . $tinymce_version;

		?>

		<script type="text/javascript">
		tinyMCEPreInit = {
			baseURL: "<?php echo $baseurl; ?>",
			suffix: "<?php echo $suffix; ?>",
		<?php

		if ( self::$drag_drop_upload ) {
			echo 'dragDropUpload: true,';
		}

		?>
			mceInit: <?php echo $mceInit; ?>,
			qtInit: <?php echo $qtInit; ?>,
			ref: <?php echo self::_parse_init( $ref ); ?>,
			load_ext: function(url,lang){var sl=tinymce.ScriptLoader;sl.markDone(url+'angs/'+lang+'.js');sl.markDone(url+'langs/'+lang+'_dlg.js');}
		};
		</script>
		<?php

		if ( $tmce_on ) {
			self::print_tinymce_scripts();

			if ( self::$ext_plugins ) {
				// Load the old-format English strings to prevent unsightly labels in old style popups
				echo "<script type='text/javascript' src='{$baseurl}/langs/en.js?$version'></script>\n";
			}
		}

		/**
		 * Fires after tinymce.js is loaded, but before any TinyMCE editor
		 * instances are created.
		 *
		 * @since 1.0.0
		 *
		 * @param array $mce_settings TinyMCE settings array.
		 */
		PrestaHelper::do_action( 'wp_tiny_mce_init', self::$mce_settings );

		?>
		<script type="text/javascript">
		<?php

		if ( self::$ext_plugins ) {
			echo self::$ext_plugins . "\n";
		}

		?>

		( function($) {
			var init, id, $wrap;

			if ( typeof tinymce !== 'undefined' ) {
				if ( tinymce.Env.ie && tinymce.Env.ie < 11 ) {
					tinymce.jQuery( '.wp-editor-wrap ' ).removeClass( 'tmce-active' ).addClass( 'html-active' );
					return;
				}
				for ( id in tinyMCEPreInit.mceInit ) {
					init = tinyMCEPreInit.mceInit[id];
					$wrap = jQuery( '#wp-' + id + '-wrap' );

					if ( ( $wrap.hasClass( 'tmce-active' ) || ! tinyMCEPreInit.qtInit.hasOwnProperty( id ) ) && ! init.wp_skip_init ) {
						tinymce.init( init );

						if ( ! window.wpActiveEditor ) {
							window.wpActiveEditor = id;
						}
					}
				}
			}

			if ( typeof quicktags !== 'undefined' ) {
				for ( id in tinyMCEPreInit.qtInit ) {
					quicktags( tinyMCEPreInit.qtInit[id] );

					if ( ! window.wpActiveEditor ) {
						window.wpActiveEditor = id;
					}
				}
			}
		}(jQuery));
		</script>
		<?php

		if ( in_array( 'wplink', self::$plugins, true ) || in_array( 'link', self::$qt_buttons, true ) ) {
			self::wp_link_dialog();
		}

		/**
		 * Fires after any core TinyMCE editor instances are created.
		 *
		 * @since 1.0.0
		 *
		 * @param array $mce_settings TinyMCE settings array.
		 */
		PrestaHelper::do_action( 'after_wp_tiny_mce', self::$mce_settings );
	}

	/**
	 * Outputs the HTML for distraction-free writing mode.
	 *
	 * @since      1.0.0
	 */
	public static function wp_fullscreen_html() {
		_deprecated_function( __FUNCTION__, '4.3.0' );
	}

	/**
	 * Performs post queries for internal linking.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $args Optional. Accepts 'pagenum' and 's' (search) arguments.
	 * @return false|array Results.
	 */
	public static function wp_link_query( $args = array() ) {
		$pts      = get_post_types( array( 'public' => true ), 'objects' );
		$pt_names = array_keys( $pts );

		$query = array(
			'post_type'              => $pt_names,
			'suppress_filters'       => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'post_status'            => 'publish',
			'posts_per_page'         => 20,
		);

		$args['pagenum'] = isset( $args['pagenum'] ) ? absint( $args['pagenum'] ) : 1;

		if ( isset( $args['s'] ) ) {
			$query['s'] = $args['s'];
		}

		$query['offset'] = $args['pagenum'] > 1 ? $query['posts_per_page'] * ( $args['pagenum'] - 1 ) : 0;

		/**
		 * Filters the link query arguments.
		 *
		 * Allows modification of the link query arguments before querying.
		 *
		 * @since 1.0.0
		 *
		 * @param array $query An array of WP_Query arguments.
		 */
		$query = PrestaHelper::apply_filters( 'wp_link_query_args', $query );

		// Do main query.
		$get_posts = new WP_Query();
		$posts     = $get_posts->query( $query );

		// Build results.
		$results = array();
		foreach ( $posts as $post ) {
			if ( 'post' == $post->post_type ) {
				$info = mysql2date( PrestaHelper::__( 'Y/m/d' ), $post->post_date );
			} else {
				$info = $pts[ $post->post_type ]->labels->singular_name;
			}

			$results[] = array(
				'ID'        => $post->ID,
				'title'     => trim( esc_html( strip_tags( get_the_title( $post ) ) ) ),
				'permalink' => get_permalink( $post->ID ),
				'info'      => $info,
			);
		}

		/**
		 * Filters the link query results.
		 *
		 * Allows modification of the returned link query results.
		 *
		 * @since 1.0.0
		 *
		 * @param array $results {
		 *     An associative array of query results.
		 *
		 *     @type  array {
		 *         @type  int    $ID        Post ID.
		 *         @type  string $title     The trimmed, escaped post title.
		 *         @type  string $permalink Post permalink.
		 *         @type  string $info      A 'Y/m/d'-formatted date for 'post' post type,
		 *                                 the 'singular_name' post type label otherwise.
		 *     }
		 * }
		 * @param array $query  An array of WP_Query arguments.
		 */
		$results = PrestaHelper::apply_filters( 'wp_link_query', $results, $query );

		return ! empty( $results ) ? $results : false;
	}

	/**
	 * Dialog for internal linking.
	 *
	 * @since 1.0.0
	 */
	public static function wp_link_dialog() {
		// Run once
		if ( self::$link_dialog_printed ) {
			return;
		}

		self::$link_dialog_printed = true;

		// display: none is required here, see #WP27605
		?>
		<div id="wp-link-backdrop" style="display: none"></div>
		<div id="wp-link-wrap" class="wp-core-ui" style="display: none" role="dialog" aria-labelledby="link-modal-title">
		<form id="wp-link" tabindex="-1">
	
		<h1 id="link-modal-title"><?php PrestaHelper::_e( 'Insert/edit link' ); ?></h1>
		<button type="button" id="wp-link-close"><span class="screen-reader-text"><?php PrestaHelper::_e( 'Close' ); ?></span></button>
		<div id="link-selector">
			<div id="link-options">
				<p class="howto" id="wplink-enter-url"><?php PrestaHelper::_e( 'Enter the destination URL' ); ?></p>
				<div>
					<label><span><?php PrestaHelper::_e( 'URL' ); ?></span>
					<input id="wp-link-url" type="text" aria-describedby="wplink-enter-url" /></label>
				</div>
				<div class="wp-link-text-field">
					<label><span><?php PrestaHelper::_e( 'Link Text' ); ?></span>
					<input id="wp-link-text" type="text" /></label>
				</div>
				<div class="link-target">
					<label><span></span>
					<input type="checkbox" id="wp-link-target" /> <?php PrestaHelper::_e( 'Open link in a new tab' ); ?></label>
				</div>
			</div>
			<p class="howto" id="wplink-link-existing-content"><?php PrestaHelper::_e( 'Or link to existing content' ); ?></p>
			<div id="search-panel">
				<div class="link-search-wrapper">
					<label>
						<span class="search-label"><?php PrestaHelper::_e( 'Search' ); ?></span>
						<input type="search" id="wp-link-search" class="link-search-field" autocomplete="off" aria-describedby="wplink-link-existing-content" />
						<span class="spinner"></span>
					</label>
				</div>
				<div id="search-results" class="query-results" tabindex="0">
					<ul></ul>
					<div class="river-waiting">
						<span class="spinner"></span>
					</div>
				</div>
				<div id="most-recent-results" class="query-results" tabindex="0">
					<div class="query-notice" id="query-notice-message">
						<em class="query-notice-default"><?php PrestaHelper::_e( 'No search term specified. Showing recent items.' ); ?></em>
						<em class="query-notice-hint screen-reader-text"><?php PrestaHelper::_e( 'Search or use up and down arrow keys to select an item.' ); ?></em>
					</div>
					<ul></ul>
					<div class="river-waiting">
						<span class="spinner"></span>
					</div>
				</div>
			</div>
		</div>
		<div class="submitbox">
			<div id="wp-link-cancel">
				<button type="button" class="button"><?php PrestaHelper::_e( 'Cancel' ); ?></button>
			</div>
			<div id="wp-link-update">
				<input type="submit" value="<?php PrestaHelper::__( 'Add Link' ); ?>" class="button button-primary" id="wp-link-submit" name="wp-link-submit">
			</div>
		</div>
		</form>
		</div>
		<?php
	}
}
