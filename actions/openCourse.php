<?php
include('../settings.php');
include('../func_date.php');
$result = array('type' => 'error', 'data' => 'Не получены данные');
if ($_REQUEST['course'] && intval($_REQUEST['course']) > 0) {
    $course_id = intval($_REQUEST['course']);
    $db->query("SELECT itemId FROM shedule WHERE id = $course_id");
    $itemId = $db->get_result();
    $sql = "SELECT *, MIN(BeginDate) AS minDate, MAX(EndDate) as maxDate FROM shedule WHERE itemId = $itemId GROUP BY itemId ORDER BY BeginDate;";
    $db->query($sql);
    if ($course = $db->movenext()) {
        if ($course['minDate'])
            $course['BeginDate'] = dateToSmall($course['minDate']);
        if ($course['maxDate'])
            $course['EndDate'] = dateToSmall($course['maxDate']);

        if ($course['Vacancies'] > 0) {
            $result = array('type' => 'success', 'data' => $course);
        } else {
            $result = array('type' => 'error', 'data' => 'На этот курс нет свободных мест.');
        }
    } else {
        $result = array('type' => 'error', 'data' => 'Не найден курс');
    }
}
echo json_encode($result);
?>