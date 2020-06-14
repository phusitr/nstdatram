<?php

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
