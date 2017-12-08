<?php

class APNSPush
{
	public $message = "";
	public $method = 0;
	public $dataArrayForPush = array();

	const METHOD_DEVELOPMENT = 0;
	const METHOD_PRODUCTION = 1;



	protected $step = 1;
	protected $devices = array();


	public function addRecipientPush( $deviceToken )
	{
		$this->devices[] = $deviceToken;
		$this->devices = array_unique($this->devices);
	}

	public function sendPersonalPush()
	{
		if(!empty($this->devices))
			$this->sendPush();
	}

	public function sendPushForAll()
	{
		unset($this->devices);

		foreach(UserDevices::getIphoneTokens() as $device)
			$this->devices[] = $device->deviceToken;

		$this->sendPush();
	}


	protected function sendPush()
	{
		
		if(empty($this->message))
			die("MESSAGE IS EMPTY");

		$my_msg = $this->message;
		$streamContext = stream_context_create();

		if($this->method == self::METHOD_DEVELOPMENT)
		{
			// die('ds');
			stream_context_set_option($streamContext, 'ssl', 'local_cert', 'development_malloko_app.pem'); 
			// stream_context_set_option($streamContext, 'ssl', 'local_cert', 'push_dev_malloko.pem'); 
			// stream_context_set_option($streamContext, 'ssl', 'passphrase', '1234');
		   	$socketClient = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195',$error, $errorString,60, STREAM_CLIENT_CONNECT, $streamContext);
		}
		elseif( $this->method == self::METHOD_PRODUCTION )
		{
			// stream_context_set_option($streamContext, 'ssl', 'local_cert', 'development_malloko_app.pem'); 
			// stream_context_set_option($streamContext, 'ssl', 'passphrase', '1234');
			$socketClient = stream_socket_client('ssl://gateway.push.apple.com:2195',$error, $errorString,60, STREAM_CLIENT_CONNECT, $streamContext);
		}
		
		$all_devices = $this->devices;
		
		
		$payload['aps'] = array('alert' => "found_replace", 'sound'=>'default', 'badge'=>1);

		if(!empty($this->dataArrayForPush))
			$payload['data'] = $this->dataArrayForPush;

		$payload = json_encode($payload);
		$payload = str_replace('found_replace', $my_msg, $payload);
		$i = 0;

		// $modelNotificationRead = new NotificationRead;
		// var_dump($all_devices);
		$all_devices = array('802b9759d89f71d9fc5182ccbe38cf149a9ae925ffac33dd7d07078310046e71');
		// die();
	 
		foreach($all_devices as $deviceToken) {
			if ($i == $this->step) {
				$i = 0;
				fclose($socketClient);
				if($this->method == self::METHOD_DEVELOPMENT)
	   				$socketClient = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195',$error, $errorString,60, STREAM_CLIENT_CONNECT, $streamContext);
				elseif( $this->method == self::METHOD_PRODUCTION )
					$socketClient = stream_socket_client('ssl://gateway.push.apple.com:2195',$error, $errorString,60, STREAM_CLIENT_CONNECT, $streamContext);
			}
			
			
			
			$message = pack('CnH', 0, 32, $deviceToken);
			$message = $message . pack('n', strlen($payload));
			$message = $message . $payload;

			

			

			$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $deviceToken)) . chr(0) . chr(strlen($payload)) . $payload;
			++$i;
			
			fwrite($socketClient, $apnsMessage);

			
		}

		fclose($socketClient);
	}
}