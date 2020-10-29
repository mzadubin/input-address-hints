<?



/**
 * для записи окончаний числительных
 * 
 * @param int $dig     число
 * @param array $arTexts варианты окончаний для 1 2  и3 товаров.
 * @example pluralTextAfterDigits(count($arResult), array("1"=>"товар", "2"=>"товара", "0"=>"товаров"));
 * 
 * @return string вариант написания для указанного числа.
 */
function pluralTextAfterDigits($dig, $arTexts=array("1"=>"", "2"=>"", "0"=>"")){
  $comment = "";
	if(($dig==1) || ($dig%10==1 && $dig%100!=11)){
		$comment = $arTexts["1"];
	} else if (($dig%10>1) && ($dig%10<5) &&
	($dig%100!=12 && $dig%100!=13 && $dig%10!=14)){
		$comment = $arTexts["2"];
	} else {
		$comment = $arTexts["0"];
	}
	return $comment;
}

 


//function getFullUserFields()
//{
//    $arUser = \CUser::GetList(
//        ($by = "ID"), ($order = "desc"),
//        array(
//          "ID"=> intval(CUser::GetID()),
//        ),
//        array(
//          "SELECT" => array("UF_*"),
//          //"FIELDS" => array("ID")
//        )
//    )->GetNext();
//    
//    if ($arUser["PERSONAL_PHOTO"] > 0) {
//        $arUser["PHOTO_URL"] = \CFile::GetPath($arUser["PERSONAL_PHOTO"]);
//    }
//    
//    
//    return $arUser;
//}

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'K', 'M', 'G', 'T');   

    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}


function resizeImageGetCustom($iImageId = false, $iWidth = 100, $iHeight = 100, $alt = "")
{
    $arFile = CFile::ResizeImageGet($iImageId, array('width' => $iWidth, 'height' => $iHeight), BX_RESIZE_IMAGE_PROPORTIONAL, true);                
    $sImgString = '<img alt="' . $alt . '" src="' . $arFile['src'] . '" width="' . $arFile['width'] . '" height="' . $arFile['height'].'" />';
    return $sImgString;
}

function array_unshift_assoc(&$arr, $key, $val)
{
    $arr = array_reverse($arr, true);
    $arr[$key] = $val;
    return array_reverse($arr, true);
}


function getFullIblockElementById($elementId) {
  $elementId = intval($elementId);
  if (!$elementId) {
    return false;
  }
  
  $rs = \CIBlockElement::GetList(
    array(),
    array(
      "ID" => $elementId
    )
  );
  if (!$ob = $rs->GetNextElement() ) {
    return false;
  }
  
  $arElement = $ob->GetFields();
  $arElement["PROPS"] = $ob->GetProperties();
  return $arElement;
}


function getPublicLink($url)
{
  //$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  return $protocol . $_SERVER['SERVER_NAME'] . $url;
}

//function logFast($variable = false, $header = "", $filePrefix = "")
//{
//  $logPath = '/local/logs/';
//  
//  \Bitrix\Main\Diag\Debug::writeToFile($variable, $header, $logPath . $filePrefix . "__" . date("d_m_Y__H_i_s") . ".log");
//}
//


function makeCode($sName)
{
    $sName = htmlspecialchars_decode($sName);
    $sName = str_replace("", "", $sName);
    $sName = str_replace('"', "", $sName);
    $arParams = array("replace_space" => "-","replace_other" => "-");
    $sTrans = Cutil::translit($sName, "ru", $arParams);
    return $sTrans;
}
    


function prepareUserFullName($arUser)
{
  $arFullName = array();
  if ($arUser["NAME"]) {
    $arFullName[] = $arUser['NAME'];
  }
  if ($arUser["SECOND_NAME"]) {
    $arFullName[] = $arUser['SECOND_NAME'];
  }
  if ($arUser["LAST_NAME"]) {
    $arFullName[] = $arUser['LAST_NAME'];
  }
  
  if (empty($arFullName) ) {
    $arFullName[] = $arUser["LOGIN"];
  }
  
  return implode(" ", $arFullName);
}



//maxim api
function ppp($p,$name){
    global $APPLICATION,$USER;
		if ($USER->IsAdmin()){
			echo "<br/>-----------------$name START -------------------<br/>";
			echo "<pre>";
			print_r($p);
			echo "</pre>";
			echo "<br/>-----------------$name END -------------------<br/>";
		}
}


//maxim api
function ddd($text){
    global $APPLICATION,$USER;
		if ($USER->IsAdmin()){
			echo "<br/>-----------------$text DIE START-------------------<br/>";
			echo "<pre>";
			print_r($p);
			echo "</pre>";
			echo "<br/>-----------------$text DIE END -------------------<br/>";
die();		
}
}

function pp($p,$name){
			echo "<br/>-----------------$name START -------------------<br/>";
			echo "<pre>";
			print_r($p);
			echo "</pre>";
			echo "<br/>-----------------$name END -------------------<br/>";
}

function m($number){
    global $USER;
		if($USER->IsAdmin()){
			echo "test step: $number<br/>";
		}
}

function mm($number){
		echo "test step: $number<br/>";
} 



function rus_date() {
	// Перевод
	 $translate = array(
	 "am" => "дп",
	 "pm" => "пп",
	 "AM" => "ДП",
	 "PM" => "ПП",
	 "Monday" => "Понедельник",
	 "Mon" => "Пн",
	 "Tuesday" => "Вторник",
	 "Tue" => "Вт",
	 "Wednesday" => "Среда",
	 "Wed" => "Ср",
	 "Thursday" => "Четверг",
	 "Thu" => "Чт",
	 "Friday" => "Пятница",
	 "Fri" => "Пт",
	 "Saturday" => "Суббота",
	 "Sat" => "Сб",
	 "Sunday" => "Воскресенье",
	 "Sun" => "Вс",
	 "January" => "Января",
	 "Jan" => "Янв",
	 "February" => "Февраля",
	 "Feb" => "Фев",
	 "March" => "Марта",
	 "Mar" => "Мар",
	 "April" => "Апреля",
	 "Apr" => "Апр",
	 "May" => "Мая",
	 "May" => "Мая",
	 "June" => "Июня",
	 "Jun" => "Июн",
	 "July" => "Июля",
	 "Jul" => "Июл",
	 "August" => "Августа",
	 "Aug" => "Авг",
	 "September" => "Сентября",
	 "Sep" => "Сен",
	 "October" => "Октября",
	 "Oct" => "Окт",
	 "November" => "Ноября",
	 "Nov" => "Ноя",
	 "December" => "Декабря",
	 "Dec" => "Дек",
	 "st" => "ое",
	 "nd" => "ое",
	 "rd" => "е",
	 "th" => "ое"
	 );
	 // если передали дату, то переводим ее
	 if (func_num_args() > 1) {
	 $timestamp = func_get_arg(1);
	 return strtr(date(func_get_arg(0), $timestamp), $translate);
	 } else {
	// иначе текущую дату
	 return strtr(date(func_get_arg(0)), $translate);
	 }
}

function rus_date2() {
	// Перевод
	 $translate = array(
	 "am" => "дп",
	 "pm" => "пп",
	 "AM" => "ДП",
	 "PM" => "ПП",
	 "Monday" => "Понедельник",
	 "Mon" => "Пн",
	 "Tuesday" => "Вторник",
	 "Tue" => "Вт",
	 "Wednesday" => "Среда",
	 "Wed" => "Ср",
	 "Thursday" => "Четверг",
	 "Thu" => "Чт",
	 "Friday" => "Пятница",
	 "Fri" => "Пт",
	 "Saturday" => "Суббота",
	 "Sat" => "Сб",
	 "Sunday" => "Воскресенье",
	 "Sun" => "Вс",
	 "January" => "Январь",
	 "Jan" => "Янв",
	 "February" => "Февраль",
	 "Feb" => "Фев",
	 "March" => "Март",
	 "Mar" => "Мар",
	 "April" => "Апрель",
	 "Apr" => "Апр",
	 "May" => "Май",
	 "May" => "Май",
	 "June" => "Июнь",
	 "Jun" => "Июн",
	 "July" => "Июль",
	 "Jul" => "Июл",
	 "August" => "Август",
	 "Aug" => "Авг",
	 "September" => "Сентябрь",
	 "Sep" => "Сен",
	 "October" => "Октябрь",
	 "Oct" => "Окт",
	 "November" => "Ноябрь",
	 "Nov" => "Ноя",
	 "December" => "Декабрь",
	 "Dec" => "Дек",
	 "st" => "ое",
	 "nd" => "ое",
	 "rd" => "е",
	 "th" => "ое"
	 );
	 // если передали дату, то переводим ее
	 if (func_num_args() > 1) {
	 $timestamp = func_get_arg(1);
	 return strtr(date(func_get_arg(0), $timestamp), $translate);
	 } else {
	// иначе текущую дату
	 return strtr(date(func_get_arg(0)), $translate);
	 }
}