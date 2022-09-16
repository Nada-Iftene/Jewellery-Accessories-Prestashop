<?php
namespace CrazyElements\Core\Debug\Classes;

class Theme_Missing extends Inspection_Base {

	public function run() {
		$theme = wp_get_theme();
		return $theme->exists();
	}

	public function get_name() {
		return 'theme-missing';
	}

	public function get_message() {
		return PrestaHelper::__( 'Some of your theme files are missing.', 'elementor' );
	}

	public function get_help_doc_url() {
		return 'https://classydevs.com/docs/crazy-elements/';
	}
}
