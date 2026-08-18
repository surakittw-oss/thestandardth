<?php
/**
 * Appearance → Homepage: the theme's homepage settings screen.
 *
 * Everything the front page shows — how many hero posts, whether sticky posts
 * lead, which category/tag blocks appear and in what order — is stored in the
 * single `ts_home_settings` option and read back by ts_home_settings().
 *
 * @package the-standard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default homepage settings.
 *
 * @return array
 */
function ts_home_defaults() {
	return array(
		'hero_count'      => 7,
		'hero_use_sticky' => 1,
		'sections'        => array(),
		'show_latest'     => 1,
		'latest_label'    => '',
		'latest_count'    => 9,
	);
}

/**
 * Current homepage settings, with defaults filled in.
 *
 * @return array
 */
function ts_home_settings() {
	$saved = get_option( 'ts_home_settings', array() );
	return wp_parse_args( is_array( $saved ) ? $saved : array(), ts_home_defaults() );
}

/**
 * Register the option.
 */
function ts_register_home_settings() {
	register_setting(
		'ts_home_settings_group',
		'ts_home_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'ts_sanitize_home_settings',
			'default'           => ts_home_defaults(),
			'show_in_rest'      => false,
		)
	);
}
add_action( 'admin_init', 'ts_register_home_settings' );

/**
 * Validate and normalise everything coming from the form.
 *
 * Section rows are re-indexed here, so the saved order is the submitted order.
 * Rows pointing at a term that no longer exists are dropped.
 *
 * @param mixed $input Raw form input.
 * @return array
 */
function ts_sanitize_home_settings( $input ) {
	$out   = ts_home_defaults();
	$input = is_array( $input ) ? $input : array();

	$out['hero_count']      = max( 1, min( 12, isset( $input['hero_count'] ) ? (int) $input['hero_count'] : 7 ) );
	$out['hero_use_sticky'] = empty( $input['hero_use_sticky'] ) ? 0 : 1;
	$out['show_latest']     = empty( $input['show_latest'] ) ? 0 : 1;
	$out['latest_label']    = isset( $input['latest_label'] ) ? sanitize_text_field( $input['latest_label'] ) : '';
	$out['latest_count']    = max( 1, min( 30, isset( $input['latest_count'] ) ? (int) $input['latest_count'] : 9 ) );

	$sections = array();
	$rows     = isset( $input['sections'] ) && is_array( $input['sections'] ) ? $input['sections'] : array();
	foreach ( $rows as $row ) {
		if ( empty( $row['term'] ) || false === strpos( $row['term'], ':' ) ) {
			continue;
		}
		list( $tax, $term_id ) = explode( ':', sanitize_text_field( $row['term'] ), 2 );
		$term_id               = (int) $term_id;
		if ( ! in_array( $tax, array( 'category', 'post_tag' ), true ) || ! $term_id ) {
			continue;
		}
		$term = get_term( $term_id, $tax );
		if ( ! $term || is_wp_error( $term ) ) {
			continue;
		}

		$sections[] = array(
			'term'  => $tax . ':' . $term_id,
			'label' => isset( $row['label'] ) ? sanitize_text_field( $row['label'] ) : '',
			'count' => max( 1, min( 24, isset( $row['count'] ) ? (int) $row['count'] : 6 ) ),
		);
	}
	$out['sections'] = $sections;

	return $out;
}

/**
 * Add the screen under Appearance.
 */
function ts_add_homepage_page() {
	add_theme_page(
		__( 'Homepage', 'the-standard' ),
		__( 'Homepage', 'the-standard' ),
		'edit_theme_options',
		'ts-homepage',
		'ts_homepage_admin_page'
	);
}
add_action( 'admin_menu', 'ts_add_homepage_page' );

/**
 * jQuery UI sortable powers the drag handles on this screen only.
 *
 * @param string $hook Current admin page hook.
 */
function ts_homepage_admin_assets( $hook ) {
	if ( 'appearance_page_ts-homepage' === $hook ) {
		wp_enqueue_script( 'jquery-ui-sortable' );
	}
}
add_action( 'admin_enqueue_scripts', 'ts_homepage_admin_assets' );

/**
 * <option> list of every category and tag, grouped, for the section picker.
 *
 * @param string $selected Value to preselect, as "taxonomy:term_id".
 * @return string
 */
function ts_term_options( $selected = '' ) {
	$html = '<option value="">' . esc_html__( '— เลือกหมวดหมู่ / แท็ก —', 'the-standard' ) . '</option>';

	$groups = array(
		__( 'หมวดหมู่', 'the-standard' ) => array( 'category', get_categories( array( 'hide_empty' => false ) ) ),
		__( 'แท็ก', 'the-standard' )      => array( 'post_tag', get_tags( array( 'hide_empty' => false, 'number' => 200 ) ) ),
	);

	foreach ( $groups as $group_label => $group ) {
		list( $tax, $terms ) = $group;
		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			continue;
		}
		$html .= '<optgroup label="' . esc_attr( $group_label ) . '">';
		foreach ( $terms as $term ) {
			$value = $tax . ':' . $term->term_id;
			$html .= sprintf(
				'<option value="%s"%s>%s (%d)</option>',
				esc_attr( $value ),
				selected( $selected, $value, false ),
				esc_html( $term->name ),
				(int) $term->count
			);
		}
		$html .= '</optgroup>';
	}

	return $html;
}

/**
 * One section row. Pass index "__i__" to emit the JS clone template.
 *
 * @param string|int $index Row index used in the field names.
 * @param array      $row   Row data.
 */
function ts_render_section_row( $index, $row = array() ) {
	$row  = wp_parse_args( $row, array( 'term' => '', 'label' => '', 'count' => 6 ) );
	$base = 'ts_home_settings[sections][' . $index . ']';
	?>
	<tr class="ts-section-row">
		<td class="ts-drag" title="<?php esc_attr_e( 'ลากเพื่อจัดลำดับ', 'the-standard' ); ?>">⣿</td>
		<td>
			<select name="<?php echo esc_attr( $base . '[term]' ); ?>" class="ts-term-select">
				<?php echo ts_term_options( $row['term'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</select>
		</td>
		<td>
			<input type="text" name="<?php echo esc_attr( $base . '[label]' ); ?>"
				value="<?php echo esc_attr( $row['label'] ); ?>"
				placeholder="<?php esc_attr_e( '(ใช้ชื่อหมวดหมู่เดิม)', 'the-standard' ); ?>" class="regular-text">
		</td>
		<td>
			<input type="number" min="1" max="24" name="<?php echo esc_attr( $base . '[count]' ); ?>"
				value="<?php echo esc_attr( $row['count'] ); ?>" class="small-text">
		</td>
		<td>
			<button type="button" class="button-link delete ts-remove-section"><?php esc_html_e( 'ลบ', 'the-standard' ); ?></button>
		</td>
	</tr>
	<?php
}

/**
 * The settings screen.
 */
function ts_homepage_admin_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$s = ts_home_settings();

	// Theme pages don't print the "Settings saved." notice on their own.
	if ( isset( $_GET['settings-updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		add_settings_error( 'ts_home_settings', 'ts_saved', __( 'บันทึกการตั้งค่าแล้ว', 'the-standard' ), 'success' );
	}
	?>
	<div class="wrap ts-home-settings">
		<h1><?php esc_html_e( 'Homepage', 'the-standard' ); ?></h1>
		<?php settings_errors( 'ts_home_settings' ); ?>
		<p class="description">
			<?php esc_html_e( 'ตั้งค่าหน้าแรก — โพสต์เด่นด้านบน และบล็อกเนื้อหาแยกตามหมวดหมู่/แท็ก', 'the-standard' ); ?>
		</p>

		<form method="post" action="options.php" id="ts-home-form">
			<?php settings_fields( 'ts_home_settings_group' ); ?>

			<h2 class="title"><?php esc_html_e( 'โพสต์เด่น (ด้านบนสุด)', 'the-standard' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="ts_hero_count"><?php esc_html_e( 'จำนวนโพสต์', 'the-standard' ); ?></label></th>
					<td>
						<input type="number" min="1" max="12" id="ts_hero_count"
							name="ts_home_settings[hero_count]" value="<?php echo esc_attr( $s['hero_count'] ); ?>" class="small-text">
						<p class="description"><?php esc_html_e( 'ชิ้นแรกแสดงใหญ่ ที่เหลือเป็นการ์ดด้านล่าง (แนะนำ 7)', 'the-standard' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'โพสต์ปักหมุด', 'the-standard' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="ts_home_settings[hero_use_sticky]" value="1" <?php checked( $s['hero_use_sticky'] ); ?>>
							<?php esc_html_e( 'ให้โพสต์ที่ปักหมุดขึ้นเป็นโพสต์เด่นก่อน', 'the-standard' ); ?>
						</label>
						<p class="description">
							<?php esc_html_e( 'ปักหมุดที่หน้าแก้ไขโพสต์ → กล่องเผยแพร่ → การมองเห็น → แก้ไข → ปักหมุดไว้ที่หน้าแรก', 'the-standard' ); ?>
							<?php if ( $sticky = get_option( 'sticky_posts' ) ) : ?>
								<br><strong><?php echo esc_html( sprintf( __( 'ตอนนี้ปักหมุดไว้ %d โพสต์', 'the-standard' ), count( (array) $sticky ) ) ); ?></strong>
							<?php endif; ?>
						</p>
					</td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( 'บล็อกเนื้อหา', 'the-standard' ); ?></h2>
			<p class="description">
				<?php esc_html_e( 'แต่ละแถวคือหนึ่งบล็อกบนหน้าแรก · ลากไอคอนซ้ายเพื่อจัดลำดับ · โพสต์ในบล็อกเรียงจากใหม่ไปเก่าอัตโนมัติ', 'the-standard' ); ?>
			</p>

			<table class="widefat striped ts-sections-table">
				<thead>
					<tr>
						<th class="ts-col-drag"></th>
						<th class="ts-col-term"><?php esc_html_e( 'หมวดหมู่ / แท็ก', 'the-standard' ); ?></th>
						<th><?php esc_html_e( 'ชื่อหัวข้อที่แสดง', 'the-standard' ); ?></th>
						<th class="ts-col-count"><?php esc_html_e( 'จำนวนโพสต์', 'the-standard' ); ?></th>
						<th class="ts-col-del"></th>
					</tr>
				</thead>
				<tbody id="ts-section-rows">
					<?php
					if ( $s['sections'] ) {
						foreach ( $s['sections'] as $i => $row ) {
							ts_render_section_row( $i, $row );
						}
					}
					?>
				</tbody>
			</table>
			<?php if ( ! $s['sections'] ) : ?>
				<p class="description ts-sections-empty">
					<?php esc_html_e( 'ยังไม่ได้ตั้งค่า — หน้าแรกจะใช้ 4 หมวดหมู่ที่มีโพสต์มากที่สุดอัตโนมัติ', 'the-standard' ); ?>
				</p>
			<?php endif; ?>

			<p><button type="button" class="button" id="ts-add-section">+ <?php esc_html_e( 'เพิ่มบล็อก', 'the-standard' ); ?></button></p>

			<h2 class="title"><?php esc_html_e( 'ข่าวล่าสุด (ท้ายหน้า)', 'the-standard' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'แสดงบล็อกนี้', 'the-standard' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="ts_home_settings[show_latest]" value="1" <?php checked( $s['show_latest'] ); ?>>
							<?php esc_html_e( 'แสดงบล็อกข่าวล่าสุดที่ท้ายหน้าแรก', 'the-standard' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'ไม่นับโพสต์ที่อยู่ในโพสต์เด่นแล้ว', 'the-standard' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="ts_latest_label"><?php esc_html_e( 'ชื่อหัวข้อ', 'the-standard' ); ?></label></th>
					<td>
						<input type="text" id="ts_latest_label" name="ts_home_settings[latest_label]"
							value="<?php echo esc_attr( $s['latest_label'] ); ?>" class="regular-text"
							placeholder="<?php esc_attr_e( 'ข่าวล่าสุด', 'the-standard' ); ?>">
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="ts_latest_count"><?php esc_html_e( 'จำนวนโพสต์', 'the-standard' ); ?></label></th>
					<td>
						<input type="number" min="1" max="30" id="ts_latest_count"
							name="ts_home_settings[latest_count]" value="<?php echo esc_attr( $s['latest_count'] ); ?>" class="small-text">
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>

		<script type="text/html" id="ts-section-template">
			<?php ts_render_section_row( '__i__' ); ?>
		</script>
	</div>

	<style>
		.ts-home-settings .ts-sections-table { max-width: 940px; margin-top: 10px; }
		.ts-home-settings .ts-col-drag  { width: 34px; }
		.ts-home-settings .ts-col-count { width: 110px; }
		.ts-home-settings .ts-col-del   { width: 60px; }
		.ts-home-settings .ts-drag { cursor: grab; color: #a7aaad; text-align: center; user-select: none; font-size: 15px; }
		.ts-home-settings .ts-section-row.ui-sortable-helper { background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,.14); }
		.ts-home-settings .ts-term-select { max-width: 260px; }
	</style>

	<script>
	jQuery(function ($) {
		var $rows = $('#ts-section-rows');

		$rows.sortable({ handle: '.ts-drag', axis: 'y', opacity: 0.85 });

		$('#ts-add-section').on('click', function () {
			var tpl = $('#ts-section-template').html().replace(/__i__/g, 'n' + Date.now());
			$rows.append(tpl);
			$('.ts-sections-empty').hide();
		});

		$rows.on('click', '.ts-remove-section', function () {
			$(this).closest('tr').remove();
		});

		// PHP reads the bracket index, not DOM order — renumber on submit so the
		// order you dragged into is the order that gets saved.
		$('#ts-home-form').on('submit', function () {
			$rows.children('tr').each(function (i) {
				$(this).find('[name]').each(function () {
					this.name = this.name.replace(/\[sections\]\[[^\]]*\]/, '[sections][' + i + ']');
				});
			});
		});
	});
	</script>
	<?php
}
