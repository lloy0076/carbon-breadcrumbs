<?php
/**
 * Date archive breadcrumb item locator class.
 * 
 * Used to locate the breadcrumb items for date archives.
 */
class Carbon_Breadcrumb_Locator_Date extends Carbon_Breadcrumb_Locator {

	/**
	 * Whether this the items of this locator should be included in the trail.
	 *
	 * @access public
	 *
	 * @return bool $is_included Whether the found items should be included.
	 */
	public function is_included() {
		return is_date();
	}

	/**
	 * Retrieve the items, found by this locator.
	 *
	 * @access public
	 *
	 * @param int $priority The priority of the located items.
	 * @return array $items The items, found by this locator.
	 */
	public function get_items($priority = 1000) {
		$items = array();
		
		// prepare the date archive item details
		$date_archives = array(
			'year' => array(
				'condition' => is_year() || is_month() || is_day(),
				'title_format' => 'Y',
				'link' => get_year_link(get_query_var('year')),
			),
			'month' => array(
				'condition' => is_month() || is_day(),
				'title_format' => 'F',
				'link' => get_month_link(get_query_var('year'), get_query_var('monthnum')),
			),
			'day' => array(
				'condition' => is_day(),
				'title_format' => 'd',
				'link' => get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day')),
			),
		);

		// add the associated date archive breadcrumb items
		foreach ($date_archives as $archive_name => $archive_details) {
			if ($archive_details['condition']) {
				$item = Carbon_Breadcrumb_Item::factory('custom', $priority);
				$item->set_title( get_the_time( $archive_details['title_format'] ) );
				$item->set_link( $archive_details['link'] );
				$item->setup();
				$items[] = $item;
			}
		}

		return $items;
	}

	/**
	 * Generate a set of breadcrumb items that found by this locator type and any subtype.
	 * The date archive locator has no subtypes, so this method wraps around get_items().
	 *
	 * @access public
	 *
	 * @return array $items The items, generated by this locator.
	 */
	public function generate_items() {
		if (!$this->is_included()) {
			return array();
		}
		
		return $this->get_items();
	}
	
}