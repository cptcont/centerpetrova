<?php
error_reporting( E_ERROR );
include('/home/h806235746/centerpetrova.com/docs/settings.php');
include('/home/h806235746/centerpetrova.com/docs/func_date.php');

//include('../settings.php');
//include('../func_date.php');

/*
 * https://petrov16.t8s.ru/Api/V1/GetLearners?authkey=NS3oJybhEGfcu%2FKaf9yaggSW6l%2Bbqe6a1mv9XzWjqmtckNtG7bE%2FqOOnNL1O6%2FCE&onlyForming=true&types=Groups,Exams
 */
$url = 'https://petrov16.t8s.ru/Api/V1/GetLearners';
$data = "types=Groups,Exams&onlyForming=true&authkey=".$authKey;
$result = array();
$curl = curl_init($url.'?'.$data);
//curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
//curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
$res = curl_exec($curl);
$res = json_decode($res);
//print_r($res);

if (!empty($res->Learners)) {
    $db->query('DELETE FROM shedule');
    foreach ($res->Learners as $group) {
        $db->assign('itemId', $group->Id);
        $db->assign('Name', $group->Name);
        $db->assign('Discipline', $group->Discipline);
        $db->assign('Level', $group->Level);
        $db->assign('LearningType', $group->LearningType);
        $db->assign('Maturity', $group->Maturity);
        $db->assign('StudentsCount', $group->StudentsCount);
        $db->assign('Vacancies', $group->Vacancies);
        if (!empty($group->ScheduleItems)) {
            foreach ($group->ScheduleItems as $item) {
                if ($item->BeginDate) {
                    $arrdat = explode('.', $item->BeginDate);
                    if ($arrdat[0] && $arrdat[1] && $arrdat[2])
                    //$item->BeginDate = dateToSql($item->BeginDate);
                    $db->assign('BeginDate', $arrdat[2].'-'.$arrdat[1].'-'.$arrdat[0]);
                }
                if ($item->EndDate) {
                    $arrdat = explode('.', $item->EndDate);
                    //$item->EndDate = dateToSql($item->EndDate);
                    $db->assign('EndDate', $arrdat[2].'-'.$arrdat[1].'-'.$arrdat[0]);
                }
                $db->assign('Teacher', $item->Teacher);
                $db->insert('shedule');
            }
        }
    }
}

?>