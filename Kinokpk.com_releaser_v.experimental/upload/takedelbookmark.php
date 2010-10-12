<?php
/**
 * Bookmark delete parser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
function bark($msg) {
	$REL_TPL->stdhead();
	stdmsg($REL_LANG->say_by_key('error'), $msg);
	$REL_TPL->stdfoot();
	exit;
}
dbconn();

loggedinorreturn();

if (!isset($_POST[delbookmark]))
bark($REL_LANG->say_by_key('no_selection'));

$res2 = sql_query("SELECT id, userid FROM bookmarks WHERE id IN (" . implode(", ", array_map("sqlesc", (array)$_POST[delbookmark])) . ")") or sqlerr(__FILE__, __LINE__);

while ($arr = mysql_fetch_assoc($res2)) {
	if (($arr[userid] == $CURUSER[id]) || (get_user_class() > 3))
	sql_query("DELETE FROM bookmarks WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
	else
	bark($REL_LANG->say_by_key('not_try_remove'));
}

safe_redirect(strip_tags($_SERVER['HTTP_REFERER']));
?>