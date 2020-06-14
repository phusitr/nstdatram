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

	$rs = $c->insertrow ( 'exam1','id,name,surname',"15,'Tony','Lagoon'");
	echo $rs;	
	$c->close();
}

?>
