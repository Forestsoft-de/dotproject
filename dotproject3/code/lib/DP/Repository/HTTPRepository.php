<?php
/**
 * The HTTPRepository class provides listing and download functionality for HTTP based repositories.
 * 
 * @author mosen
 * @package dotProject
 * @subpackage lib
 */

require_once('DP/Repository/Repository.php');



/**
 * The HTTPRepository class provides listing and download functionality for HTTP based repositories.
 *
 * The HTTP repository class uses a package index to determine the filename and title of each module.
 */
class HTTPRepository extends DP_Repository {
	
	/**
	 * List the packages available in this repository
	 * 
	 * Uses DP_Fetcher to retrieve the package listing which is then converted into an associative
	 * array and returned.
	 * 
	 * @return
	 */
	function listRepository() {
		
	}
}
?>