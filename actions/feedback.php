<?
$tmpRes = 0;
$resMess = array('',
	'<h2 class="infoGreen">���� ��������� ����������. �������.</h2>',
	'<h2 class="infoRed">������ �������� ���������, ���������� �����.</h2>'
	);

if(($_POST['message']) && ($_POST['submit'])) {

	$tmpUser = fullProtect($_POST['user']);
	$tmpMessage = fullProtect($_POST['message']);

	$tmpMess = "��������� �� inclouds-cms.ru\r\n\r\n";
	$tmpMess .= '������������: ' . $tmpUser . "\r\n";
	$tmpMess .= '���������: ' . $tmpMessage . "\r\n";

	mysql_query("INSERT INTO feedback (date, name, contacts, message) VALUES(NOW(), '$tmpUser', '', '$tmpMessage');", $baseCon) or die($baseErr);

	if(smtpmail($siteComVars['adminMail'], '��������� �� inclouds-cms.ru', $tmpMess)) {
		$tmpRes = 1;
		}
	else {$tmpRes = 2;}
	}
?>