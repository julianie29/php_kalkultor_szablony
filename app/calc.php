<?php
require_once dirname(__FILE__).'/../config.php';

require_once _ROOT_PATH.'/lib/smarty/Smarty.class.php';


function getParams(&$form){
    $form['value'] = isset($_REQUEST ['value']) ? $_REQUEST['value'] : null;
    $form['time'] = isset($_REQUEST ['time']) ? $_REQUEST['time'] : null;
    $form['interest'] = isset($_REQUEST ['interest']) ? $_REQUEST['interest'] : null;
}

function validate(&$form, &$messages){

    if (!(isset($form['value']) && isset($form['time']) && isset($form['interest']))) {
        return false;
    }

    if ( $form['value'] == "") $messages []  = 'Nie podano kwoty kredytu';
    if ( $form['time'] == "") $messages []  = 'Nie podano czasu kredytowania';
    if ( $form['interest'] == "") $messages []  = 'Nie podano oprocentowania';

    if (count($messages) > 0) return false;

        if (!is_numeric($form['value'])) $messages [] = 'Kwota kredytu nie jest liczbą';
        if (!is_numeric($form['time'])) $messages [] = 'Okres kredytowania nie jest liczbą';
        if (!is_numeric($form['interest'])) $messages [] = 'Oprocentowanie nie jest liczbą';

        if (count($messages) != 0) return false;
        else return true;
}

function process(&$form, &$messages, &$result){

    //konwersja parametrów na float
    $form['value'] = floatval($form['value']);
    $form['time'] = floatval($form['time']);
    $form['interest'] = floatval($form['interest']);

    $result = (($form['value']*(100+$form['interest']))/100)/($form['time']*12);

}

$value = null;
$time = null;
$interest = null;
$result = null;
$messages = array();

getParams($form);

if (validate($form,$messages)){
    process($form, $messages, $result);
}
$smarty = new Smarty();

$smarty->assign('app_url',_APP_URL);
$smarty->assign('root_path',_ROOT_PATH);
$smarty->assign('page_title','Kalkulator kredytowy');
$smarty->assign('page_description','Darmowy kalkulator kredytowy, dzięki któremu w szybki i łatwy sposób obliczysz ratę swojego kredytu');
$smarty->assign('page_header','Szablony Smarty');

//pozostałe zmienne niekoniecznie muszą istnieć, dlatego sprawdzamy aby nie otrzymać ostrzeżenia
$smarty->assign('form',$form);
$smarty->assign('result',$result);
$smarty->assign('messages',$messages);

$smarty->display(_ROOT_PATH.'/app/calc.html');


