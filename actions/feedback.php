<?
$tmpRes = 0;
$resMess = array('',
	'<h2 class="infoGreen">Ваше сообщение отправлено. Спасибо.</h2>',
	'<h2 class="infoRed">Ошибка отправки сообшения, попробуйте позже.</h2>'
	);

if(($_POST['message']) && ($_POST['submit'])) {

	$tmpUser = fullProtect($_POST['user']);
	$tmpMessage = fullProtect($_POST['message']);

	$tmpMess = "Сообщение на inclouds-cms.ru\r\n\r\n";
	$tmpMess .= 'Пользователь: ' . $tmpUser . "\r\n";
	$tmpMess .= 'Сообщение: ' . $tmpMessage . "\r\n";

	mysql_query("INSERT INTO feedback (date, name, contacts, message) VALUES(NOW(), '$tmpUser', '', '$tmpMessage');", $baseCon) or die($baseErr);

	if(smtpmail($siteComVars['adminMail'], 'Сообщение на inclouds-cms.ru', $tmpMess)) {
		$tmpRes = 1;
		}
	else {$tmpRes = 2;}
	}
?>