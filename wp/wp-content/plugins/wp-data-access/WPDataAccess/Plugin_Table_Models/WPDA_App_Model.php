<?php

namespace WPDataAccess\Plugin_Table_Models {

	use WPDataAccess\WPDA;

	class WPDA_App_Model extends WPDA_Plugin_Table_Base_Model {

		const BASE_TABLE_NAME = 'wpda_app';

		public static function get_by_id( $app_id ) {

			global $wpdb;
			$dataset = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM `%1s` WHERE app_id = %d', // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders
					array(
						WPDA::remove_backticks( self::get_base_table_name() ),
						$app_id,
					)
				), // db call ok; no-cache ok.
				'ARRAY_A'
			); // phpcs:ignore Standard.Category.SniffName.ErrorCode

			return 1 === $wpdb->num_rows ? $dataset : false;

		}

		public static function get_by_name( $app_name ) {

			global $wpdb;
			$dataset = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM `%1s` WHERE app_name = %s', // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders
					array(
						WPDA::remove_backticks( self::get_base_table_name() ),
						$app_name,
					)
				), // db call ok; no-cache ok.
				'ARRAY_A'
			); // phpcs:ignore Standard.Category.SniffName.ErrorCode

			return 1 === $wpdb->num_rows ? $dataset : false;

		}

		public static function list() {

			global $wpdb;
			return $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM `%1s` ORDER BY app_name', // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders
					array(
						WPDA::remove_backticks( self::get_base_table_name() ),
					)
				), // db call ok; no-cache ok.
				'ARRAY_A'
			); // phpcs:ignore Standard.Category.SniffName.ErrorCode

		}

		public static function add_to_dashboard_menu() {

			global $wpdb;
			return $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM `%1s` WHERE `app_add_to_menu` = 1 ORDER BY app_name', // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders
					array(
						WPDA::remove_backticks( self::get_base_table_name() ),
					)
				), // db call ok; no-cache ok.
				'ARRAY_A'
			); // phpcs:ignore Standard.Category.SniffName.ErrorCode

		}

		public static function create(
			$app_name,
			$app_title,
			$app_type,
			$app_settings
		) {

			global $wpdb;
			if ( 1 === $wpdb->insert(
					static::get_base_table_name(),
					array(
						'app_name'     => $app_name,
						'app_title'    => $app_title,
						'app_type'     => $app_type,
						'app_settings' => $app_settings,
					)
				)
			) {
				return array(
					'app_id' => $wpdb->insert_id,
					'msg'    => '',
				);
			} else {
				return array(
					'app_id' => false,
					'msg'    => $wpdb->last_error,
				);
			}
		}

		public static function delete( $app_id ) {

			global $wpdb;
			return $wpdb->delete(
				static::get_base_table_name(),
				array(
					'app_id' => $app_id,
				)
			);

		}
		
		public static function update(
			$app_id,
			$app_name,
			$app_title,
			$app_type,
			$app_settings,
			$app_add_to_menu
		) {

			global $wpdb;
			$wpdb->update(
				static::get_base_table_name(),
				array(
					'app_name'        => $app_name,
					'app_title'       => $app_title,
					'app_type'        => $app_type,
					'app_settings'    => $app_settings,
					'app_add_to_menu' => $app_add_to_menu,
				),
				array(
					'app_id' => $app_id,
				)
			);

			return $wpdb->last_error;

		}

		public static function update_theme(
			$app_id,
			$app_theme
		) {

			global $wpdb;
			$wpdb->update(
				static::get_base_table_name(),
				array(
					'app_theme' => $app_theme,
				),
				array(
					'app_id' => $app_id,
				)
			);

			return $wpdb->last_error;

		}

	}

}