<?php

namespace MABEL_WCBB\Core\Common\Managers
{

	/**
	 * Holds basic plugin info such as the slug & version.
	 * Is accessible through the whole plugin.
	 * Class Config_Manager
	 * @package MABEL_WCBB\Core
	 */
	class Config_Manager
	{

		public static $version;

		public static $url;

		public static $dir;

		public static $slug;

		public static $plugin_base;

		public static $settings_key;

		public static $name;

		public static function init($dir, $url, $plugin_base, $version, $settings_key, $name)
		{
			self::$version = $version;
			self::$url = $url;
			self::$dir = $dir;
			self::$plugin_base = $plugin_base;
			self::$slug = trim(dirname($plugin_base) ,'/');
			self::$settings_key = $settings_key;
			self::$name = $name;
		}

	}

}