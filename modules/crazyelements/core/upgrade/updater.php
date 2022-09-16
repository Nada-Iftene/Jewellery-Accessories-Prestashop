<?php

namespace CrazyElements\Core\Upgrade;

use CrazyElements\Core\Base\Background_Task;

defined( '_PS_VERSION_' ) || exit;

class Updater extends Background_Task {

	protected function format_callback_log( $item ) {
		return $this->manager->get_plugin_label() . '/Upgrades - ' . $item['callback'][1];
	}
}
