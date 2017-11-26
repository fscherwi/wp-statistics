<?php
function wp_statistics_generate_referring_postbox_content( $count = 10 ) {
	global $wpdb, $WP_Statistics;
	$result = $wpdb->get_results(
		"SELECT `ref`, COUNT(`ref`) AS `count` FROM(
    			SELECT SUBSTRING_INDEX(
					REPLACE(REPLACE(REPLACE(`referred`, 'http://', ''), 'https://', ''), 'www.', ''),
					'/', 1
				) AS `ref`
				FROM `{$wpdb->prefix}statistics_visitor`
				WHERE `referred` <> '')
			  	AS `visitor`
			GROUP BY `ref`
			ORDER BY `count` DESC
			LIMIT {$count}"
	);
	?>
	<table width="100%" class="widefat table-stats left-align" id="last-referrer">
		<tr>
			<td width="10%"><?php _e('References', 'wp-statistics'); ?></td>
			<td width="90%"><?php _e('Address', 'wp-statistics'); ?></td>
		</tr>
		<?php
		foreach ( $result as $item ) {
			echo "<tr>";
			echo "<td><a href='?page=" .
			     WP_Statistics::$page['referrers'] .
			     "&referr=" .
			     $item->ref .
			     "'>" .
			     number_format_i18n($item->count) .
			     "</a></td>";
			echo "<td>" . $WP_Statistics->get_referrer_link($item->ref) . "</td>";
			echo "</tr>";
		}
		?>
	</table>
	<?php
}	
