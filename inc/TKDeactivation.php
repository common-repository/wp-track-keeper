<?php

class TKDeactivation
{
	public static function run()
	{
		wp_clear_scheduled_hook( 'makeNewFileCheckNow' );
	}
}