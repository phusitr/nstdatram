
<?php
/**
 * Short description for file
 *
 * Long description for file (if any)...
 *
 * PHP version 7.2.x
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @name       exam.combo.data.php
 * @category   nstdatram
 * @author     napatsorn seesuwan <seesuwannapatsorn@gmail.com>
 * @copyright  2020
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @since      File available since Release 1.0.0
 */

require_once  dirname(__FILE__) . "/config/config.inc.php";
require_once  dirname(__FILE__) . "/lib/pg.class.php";

/* Connection to main nstdatram database */
$db = new postgresql ();


/* Connection to main nstdatram database */
if ($db->connect ( $config ))
{
	$rs = $db->query ("tblorginfo ORDER BY org_id ASC ");
		
		/* Check data in system database */
	if ( $db->numrows ($rs )) {
		$i = 0;
		$resule = array ();
		while ( $i < $db->numrows($rs)) {
		      $result[$i] = $db->fetchobject ( $rs );
		      $i++;
		}
	}

	echo json_encode ( $result );
}


?>
