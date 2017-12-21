<?php

class SiteHelper {

	public static function translit($str) {
		$tr = array(
			"А" => "a", "Б" => "b", "В" => "v", "Г" => "g",
			"Д" => "d", "Е" => "e", "Ж" => "j", "З" => "z", "И" => "i",
			"Й" => "y", "К" => "k", "Л" => "l", "М" => "m", "Н" => "n",
			"О" => "o", "П" => "p", "Р" => "r", "С" => "s", "Т" => "t",
			"У" => "u", "Ф" => "f", "Х" => "h", "Ц" => "ts", "Ч" => "ch",
			"Ш" => "sh", "Щ" => "sch", "Ъ" => "", "Ы" => "yi", "Ь" => "",
			"Э" => "e", "Ю" => "yu", "Я" => "ya", "а" => "a", "б" => "b",
			"в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j",
			"з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
			"м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
			"с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
			"ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y",
			"ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya",
			" " => "-", "." => "-", "/" => "-", "(" => "", ")" => "",
		);
		return strtr($str, $tr);
	}

	public static function pluralize($n, $arr) {

		$index = $n % 10 == 1 && $n % 100 != 11 ? 0 : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? 1 : 2);
		if ($arr) {
			return $n . ' ' . $arr[$index];
		} else {
			return $n;
		}
	}

	public static function scanNameModels($folderAlias = 'application.models', $parentClass = 'EActiveRecord') {
		$path = Yii::getPathOfAlias($folderAlias);
		$files = scandir($path);
		$ret = array();
		foreach ($files as $file) {
			if (($pos = strpos($file, '.php')) === false)
				continue;
			$modelClass = substr($file, 0, -4);
			try {
				if (get_parent_class($modelClass) === $parentClass) {
					$ret[] = $modelClass;
				}
			} catch (Exception $e) {
				continue;
			}
		}
		return $ret;
	}

	public static function genUniqueKey($length = 9, $salt = '') {
		$string = 'abcdefghijlklmnopqrstuvwxyzABCDEFGHIJLKLMNOPQRSTUVWXYZ1234567890';
		$result = '';
		$n = strlen($string);
		for ($i = 0; $i < $length; $i++) {
			$result .= $string[rand(0, $n)];
		}
		if ($length and $length > 0)
			return substr(md5($result . $salt . time()), 0, $length);
		else
			return substr(md5($result . time()), 0);
	}
	
	public static function russianMonth($monthNumber)
	{
		$n = (int) $monthNumber;
		switch ($n) {
			case 1:
				return 'января';
			case 2: 
				return 'февраля';
			case 3: 
				return 'марта';
			case 4: 
				return 'апреля';
			case 5: 
				return 'мая';
			case 6: 
				return 'июня';
			case 7: 
				return 'июля';
			case 8: 
				return 'августа';
			case 9: 
				return 'сентября';
			case 10: 
				return 'октября';
			case 11: 
				return 'ноября';
			case 12: 
				return 'декабря';
		}
	}

	public static function russianDate($datetime = null) {
        if (!$datetime || $datetime == 0)
            return '';
            
		if (is_numeric($datetime) ) {
			$timestamp = $datetime;
		} else if (is_string($datetime)) {
			$timestamp = strtotime($datetime);
        } else {
			$timestamp = time();
		}
		$date = explode(".", date("d.m.Y", $timestamp));
		$m = self::russianMonth($date[1]);
		return $date[0] . '&nbsp;' . $m . '&nbsp;' . $date[2];
	}
    
    public static function mpr($array)
    {
        echo "<pre>";
            print_r($array);
        echo "</pre>";
    }

    public static function elapsedTime($date)
    {

    	

         $time_now = time();
         $got_time = strtotime($date);

         $diff_time = floor(($time_now - $got_time) / (60));
         
          

         switch ($diff_time) {
         	case 0:
         			$diff_seconds = floor($time_now - $got_time);
         			$correct_word = Yii::t('app', '{n} секунду|{n} секунды|{n} секунд', $diff_seconds);
         			$result = "{$correct_word} назад";
         		break;
         	case ($diff_time >0 && $diff_time<60):
         			$correct_word = Yii::t('app', '{n} минута|{n} минуты|{n} минут', $diff_time);
         			$result = "{$correct_word} назад";
         		break;
         	
         	case ($diff_time>=60 && $diff_time<1440):
         			$diff_time = floor($diff_time/60);
         			$correct_word = Yii::t('app', '{n} час|{n} часа|{n} часов', $diff_time);
         			$result = "{$correct_word} назад";
         		break;
         	default:
	         		$result = date('d.m.Y', strtotime($date));
	         	break;
         }

         return $result;
    }

	public static function getGlobalSMTRPSettigs()
	{
		$config = array();
		$config['smtp_username'] = 'alex@tradeplayz.com';  //Смените на адрес своего почтового ящика.
$config['smtp_port'] = '465'; // Порт работы.
$config['smtp_host'] =  'ssl://smtp.gmail.com';  //сервер для отправки почты
$config['smtp_password'] = 'hbjsfk7676YF465tgds89)';  //Измените пароль
$config['smtp_debug'] = true;  //Если Вы хотите видеть сообщения ошибок, укажите true вместо false
$config['smtp_charset'] = 'utf-8';	//кодировка сообщений. (windows-1251 или utf-8, итд)
$config['smtp_from'] = 'TradePlayz Alex'; //Ваше имя - или имя Вашего сайта. Будет показывать при прочтении в поле "От кого"
		return $config;
	}

	public static function smtpmail($to='', $mail_to, $subject, $message, $headers='') {
	 $config = self::getGlobalSMTRPSettigs();
	
	$SEND =	"Date: ".date("D, d M Y H:i:s") . " UT\r\n";
	$SEND .= 'Subject: =?'.$config['smtp_charset'].'?B?'.base64_encode($subject)."=?=\r\n";
	if ($headers) $SEND .= $headers."\r\n\r\n";
	else
	{
			$SEND .= "Reply-To: ".$config['smtp_username']."\r\n";
			$SEND .= "To: \"=?".$config['smtp_charset']."?B?".base64_encode($to)."=?=\" <$mail_to>\r\n";
			$SEND .= "MIME-Version: 1.0\r\n";
			$SEND .= "Content-Type: text/html; charset=\"".$config['smtp_charset']."\"\r\n";
			$SEND .= "Content-Transfer-Encoding: 8bit\r\n";
			$SEND .= "From: \"=?".$config['smtp_charset']."?B?".base64_encode($config['smtp_from'])."=?=\" <".$config['smtp_username'].">\r\n";
			$SEND .= "X-Priority: 3\r\n\r\n";
	}
	$SEND .=  $message."\r\n";
	 if( !$socket = fsockopen($config['smtp_host'], $config['smtp_port'], $errno, $errstr, 30) ) {
		if ($config['smtp_debug']) echo $errno."<br>".$errstr;
		return false;
	 }
 
	if (!self::server_parse($socket, "220", __LINE__)) return false;
 
	fputs($socket, "HELO " . $config['smtp_host'] . "\r\n");

	if (!self::server_parse($socket, "250", __LINE__)) {

		if ($config['smtp_debug']) echo '<p>Не могу отправить HELO!</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "AUTH LOGIN\r\n");
	if (!self::server_parse($socket, "334", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>Не могу найти ответ на запрос авторизаци.</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, base64_encode($config['smtp_username']) . "\r\n");
	if (!self::server_parse($socket, "334", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>Логин авторизации не был принят сервером!</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, base64_encode($config['smtp_password']) . "\r\n");
	if (!self::server_parse($socket, "235", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>Пароль не был принят сервером как верный! Ошибка авторизации!</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "MAIL FROM: <".$config['smtp_username'].">\r\n");
	if (!self::server_parse($socket, "250", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>Не могу отправить комманду MAIL FROM: </p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "RCPT TO: <" . $mail_to . ">\r\n");
 
	if (!self::server_parse($socket, "250", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>Не могу отправить комманду RCPT TO: </p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "DATA\r\n");
 
	if (!self::server_parse($socket, "354", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>Не могу отправить комманду DATA</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, $SEND."\r\n.\r\n");
 
	if (!self::server_parse($socket, "250", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>Не смог отправить тело письма. Письмо не было отправленно!</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "QUIT\r\n");
	fclose($socket);
	return TRUE;
}

public static function server_parse($socket, $response, $line = __LINE__) {
	 $config = self::getGlobalSMTRPSettigs();
		 // var_dump($config);die();
	while (@substr($server_response, 3, 1) != ' ') {
		if (!($server_response = fgets($socket, 256))) {
			if ($config['smtp_debug']) echo "<p>Проблемы с отправкой почты!</p>$response<br>$line<br>";
 			return false;
 		}
	}
	if (!(substr($server_response, 0, 3) == $response)) {
		if ($config['smtp_debug']) echo "<p>Проблемы с отправкой почты!</p>$response<br>$line<br>";
		return false;
	}
	return true;
}

	public static function sendMail($subject,$message,$to='',$from='')
    {
        if($to=='') $to = Yii::app()->params['adminEmail'];
        if($from=='') $from = 'no-reply@torsim.ru';
        $headers = "MIME-Version: 1.0\r\nFrom: $from\r\nReply-To: $from\r\nContent-Type: text/html; charset=utf-8";
	    // $message = wordwrap($message, 70);
	    $message = str_replace("\n.", "\n..", $message);

	    $tos = explode(',', $to);
	    if(is_array($tos))
	    {
	    	foreach($tos as $to_mail)
	    		self::smtpmail(trim($to_mail), trim($to_mail), $subject, $message);
	    }
	    else
	    	self::smtpmail($to, $to, $subject, $message);


	    

        // return mail($to,'=?UTF-8?B?'.base64_encode($subject).'?=',$message,$headers);
    }

    public static function getPhoneByMask ($phone)
    {
        if($phone{0}.$phone{1} == '+7')
            $phone =  "8".substr($phone, 2);
        
        
        $length = strlen($phone);
        $tstr = '';
        for ( $i = 0; $i < $length; $i++ ) {

            switch ( $i ) {
                case 1:
                    $tstr .= ' (';
                    break;
                case 4:
                    $tstr .= ') ';
                    break;
                case 7:
                case 9:
                    $tstr .= '-';
                    break;
            }
            $tstr .= $phone[$i];

        }

        return $tstr;
    }

    public static function getPhoneByMaskTyumen ($phone)
    {
        if($phone{0}.$phone{1} == '+7')
            $phone =  "8".substr($phone, 2);
        
        
        $length = strlen($phone);
        $tstr = '';
        for ( $i = 0; $i < $length; $i++ ) {

            switch ( $i ) {
                case 1:
                    $tstr .= ' (';
                    break;
                case 5:
                    $tstr .= ') ';
                    break;
                case 7:
                case 9:
                    $tstr .= '-';
                    break;
            }
            $tstr .= $phone[$i];

        }

        return $tstr;
    }


    public static function clearPhoneMask ($phone)
    {
        $phone = str_replace("+7", "8", $phone);
        
        $phone = str_replace("-", "", $phone);
        $phone = str_replace("(", "", $phone);
        $phone = str_replace(")", "", $phone);
        $phone = str_replace("_", "", $phone);
        $phone = str_replace(" ", "", $phone);

        $phone = str_replace(array("-"," ","−",")","(","‒", chr(160)), '', $phone);
				$phone = iconv( "WINDOWS-1251","UTF-8", $phone);
				$phone = str_replace(array("В"), '', $phone);

				$phone = str_replace("+7", "8", $phone);

		if($phone[0] == '7'){
			$phone[0] = '8';
		}
		
        return $phone;
    }
}