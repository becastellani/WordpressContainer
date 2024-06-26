<?php

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package WPDataAccess\Data_Publisher
 */
namespace WPDataAccess\Data_Publisher;

use WPDataAccess\Data_Dictionary\WPDA_Dictionary_Exist;
use WPDataAccess\Data_Dictionary\WPDA_Dictionary_Lists;
use WPDataAccess\Data_Tables\WPDA_Data_Tables;
use WPDataAccess\List_Table\WPDA_List_Table;
use WPDataAccess\Plugin_Table_Models\WPDA_Publisher_Model;
use WPDataAccess\Utilities\WPDA_Message_Box;
use WPDataAccess\WPDA;
/**
 * Class WPDA_Publisher_List_Table extends WPDA_List_Table
 *
 * List table to support Data Tables.
 *
 * @author  Peter Schulz
 * @since   2.0.15
 */
class WPDA_Publisher_List_Table extends WPDA_List_Table {
    public function __construct( $args = array() ) {
        $args['column_headers'] = self::column_headers_labels();
        $args['title'] = 'Data Tables';
        $args['subtitle'] = '';
        parent::__construct( $args );
        WPDA_Data_Tables::enqueue_styles_and_script();
    }

    public function get_columns() {
        if ( is_array( $this->wpda_cached_columns ) ) {
            return $this->wpda_cached_columns;
        }
        $columns = parent::get_columns();
        // Remove data source column.
        unset($columns['pub_data_source']);
        // Add data source column after schema name.
        $columns = WPDA::array_insert_after( $columns, 'pub_schema_name', array(
            'pub_data_source' => __( 'Data Source', 'wp-data-access' ),
        ) );
        return $columns;
    }

    /**
     * Overwrite method column_default
     *
     * Column pub_responsive should return 'Flat' or 'Responsive'.
     */
    public function column_default( $item, $column_name ) {
        if ( 'pub_id' === $column_name ) {
            // Validate schema, table and column names
            $warning = WPDA::validate_names( $item['pub_schema_name'], $item['pub_table_name'] );
            if ( '' !== $warning ) {
                return $item['pub_id'] . $warning . substr( parent::column_default( $item, $column_name ), strlen( $item['pub_id'] ) );
            }
        }
        if ( 'pub_schema_name' === $column_name ) {
            if ( 'CPT' === $item['pub_data_source'] ) {
                return '';
            }
            global $wpdb;
            if ( $wpdb->dbname === $item[$column_name] ) {
                return "WordPress database ({$item[$column_name]})";
            }
        }
        if ( 'pub_data_source' === $column_name ) {
            switch ( $item[$column_name] ) {
                case 'Query':
                    // Show SQL query.
                    return '<div>' . 'Custom query ' . '<i class="fas fa-eye wpda_tooltip_cq" title="' . $this->render_column_content( $item, 'pub_query', false ) . '"></i>' . '</div>';
                case 'CPT':
                    // Show CPT query.
                    return 'Custom post type: ' . $this->render_column_content( $item, 'pub_cpt' );
                default:
                    // Show database table.
                    return $this->render_column_content( $item, 'pub_table_name' );
            }
        }
        if ( 'pub_table_name' === $column_name ) {
            if ( 'Query' === $item['pub_data_source'] ) {
                return '';
            }
        }
        if ( 'pub_responsive' === $column_name ) {
            if ( 'Yes' === $item[$column_name] ) {
                return 'Responsive';
            } else {
                return 'Flat';
            }
        }
        if ( 'pub_table_options_searching' === $column_name || 'pub_table_options_ordering' === $column_name || 'pub_table_options_paging' === $column_name || 'pub_table_options_serverside' === $column_name ) {
            if ( 'on' === $item[$column_name] ) {
                return 'Yes';
            } else {
                return 'No';
            }
        }
        return parent::column_default( $item, $column_name );
    }

    /**
     * Overwrites method column_default_add_action
     *
     * Add a link to show the shortcode of a data table.
     *
     * @param array  $item
     * @param string $column_name
     * @param array  $actions
     */
    public function column_default_add_action( $item, $column_name, &$actions ) {
        parent::column_default_add_action( $item, $column_name, $actions );
        // Add copy data table to actions
        $wp_nonce_action = "wpda-copy-{$this->table_name}";
        $wp_nonce = wp_create_nonce( $wp_nonce_action );
        $form_id = '_' . (self::$list_number - 1);
        $esc_attr = 'esc_attr';
        $input_fields = $this->get_key_input_fields( $item );
        $page_field = $this->page_number_item;
        $copy_form = <<<EOT
\t\t\t\t<form id='copy_form{$esc_attr( $form_id )}' method='post'
\t\t\t\t\t  action='?page={$esc_attr( $this->page )}'
\t\t\t\t>
\t\t\t\t\t{$input_fields}
\t\t\t\t\t<input type='hidden' name='table_name' value='{$esc_attr( $this->table_name )}'>
\t\t\t\t\t<input type='hidden' name='action' value='copy' />
\t\t\t\t\t<input type='hidden' name='_wpnonce' value='{$esc_attr( $wp_nonce )}'>
\t\t\t\t\t{$page_field}
\t\t\t\t</form>
EOT;
        ?>

			<script type='text/javascript'>
				jQuery("#wpda_invisible_container").append("<?php 
        echo str_replace( array("\n", "\r"), '', $copy_form );
        // phpcs:ignore WordPress.Security.EscapeOutput
        ?>");
			</script>

			<?php 
        $copy_warning = __( "Copy table options set?\\n\\'Cancel\\' to stop, \\'OK\\' to copy.", 'wp-data-access' );
        $actions['copy'] = sprintf(
            '<a href="javascript:void(0)"
									title="%s"
                                    class="edit wpda_tooltip"
                                    onclick="if (confirm(\'%s\')) jQuery(\'#%s\').submit()">
                                    <span style="white-space: nowrap">
										<i class="fas fa-copy wpda_icon_on_button"></i>
										%s
                                    </span>
                                </a>
                                ',
            __( 'Copy: new table name = old table name_ + n', 'wp-data-access' ),
            $copy_warning,
            "copy_form{$form_id}",
            __( 'Copy', 'wp-data-access' )
        );
        // Show Data Table shortcode directly from Data Tables main page
        $shortcode_enabled = 'on' === WPDA::get_option( WPDA::OPTION_PLUGIN_WPDATAACCESS_POST ) && 'on' === WPDA::get_option( WPDA::OPTION_PLUGIN_WPDATAACCESS_PAGE );
        ?>
			<div id="wpda_publication_<?php 
        echo esc_attr( $item['pub_id'] );
        ?>"
				 title="<?php 
        echo __( 'Shortcode', 'wp-data-access' );
        ?>"
				 style="display:none"
			>
				<p class="wpda_shortcode_content">
					Copy the shortcode below into your post or page to make this data table available on your website.
				</p>
				<p class="wpda_shortcode_text">
					<strong>
						[wpdataaccess pub_id="<?php 
        echo esc_attr( $item['pub_id'] );
        ?>"]
					</strong>
				</p>
				<p class="wpda_shortcode_buttons">
					<button class="button wpda_shortcode_clipboard wpda_shortcode_button"
							type="button"
							data-clipboard-text='[wpdataaccess pub_id="<?php 
        echo esc_attr( $item['pub_id'] );
        ?>"]'
							onclick="jQuery.notify('<?php 
        echo __( 'Shortcode successfully copied to clipboard!' );
        ?>','info')"
					>
						<?php 
        echo __( 'Copy', 'wp-data-access' );
        ?>
					</button>
					<button class="button button-primary wpda_shortcode_button"
							type="button"
							onclick="jQuery('.ui-dialog-content').dialog('close')"
					>
						<?php 
        echo __( 'Close', 'wp-data-access' );
        ?>
					</button>
				</p>
				<?php 
        if ( !$shortcode_enabled ) {
            ?>
					<p>
						Shortcode wpdataaccess is not enabled for all output types.
						<a href="<?php 
            echo admin_url( 'options-general.php' );
            // phpcs:ignore WordPress.Security.EscapeOutput
            ?>?page=wpdataaccess" class="wpda_shortcode_link">&raquo; Manage settings</a>
					</p>
					<?php 
        }
        ?>
			</div>
			<?php 
        $actions['shortcode'] = sprintf(
            '<a href="javascript:void(0)"
						class="view wpda_tooltip"
						title="%s"
						onclick="jQuery(\'#wpda_publication_%s\').dialog()"
						<span style="white-space:nowrap">
							<i class="fas fa-code wpda_icon_on_button"></i>
							%s
						</span>
					</a>
					',
            __( 'Get shortcode', 'wp-data-access' ),
            esc_attr( $item['pub_id'] ),
            __( 'Shortcode', 'wp-data-access' )
        );
        $actions['test'] = sprintf(
            '<a href="javascript:void(0)"
						class="view wpda_tooltip"
						title="%s"
						onclick="test_publication(\'%s\', \'%s\')"
						<span style="white-space:nowrap">
							<i class="fas fa-bug wpda_icon_on_button"></i>
							%s
						</span>
					</a>
					',
            __( 'Test', 'wp-data-access' ),
            esc_attr( wp_create_nonce( "wpda-publication-{$item['pub_id']}" ) ),
            esc_attr( $item['pub_id'] ),
            __( 'Test', 'wp-data-access' )
        );
    }

    public function process_bulk_action() {
        if ( 'copy' === $this->current_action() ) {
            $wp_nonce_action = "wpda-copy-{$this->table_name}";
            $wp_nonce = ( isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '' );
            // input var okay.
            if ( !wp_verify_nonce( $wp_nonce, $wp_nonce_action ) ) {
                die( __( 'ERROR: Not authorized', 'wp-data-access' ) );
            }
            if ( isset( $_REQUEST['pub_id'] ) ) {
                $pub_id = sanitize_text_field( wp_unslash( $_REQUEST['pub_id'] ) );
                // input var okay.
            }
            $unique_pu_name = $this->get_unique_pub_name( $pub_id );
            global $wpdb;
            $pub_raw = $wpdb->get_results( $wpdb->prepare( 
                'SELECT * FROM `%1s` WHERE pub_id = %d',
                // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders
                array(WPDA::remove_backticks( $this->table_name ), $pub_id)
             ), 'ARRAY_A' );
            if ( $wpdb->num_rows > 0 ) {
                $pub_raw[0]['pub_name'] = $unique_pu_name;
                unset($pub_raw[0]['pub_id']);
                $rows_inserted = $wpdb->insert( $this->table_name, $pub_raw[0] );
                switch ( $rows_inserted ) {
                    case 0:
                        $msg = new WPDA_Message_Box(array(
                            'message_text'           => __( 'Could not copy data table [source not found]', 'wp-data-access' ),
                            'message_type'           => 'error',
                            'message_is_dismissible' => false,
                        ));
                        $msg->box();
                        break;
                    case 1:
                        $msg = new WPDA_Message_Box(array(
                            'message_text' => __( 'Data table copied', 'wp-data-access' ),
                        ));
                        $msg->box();
                        break;
                    default:
                        $msg = new WPDA_Message_Box(array(
                            'message_text'           => __( 'Could not copy data table [too many rows]', 'wp-data-access' ),
                            'message_type'           => 'error',
                            'message_is_dismissible' => false,
                        ));
                        $msg->box();
                }
            }
        } else {
            parent::process_bulk_action();
        }
    }

    protected function get_unique_pub_name( $pub_id ) {
        global $wpdb;
        $db_pub_name = $wpdb->get_results( $wpdb->prepare( 
            'select pub_name from `%1s` where pub_id = %d',
            // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders
            array(WPDA::remove_backticks( WPDA_Publisher_Model::get_base_table_name() ), $pub_id)
         ), 'ARRAY_A' );
        if ( $wpdb->num_rows !== 1 ) {
            wp_die( __( 'ERROR: Data table not found', 'wp-data-access' ) );
        }
        $i = 2;
        $pub_name = $db_pub_name[0]['pub_name'];
        $unique_pub_name = "{$pub_name}_{$i}";
        $wpdb->get_results( $wpdb->prepare( 
            "select 'x' from `%1s` where pub_name = %s",
            // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders
            array(WPDA::remove_backticks( WPDA_Publisher_Model::get_base_table_name() ), $unique_pub_name)
         ) );
        while ( $wpdb->num_rows > 0 ) {
            // Search until a free options set is found
            $i++;
            $unique_pub_name = "{$pub_name}_{$i}";
            $wpdb->get_results( $wpdb->prepare( 
                "select 'x' from `%1s` where pub_name = %s",
                // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders
                array(WPDA::remove_backticks( WPDA_Publisher_Model::get_base_table_name() ), $unique_pub_name)
             ) );
        }
        return $unique_pub_name;
    }

    public static function column_headers_labels() {
        return array(
            'pub_id'                          => __( 'ID', 'wp-data-access' ),
            'pub_name'                        => __( 'Name', 'wp-data-access' ),
            'pub_schema_name'                 => __( 'Database', 'wp-data-access' ),
            'pub_data_source'                 => __( 'Data Source', 'wp-data-access' ),
            'pub_table_name'                  => __( 'Table/View Name', 'wp-data-access' ),
            'pub_column_names'                => __( 'Column Names', 'wp-data-access' ),
            'pub_format'                      => __( 'Format', 'wp-data-access' ),
            'pub_query'                       => __( 'Query', 'wp-data-access' ),
            'pub_sort_icons'                  => __( 'Sort Icons', 'wp-data-access' ),
            'pub_styles'                      => __( 'Styling', 'wp-data-access' ),
            'pub_style_premium'               => __( 'Enable Premium Styling', 'wp-data-access' ),
            'pub_style_user'                  => __( 'Custom Style', 'wp-data-access' ),
            'pub_style_color'                 => __( 'Style Color', 'wp-data-access' ),
            'pub_style_space'                 => __( 'Style Spacing', 'wp-data-access' ),
            'pub_style_corner'                => __( 'Style Corner', 'wp-data-access' ),
            'pub_style_modal_width'           => __( 'Modal Width', 'wp-data-access' ),
            'pub_responsive'                  => __( 'Output', 'wp-data-access' ),
            'pub_table_options_searching'     => __( 'Searching?', 'wp-data-access' ),
            'pub_table_options_ordering'      => __( 'Ordering?', 'wp-data-access' ),
            'pub_table_options_paging'        => __( 'Paging?', 'wp-data-access' ),
            'pub_table_options_serverside'    => __( 'Server Side Processing?', 'wp-data-access' ),
            'pub_table_options_nl2br'         => __( 'NL > BR', 'wp-data-access' ),
            'pub_table_options_advanced'      => __( 'Advanced Options', 'wp-data-access' ),
            'pub_default_where'               => __( 'Default Where', 'wp-data-access' ),
            'pub_default_orderby'             => __( 'Default Order By', 'wp-data-access' ),
            'pub_responsive_popup_title'      => __( 'Popup Title', 'wp-data-access' ),
            'pub_responsive_cols'             => __( 'Responsive Cols', 'wp-data-access' ),
            'pub_responsive_type'             => __( 'Responsive Type', 'wp-data-access' ),
            'pub_responsive_modal_hyperlinks' => __( 'Modal Hyperlinks', 'wp-data-access' ),
            'pub_responsive_icon'             => __( 'Responsive Icon?', 'wp-data-access' ),
            'pub_flat_scrollx'                => __( 'Horizontal Scrollbar', 'wp-data-access' ),
            'pub_extentions'                  => __( 'Extensions', 'wp-data-access' ),
            'pub_cpt'                         => __( 'Custom post type', 'wp-data-access' ),
            'pub_cpt_fields'                  => __( 'Custom fields', 'wp-data-access' ),
            'pub_cpt_query'                   => __( 'CPT query', 'wp-data-access' ),
            'pub_cpt_format'                  => __( 'Field labels', 'wp-data-access' ),
        );
    }

    // Overwrite method
    public function show() {
        parent::show();
        WPDA::shortcode_popup();
        ?>
			<script type="application/javascript">
				function test_publication(wpnonce, pub_id) {
					if (jQuery('#data_publisher_test_container_' + pub_id).length>0) {
						jQuery('#data_publisher_test_container_' + pub_id).show();
					} else {
						jQuery.ajax({
							type: "POST",
							url: "<?php 
        echo admin_url( 'admin-ajax.php?action=wpda_test_publication' );
        // phpcs:ignore WordPress.Security.EscapeOutput
        ?>",
							data: {
								wpnonce: wpnonce,
								pub_id: pub_id
							}
						}).done(
							function (html) {
								jQuery("body").append(html);
								jQuery('#data_publisher_test_container_' + pub_id).show();
							}
						);
					}
				}
				jQuery(function() {
					jQuery(".wpda_tooltip_cq").tooltip({
						tooltipClass: "wpda_tooltip_cq wpda_tooltip_dashboard"
					});
				});
			</script>
			<style>
				table.wp-list-table td.pub_data_source div {
	                overflow-x: hidden;
                    max-height: 70px;
                    overflow-y: auto;
				}
				.row-actions {
					white-space: nowrap;
				}
				.wpda_tooltip_cq {
					max-width: max-content;
				}
			</style>
			<?php 
    }

}
