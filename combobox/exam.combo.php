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
 * @name       exam.combo.php
 * @category   nstdatram
 * @author     napatsorn seesuwan <seesuwannapatsorn@gmail.com>
 * @copyright  2020
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @since      File available since Release 1.0.0
 */


@require_once dirname(__FILE__) . "/lib/common.class.php";

$html = new html ();
$html->headerMainPage ();
$html->sysMainMenu ();

?>

<div data-options="region:'center',title:'หน้าหลัก'">
    <div class="easyui-tabs" data-options="fit:true,border:false,plain:true">
        <div title="" style="padding:5px">
	<input id="cc" class="easyui-combobox" name="txtexample" label="โปรดเลือก" required="true" size="50"
	data-options="editable:false,valueField:'org_id',textField:'org_name',url:'exam.combo.data.php'">
	</div>

    </div>
</div>
<script type="text/javascript">
$('#cc').combobox({
	filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].indexOf(q) == 0;
	}
});
</script>

<?php
        $html->endheaderMainPage ();
?>
