<?php
namespace CrazyElements;

use CrazyElements\Modules\DynamicTags\Module as TagsModule;

use CrazyElements\PrestaHelper; if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Audio extends Widget_Base {

	/**
	 * Current instance.
	 *
	 * @access protected
	 *
	 * @var array
	 */
	protected $_current_instance = [];

	/**
	 * Get widget name.
	 *
	 * Retrieve audio widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'audio';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve audio widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return PrestaHelper::__( 'SoundCloud', 'elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve audio widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'ceicon-headphones';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'audio', 'player', 'soundcloud', 'embed' ];
	}

	/**
	 * Register audio widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_audio',
			[
				'label' => PrestaHelper::__( 'SoundCloud', 'elementor' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => PrestaHelper::__( 'Link', 'elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
					'categories' => [
					],
				],
				'default' => [
					'url' => 'https://soundcloud.com/shchxango/john-coltrane-1963-my-favorite',
				],
				'show_external' => false,
			]
		);

		$this->add_control(
			'sc_width',
			[
				'label' => PrestaHelper::__( 'Width', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => PrestaHelper::__( 'Add Width.', 'elementor' ),
				'label_block' => false,
			]
		);

		$this->add_control(
			'sc_height',
			[
				'label' => PrestaHelper::__( 'Height', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => PrestaHelper::__( 'Add Height.', 'elementor' ),
				'label_block' => false,
			]
		);

		$this->add_control(
			'visual',
			[
				'label' => PrestaHelper::__( 'Visual Player', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'yes' => PrestaHelper::__( 'Yes', 'elementor' ),
					'no' => PrestaHelper::__( 'No', 'elementor' ),
				],
			]
		);
		$this->add_control(
			'sc_options',
			[
				'label' => PrestaHelper::__( 'Additional Options', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'sc_auto_play',
			[
				'label' => PrestaHelper::__( 'Autoplay', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);
		$this->add_control(
			'sc_buying',
			[
				'label' => PrestaHelper::__( 'Buy Button', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => PrestaHelper::__( 'Hide', 'elementor' ),
				'label_on' => PrestaHelper::__( 'Show', 'elementor' ),
				'default' => 'yes',
			]
		);
		$this->add_control(
			'sc_liking',
			[
				'label' => PrestaHelper::__( 'Like Button', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => PrestaHelper::__( 'Hide', 'elementor' ),
				'label_on' => PrestaHelper::__( 'Show', 'elementor' ),
				'default' => 'yes',
			]
		);
		$this->add_control(
			'sc_download',
			[
				'label' => PrestaHelper::__( 'Download Button', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => PrestaHelper::__( 'Hide', 'elementor' ),
				'label_on' => PrestaHelper::__( 'Show', 'elementor' ),
				'default' => 'yes',
			]
		);
		$this->add_control(
			'sc_show_artwork',
			[
				'label' => PrestaHelper::__( 'Artwork', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => PrestaHelper::__( 'Hide', 'elementor' ),
				'label_on' => PrestaHelper::__( 'Show', 'elementor' ),
				'default' => 'yes',
				'condition' => [
					'visual' => 'no',
				],
			]
		);
		$this->add_control(
			'sc_sharing',
			[
				'label' => PrestaHelper::__( 'Share Button', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => PrestaHelper::__( 'Hide', 'elementor' ),
				'label_on' => PrestaHelper::__( 'Show', 'elementor' ),
				'default' => 'yes',
			]
		);
		$this->add_control(
			'sc_show_comments',
			[
				'label' => PrestaHelper::__( 'Comments', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => PrestaHelper::__( 'Hide', 'elementor' ),
				'label_on' => PrestaHelper::__( 'Show', 'elementor' ),
				'default' => 'yes',
			]
		);
		$this->add_control(
			'sc_show_playcount',
			[
				'label' => PrestaHelper::__( 'Play Counts', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => PrestaHelper::__( 'Hide', 'elementor' ),
				'label_on' => PrestaHelper::__( 'Show', 'elementor' ),
				'default' => 'yes',
			]
		);
		$this->add_control(
			'sc_show_user',
			[
				'label' => PrestaHelper::__( 'Username', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => PrestaHelper::__( 'Hide', 'elementor' ),
				'label_on' => PrestaHelper::__( 'Show', 'elementor' ),
				'default' => 'yes',
			]
		);
		$this->add_control(
			'sc_color',
			[
				'label' => PrestaHelper::__( 'Controls Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
			]
		);
		$this->add_control(
			'view',
			[
				'label' => PrestaHelper::__( 'View', 'elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'soundcloud',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Render audio widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['link'] ) ) {
			return;
		}
		
		$soundcloudsettingarray=array('visual' =>(isset($settings['visual']) && ($settings['visual']=='' || $settings['visual']=='no'))?'false':'true',
		    'auto_play' => (isset($settings['sc_auto_play']) && ($settings['sc_auto_play']=='' || $settings['sc_auto_play']=='no'))?'false':'true',
		    'buying' => (isset($settings['sc_buying']) && ($settings['sc_buying']=='' || $settings['sc_buying']=='no'))?'false':'true',
		    'liking' => (isset($settings['sc_liking']) && ($settings['sc_liking']=='' || $settings['sc_liking']=='no'))?'false':'true',
		    'download' => (isset($settings['sc_download']) && ($settings['sc_download']=='' || $settings['sc_download']=='no'))?'false':'true',
		    'show_artwork' => (isset($settings['sc_show_artwork']) && ($settings['sc_show_artwork']=='' || $settings['sc_show_artwork']=='no'))?'false':'true',
		    'sharing' => (isset($settings['sc_sharing']) && ($settings['sc_sharing']=='' || $settings['sc_sharing']=='no'))?'false':'true',
		    'show_comments' => (isset($settings['sc_show_comments']) && ($settings['sc_show_comments']=='' || $settings['sc_show_comments']=='no'))?'false':'true',
		    'show_playcount' => (isset($settings['sc_show_playcount']) && ($settings['sc_show_playcount']=='' || $settings['sc_show_playcount']=='no'))?'false':'true',
		    'show_user' => (isset($settings['sc_show_user']) && ($settings['sc_show_user']=='' || $settings['sc_show_user']=='no'))?'false':'true',
		    'color' => (isset($settings['sc_color']) && ($settings['sc_color']=='' || $settings['sc_color']=='no'))?'':$settings['sc_color'],
		);
		$style='';
		if($settings['sc_width']!='' ){
			$style.='width: '.$settings['sc_width'].'px;';
		}else{
			$style.='';
		}

		if( $settings['sc_height']!='' ){
			$style.='height: '.$settings['sc_height'].'px;';
		}else{
			$style.='';
		}

     	$queryparam= http_build_query($soundcloudsettingarray); 

		$this->_current_instance = $settings;
		?>
		<div class="elementor-soundcloud-wrapper">
			<iframe frameborder="no" allow="autoplay" style="<?php echo $style ?>"  width="<?php echo  $settings['sc_width'] ?>" height="<?php echo  $settings['sc_height'] ?>"
			  src="https://w.soundcloud.com/player/?url=<?php echo  $settings['link']['url'] ?>&<?php echo $queryparam ?>">
			</iframe>
		</div>
		<?php

	}

	/**
	 * Filter audio widget oEmbed results.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $html The HTML returned by the oEmbed provider.
	 *
	 * @return string Filtered audio widget oEmbed HTML.
	 */
	public function filter_oembed_result( $html ) {
		$param_keys = [
			'auto_play',
			'buying',
			'liking',
			'download',
			'sharing',
			'show_comments',
			'show_playcount',
			'show_user',
			'show_artwork',
		];

		$params = [];

		foreach ( $param_keys as $param_key ) {
			$params[ $param_key ] = 'yes' === $this->_current_instance[ 'sc_' . $param_key ] ? 'true' : 'false';
		}

		$params['color'] = str_replace( '#', '', $this->_current_instance['sc_color'] );

		preg_match( '/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $html, $matches );

		$url = esc_url( add_query_arg( $params, $matches[1] ) );

		$visual = 'yes' === $this->_current_instance['visual'] ? 'true' : 'false';

		$html = str_replace( [ $matches[1], 'visual=true' ], [ $url, 'visual=' . $visual ], $html );

		if ( 'false' === $visual ) {
			$html = str_replace( 'height="400"', 'height="200"', $html );
		}

		return $html;
	}

	/**
	 * Render audio widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {}
}
