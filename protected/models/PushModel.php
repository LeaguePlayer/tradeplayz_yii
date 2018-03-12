<?php
// use for send PUSH Android and iOs
class PushModel
{
	//settings
	protected $API_ACCESS_KEY_ANDROID = "AIzaSyBijtt8WejyozbHNr5tllGkRlpklRnpuLI";
	protected $IOS_DEVELOPMENT_CERT = "d_tpz_app.pem";
	protected $IOS_PRODUCTION_CERT = "push_prod_malloko.pem";
	// end settings


	protected $registredDevices = array();
	protected $dataPush = array();
	protected $step = 1;

	public $DEBUG_MODE = self::DEBUG_ON;
	public $message = "";

	
	


	const DEBUG_ON = 1;
	const DEBUG_OFF = 0;

	const OS_ANDROID = 'android';
	const OS_IOS = 'ios';



	public function addAndroidDevices( $value )
	{

		if(is_array($value) && $this->registredDevices[self::OS_ANDROID]!==null)
			{
				if($this->registredDevices[self::OS_ANDROID]===null)
					$this->registredDevices[self::OS_ANDROID] = $value;
				else
					$this->registredDevices = array_merge($this->registredDevices[self::OS_ANDROID], $value);
			}
		else
			$this->registredDevices[self::OS_ANDROID][] = $value;
	}

	public function addIOsDevices( $value )
	{
		if(is_array($value))
			{
				if($this->registredDevices[self::OS_IOS]===null)
					$this->registredDevices[self::OS_IOS] = $value;
				else
					$this->registredDevices = array_merge($this->registredDevices[self::OS_IOS], $value);
			}
		else
			$this->registredDevices[self::OS_IOS][] = $value;
	}

	public function removeAndroidDevices()
	{
		unset($this->registredDevices[self::OS_ANDROID]);
		$this->registredDevices[self::OS_ANDROID] = array();
	}

	public function removeIOsDevices()
	{
		unset($this->registredDevices[self::OS_IOS]);
		$this->registredDevices[self::OS_IOS] = array();
	}

	public function removeAllDevices()
	{
		unset($this->registredDevices);
		$this->registredDevices = array();
	}

	public function setData( $array )
	{
		$this->dataPush = $array;
	}

	public function sendPush()
	{
		$result = array();
		
		if($this->registredDevices[self::OS_ANDROID]!==null)
			$result[self::OS_ANDROID] = $this->sendPushAndroid();
		if($this->registredDevices[self::OS_IOS]!==null)
			$result[self::OS_IOS] = $this->sendPushIOs();
		return $result;
	}

	public function sendPushAndroid()
	{
		define( 'API_ACCESS_KEY', $this->API_ACCESS_KEY_ANDROID );


			$registrationIds = $this->registredDevices[self::OS_ANDROID];
			$dataPush = $this->dataPush;
			$dataPush['message'] = $this->message;

			$fields = array
			(
				'registration_ids' 	=> $registrationIds,
				'data'			=> $dataPush
			);
			 
			$headers = array
			(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
			 
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
			$result = curl_exec($ch );
			curl_close( $ch );

			return (array) json_decode($result);
	}

	public function sendPushIOs()
	{
		
		if(empty($this->message))
			die("MESSAGE IS EMPTY");

		$my_msg = $this->message;
		$streamContext = stream_context_create();

		if($this->DEBUG_MODE == self::DEBUG_ON)
		{
			stream_context_set_option($streamContext, 'ssl', 'local_cert', $this->IOS_DEVELOPMENT_CERT); 
		   	$socketClient = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195',$error, $errorString,60, STREAM_CLIENT_CONNECT, $streamContext);
		}
		elseif( $this->DEBUG_MODE == self::DEBUG_OFF )
		{

			stream_context_set_option($streamContext, 'ssl', 'local_cert', $this->IOS_PRODUCTION_CERT); 
			$socketClient = stream_socket_client('ssl://gateway.push.apple.com:2195',$error, $errorString,60, STREAM_CLIENT_CONNECT, $streamContext);
		}
		
		
		$all_devices = $this->registredDevices[self::OS_IOS];
		
		// SiteHelper::mpr($all_devices);die();
		$payload['aps'] = array('alert' => "found_replace", 'sound'=>'default', 'badge'=>+1);

		if(!empty($this->dataPush))
			$payload['data'] = $this->dataPush;

		$payload = json_encode($payload);
		$payload = str_replace('found_replace', $my_msg, $payload);
		$i = 0;

		
// var_dump($payload);die();
// var_dump($all_devices);die();
	 
		foreach($all_devices as $deviceToken) {
			// var_dump($this->DEBUG_MODE);die();
			if ($i == $this->step) {
				$i = 0;
				fclose($socketClient);
				if($this->DEBUG_MODE == self::DEBUG_ON)
	   				$socketClient = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195',$error, $errorString,60, STREAM_CLIENT_CONNECT, $streamContext);
				elseif( $this->DEBUG_MODE == self::DEBUG_OFF )

					$socketClient = stream_socket_client('ssl://gateway.push.apple.com:2195',$error, $errorString,60, STREAM_CLIENT_CONNECT, $streamContext);
			}
			
			
			
			$message = pack('CnH', 0, 32, $deviceToken);
			$message = $message . pack('n', strlen($payload));
			$message = $message . $payload;

			
// var_dump($message);die();
			

			$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $deviceToken)) . chr(0) . chr(strlen($payload)) . $payload;
			++$i;
			
			fwrite($socketClient, $apnsMessage);

			
		}

		fclose($socketClient);
	}
}			