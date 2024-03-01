<?php

namespace MABEL_WCBB\Core\Common\Managers
{
	/**
	 * This class holds the settings as saved from the admin pages.
	 * Class Settings_Manager
	 * @package MABEL_WCBB\Core
	 */
	class Settings_Manager
	{
		/**
		 * @var array contains all plugin settings from the database.
		 */
		private static $settings = null;

		/**
		 * @var array contains all plugin default settings.
		 */
		private static $defaults;

		public static function init(array $defaults = array())
		{
			self::$defaults = $defaults;
		}

		/**
		 * Function to lazy fetch the settings. Only when they're needed.
		 */
		private static function fetch_settings()
		{
			if(self::$settings === null)
			{
				$options = get_option( Config_Manager::$settings_key );
				self::$settings = ( $options != '' && $options !== false ) ? (array) $options : array();
				self::sanitize();
			}
		}

		public static function has_setting($key)
		{
			self::fetch_settings();
			return isset(self::$settings[$key]);
		}

		public static function set_setting($key, $value)
		{
			self::fetch_settings();
			self::$settings[$key] = $value;
		}

		public static function save()
		{
			self::fetch_settings();
			update_option(Config_Manager::$settings_key, self::$settings);
		}

		public static function get_setting($key, $fallback_to_default = true)
		{
			self::fetch_settings();

			$setting = isset( self::$settings[$key] ) ? self::$settings[$key] : null;

			if($setting !== null || $fallback_to_default === false)
				return $setting;

			if(!is_array(self::$defaults))
				return null;

			if(isset(self::$defaults[$key]))
				return self::$defaults[$key];

			return null;
		}

		/**
		 * Translates the default setting if no setting was found
		 * @param $key
		 *
		 * @return null|string
		 */
		public static function get_translated_setting($key)
		{
			self::fetch_settings();

			$setting = isset( self::$settings[$key] ) ? self::$settings[$key] : null;

			if($setting != null)
				return $setting;

			if(!is_array(self::$defaults))
				return null;

			if(isset(self::$defaults[$key]))
				return __(self::$defaults[$key], Config_Manager::$slug);

			return null;
		}

		private static function sanitize()
		{
			foreach(self::$settings as $k => $v){
				if($v === 'true'){
					self::$settings[$k] = true;
					continue;
				}
				if($v === 'false'){
					self::$settings[$k] = false;
					continue;
				}
			}
		}
	}
}