<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('time_ago'))
{
	function time_ago($time, $max_units = NULL)
	{
		$lengths = array(1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600);
		//$units = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade');
        $units = array('s', 'm', 'h', 'd', 'w', 'm', 'y', 'd');
		$unit_string_array = array();
	
		$max_units = (is_numeric($max_units) && in_array($max_units, range(1,8))) ? $max_units : sizeOf($lengths);
		$diff = (is_numeric($time) ? time() - $time : time() - strtotime($time));
		$future = ($diff < 0) ? 1 : 0;
		$diff = abs($diff); // Let's get positive!
		
		$total_units = 0;
		for ($i = sizeOf($lengths) - 1; $i >= 0; $i--)
		{
			if ($diff > $lengths[$i] && $total_units < $max_units)
			{
				$amount = floor($diff / $lengths[$i]);
				$mod = $diff % $lengths[$i];
				
				//$unit_string_array[] = $amount . ' ' . $units[$i]  . (($amount == 1) ? '' : 's');
                $unit_string_array[] = $amount  . $units[$i];
				$diff = $mod;
				$total_units++;
			}
		}
		
		return ($future) ? implode($unit_string_array, ', ') . ' to go' : implode($unit_string_array, ', ');// . ' ago';
	}
}
