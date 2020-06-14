<?php

/*******************************************************************
 * Author : Phusit Roongroj <phusit@nectec.or.th>
 * Network Technology Lab (NTL), 
 * National Electronics and Computer Technology Center , NECTEC
 *******************************************************************
 */

/* call important library */
@require_once dirname(__FILE__) . '/lib/pg.class.php';
@require_once dirname(__FILE__) . '/config/config.inc.php';

$c = new postgresql (); /* Use postgresql library */

if ($c->connect ( $config )) {

	$rs = $c->query ( "exam1 where name ='CAR'" );
	
	$i = 0;
	while ( $i < $c->numrows ( $rs ) ) {
		$data = $c->fetchobject ( $rs );
		echo $data ->id . "\t";
		echo $data ->name . "\t";
		echo $data ->surname . "\n";
		$i++;
	}

	$c->close();
}

?>
