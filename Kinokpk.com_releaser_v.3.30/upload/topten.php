<?php
/**
 * Top ten
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require_once "include/bittorrent.php";

dbconn();

loggedinorreturn();

$REL_TPL->stdhead($REL_LANG->say_by_key('topten'));
$res = sql_query("SELECT SUM(1) FROM users") or sqlerr(__FILE__, __LINE__);
$count = mysql_result($res,0);
if (!$count) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('nothing_found'),'error'); $REL_TPL->stdfoot(); die(); }
$perpage = 10;
list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, array('topten'));



$res = sql_query("SELECT u.*, c.name, c.flagpic FROM users AS u LEFT JOIN countries AS c ON c.id = u.country ORDER BY ratingsum DESC $limit") or sqlerr(__FILE__, __LINE__);
$num = mysql_num_rows($res);

print ('<div id="users-table">');
print ("<p>$pagertop</p>");
print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
print("<tr><td class=\"colhead\" align=\"left\">���</td><td class=\"colhead\">���������������</td><td class=\"colhead\">��������� ����</td><td class=\"colhead\">�������</td><td class=\"colhead\">���</td><td class=\"colhead\" align=\"left\">�������</td><td class=\"colhead\">������</td></tr>\n");
while ($arr = mysql_fetch_assoc($res)) {
	if ($arr['country'] > 0) {
		$country = "<td style=\"padding: 0px\" align=\"center\"><img src=\"pic/flag/$arr[flagpic]\" alt=\"$arr[name]\" title=\"$arr[name]\"></td>";
	}
	else
	$country = "<td align=\"center\">---</td>";
	$ratio = ratearea($arr['ratingsum'],$arr['id'],'users',$CURUSER['id']);

	if ($arr["gender"] == "1") $gender = "<img src=\"pic/male.gif\" alt=\"������\" title=\"������\" style=\"margin-left: 4pt\">";
	elseif ($arr["gender"] == "2") $gender = "<img src=\"pic/female.gif\" alt=\"�������\" title=\"�������\" style=\"margin-left: 4pt\">";
	else $gender = "<div align=\"center\"><b>?</b></div>";

	print("<tr><td align=\"left\"><a href=\"".$REL_SEO->make_link('userdetails','id',$arr['id'],'username',translit($arr["username"]))."\"><b>".get_user_class_color($arr["class"], $arr["username"])."</b></a>" .($arr["donated"] > 0 ? "<img src=\"pic/star.gif\" border=\"0\" alt=\"Donor\">" : "")."</td>" .
"<td>".mkprettytime($arr['added'])."</td><td>".mkprettytime($arr['last_access'])." (".get_elapsed_time($arr["last_access"],false)." {$REL_LANG->say_by_key('ago')})</td><td>$ratio</td><td>$gender</td>".
"<td align=\"left\">" . get_user_class_name($arr["class"]) . "</td>$country</tr>\n");
}
print("</table>\n");
print ("<p>$pagerbottom</p>");
print('</div>');

$REL_TPL->stdfoot();

?>
