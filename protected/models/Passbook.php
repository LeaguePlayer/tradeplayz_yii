<?php

class Passbook
{
	public $holder;

	public function getPassbookCard()
	{
		Yii::import('application.PKPass.PKPass',true);
      	$pass = new \PKPass\PKPass(); 


      	$pass->setCertificate(Yii::app()->getBasePath().'/PKPass/cert/pass.p12'); // Set the path to your Pass Certificate (.p12 file)
		$pass->setCertificatePassword('qwelpo3452'); // Set password for certificate
		$pass->setWWDRcertPath(Yii::app()->getBasePath().'/PKPass/cert/pass.pem');

		$pass->setJSON( $this->getJson() );

					    // add files to the PKPass package
					    $pass->addFile(Yii::app()->getBasePath().'/PKPass/resource/'.'icon.png');
					    $pass->addFile(Yii::app()->getBasePath().'/PKPass/resource/'.'icon@2x.png');
					    $pass->addFile(Yii::app()->getBasePath().'/PKPass/resource/'.'logo.png');
					    $pass->addFile(Yii::app()->getBasePath().'/PKPass/resource/'.'logo@2x.png');

					    if(!$pass->create(true)) { // Create and output the PKPass
					        echo 'Error: '.$pass->getError();
					    }
					    exit;
	}

	public function getJson()
	{

		return '{
				  "formatVersion" : 1,
				  "passTypeIdentifier" : "pass.com.amobile.malloko.app",
				  "serialNumber" : "8j23fm3",
				  "webServiceURL" : "https://example.com/passes/",
				  "authenticationToken" : "vxwxd7J8AlNNFPS8k0a0FfUFtq0ewzFdc",
				  "teamIdentifier" : "559UN83XVC",
				  "locations" : [
				    {
				      "longitude" : -122.3748889,
				      "latitude" : 37.6189722
				    },
				    {
				      "longitude" : -122.03118,
				      "latitude" : 37.33182
				    }
				  ],
				  "organizationName" : "Malloko",
				  "description" : "Membership card",
				  "logoText" : "Malloko",
				  "foregroundColor" : "rgb(255, 255, 255)",
				  "backgroundColor" : "rgb(33, 30, 27)",
				  "generic" : {
				    "primaryFields" : [
				      {
				        "key" : "member",
				        "value" : "'.$this->holder->name.'",
				        "textAlignment" : "PKTextAlignmentCenter"
				      }
				    ],
				   
				    "auxiliaryFields" : [
				      
				      {
				        "key" : "favorite",
				        "label" : "номер вашей карты",
				        "value" : "'.$this->holder->card_relation->card->getFormatedCardNumber().'",
				        "textAlignment" : "PKTextAlignmentCenter"
				      }
				    ],
				    "backFields" : [
				      
				      {
				        "key" : "card_holder",
				        "label" : "Владелец карты",
				        "value" : "'.$this->holder->name.'"
				      },
				      {
				        "key" : "card_number",
				        "label" : "Номер карты",
				        "value" : "'.$this->holder->card_relation->card->getFormatedCardNumber().'"
				      },
				      
				    ]
				  }
				}';
	}
}