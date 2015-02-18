<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Filter by member:key="val"
 *
 * @package        low_search
 * @author         Lodewijk Schutte ~ Low <hi@gotolow.com>
 * @link           https://github.com/low/low_search_members
 * @copyright      Copyright (c) 2015, Low
 */
class Low_search_filter_members extends Low_search_filter {

	/**
	 * Prefix
	 */
	private $_pfx = 'member:';

	/**
	 * Allowed keys
	 */
	private $_keys = array(
		'username',
		'screen_name',
		'email',
		'url',
		'location',
		'occupation',
		'interests',
		'bday_d',
		'bday_m',
		'bday_y',
		'aol_im',
		'yahoo_im',
		'msn_im',
		'icq',
		'bio',
		'signature'
	);

	// --------------------------------------------------------------------

	/**
	 * Allows for member:key="val" parameters
	 *
	 * @access     private
	 * @return     void
	 */
	public function filter($entry_ids)
	{
		// --------------------------------------
		// Get member params
		// --------------------------------------

		$params = $this->params->get_prefixed($this->_pfx, TRUE);
		$params = array_filter($params, 'low_not_empty');

		// --------------------------------------
		// Bail out of nothing's there
		// --------------------------------------

		if (empty($params))
		{
			return $entry_ids;
		}

		// --------------------------------------
		// Log it
		// --------------------------------------

		$this->_log('Applying '.__CLASS__);

		// --------------------------------------
		// loop through params and collect where clauses
		// --------------------------------------

		$where = array();

		foreach ($params AS $key => $val)
		{
			$search = FALSE;

			// Search field?
			if (strpos($key, 'search:') === 0)
			{
				$search = TRUE;
				$key = substr($key, 7);
				$val = $this->params->prep($key, $val);
			}

			// Skip unknown keys
			if ( ! in_array($key, $this->_keys)) continue;

			// Add prefix to key
			$key = 'm.'.$key;

			// Get where clause
			if ($search)
			{
				$where[] = $this->_get_where_search($key, $val);
			}
			else
			{
				list($val, $in) = $this->params->explode($val);
				$where[] = array($key, ($in ? 'where_in' : 'where_not_in'), $val);
			}
		}

		// --------------------------------------
		// No valid params? Bail out
		// --------------------------------------

		if (empty($where))
		{
			$this->_log('No valid member filters found');
			return $entry_ids;
		}

		// --------------------------------------
		// Get channel IDs before starting the query
		// --------------------------------------

		$channel_ids = ee()->low_search_collection_model->get_channel_ids();

		// --------------------------------------
		// Start the query
		// --------------------------------------

		ee()->db->select('t.entry_id')
		    ->distinct()
		    ->from('channel_titles t')
		    ->join('members m', 't.author_id = m.member_id');

		// Existing member IDs?
		if ($entry_ids)
		{
			ee()->db->where_in('t.entry_id', $entry_ids);
		}

		// Channel IDs?
		if ($channel_ids)
		{
			ee()->db->where_in('t.channel_id', $channel_ids);
		}

		// Handle where clauses
		foreach ($where AS $sql)
		{
			if (is_array($sql))
			{
				list($key, $method, $val) = $sql;
				ee()->db->$method($key, $val);
			}
			else
			{
				ee()->db->where($sql, NULL, FALSE);
			}
		}

		// --------------------------------------
		// Get it
		// --------------------------------------

		$query = ee()->db->get();

		// --------------------------------------
		// Flatten it
		// --------------------------------------

		$entry_ids = low_flatten_results($query->result_array(), 'entry_id');

		// --------------------------------------
		// Return it
		// --------------------------------------

		return $entry_ids;
	}
}