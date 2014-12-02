<?php namespace SOSTheBlack\Moip;

use App;
use DB;

/**
 * MoipAbstract
 *
 * @package SOSTheBlack\Moip
 * @author Jean Cesar Garcia <jeancesargarcia@gmail.com> <SOSTheBlack>
 * @version 1.*
 **/
abstract class MoipAbstract
{
	/**
	 * Api of MoIP
	 *
	 * @var \SOSTheBlack\Moip\Api
	 **/
	protected $api;

	/**
	 * data of configuration of the MoIP
	 *
	 * @var array table moip
	 **/
	protected $moip;
	
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $data;

	/**
	* initialize()
	*
	* @return \SOSTheBlack\Moip\Api
	*/
	protected function initialize()
	{
		$this->moip = DB::table('moip')->first();
		$this->api  = App::make('\SOSTheBlack\Moip\Api');
		$this->api->setEnvironment(! $this->moip->environment);
		$this->api->setCredential([
		    'key' 	=> $this->moip->key,
		    'token' => $this->moip->token
		]);
		return $this->api;
	}

	/**
	 * setData()
	 * 
	 * @param string[] $data
	 * @return void
	 */
	protected function setData($data)
	{
		$this->data = $data;
	}	

	/**
	 * getValidate()
	 * 
	 * @return string
	 */
	protected function getValidate()
	{
		return $this->moip->validate === 0 ? 'Basic' : 'Identification';
	}

	/**
	 * getUniqueId()
	 * 
	 * adds unique id in the order
	 * 
	 * @return boolean|string
	 */
	protected function getUniqueId()
	{
		return isset($this->data['unique_id']) ? $this->data['unique_id'] : false;
	}

	/**
	 * getReason()
	 * 
	 * adds payment reason in the order
	 * 
	 * @return string
	 */
	protected function getReason()
	{
		return isset($this->data['reason']) ? $this->data['reason'] : $this->moip->reason;
	}

	/**
	 * getParams
	 * 
	 * Validation of the params sent
	 * 
	 * @param string $oneParam 
	 * @param string $twoParam 
	 * @param boolean $db 
	 * @return string
	 */
	protected function getParams($oneParam, $twoParam = null, $db = false)
	{
		if (! $twoParam) {
			if ($db === true) {
				return isset($this->data[$oneParam]) ? $this->data[$oneParam] : $this->moip->$oneParam;
			} else {
				return isset($this->data[$oneParam]) ? $this->data[$oneParam] : '';
			}
		} else {
			return isset($this->data[$oneParam][$twoParam]) ? $this->data[$oneParam][$twoParam] : '';
		}
	}

	/**
	 * getBilletInstructions
	 * 
	 * @return array
	 */
	protected function getBilletInstructions()
	{
		$billet = [
	    	'instructions' => [
	    		'firstLine',
				'secondLine',
				'lastLine'
	    	]
		];
		
		foreach ($billet['instructions'] as $keyInstructions => $valueInstructions) {
			$billet['instructions'][$keyInstructions] =
				isset($this->data['billet']['instructions'][$keyInstructions]) ? 
				$this->data['billet']['instructions'][$keyInstructions] :
				$this->moip->$valueInstructions;
		}
		
		return $billet['instructions'];
	}

	/**
	 * getPaymentWay
	 * 
	 * Adds payment way in the order
	 * 
	 * @return false|\SOSTheBlack\Moip\Api\
	 */
	protected function getPaymentWay()
	{
		if (! isset($this->data['paymentWay'])) {
			return false;
		} else {
			$payment  = $this->data['paymentWay'];
			$arrayWay = [
				'creditCard',
		    	'billet'	,
		    	'financing'	,
		    	'debit'		,
		    	'debitCard'	
		    ];

			foreach ($arrayWay as $arrayWayKey => $arrayWayValue) {
				if (isset($payment[$arrayWayKey]) && $payment[$arrayWayKey] == $arrayWayValue) {
					$this->api->addPaymentWay($arrayWayValue);
				} else {
					if ($this->moip->$arrayWayValue === 1) {
						$this->api->addPaymentWay($arrayWayValue);
					}
				}
			}
		}
	}

	/**
	 * getMessage
	 * 
	 * @return void
	 */
	protected function getMessage()
	{
		if (isset($this->data['message'])) {
			foreach ($this->data['message'] as $keyMessage => $valueMessage) {
				$this->api->addMessage($valueMessage);
			}
		}
	}

	/**
	 * getComission
	 * 
	 * @return void
	 */
	protected function getComission()
	{
		if (isset($this->data['comission'])) {
			foreach ($this->data['comission'] as $keyComission => $valueComission) {
				$this->api->addComission(
					isset($valueComission[0]) ? $valueComission[0] : null,
					isset($valueComission[1]) ? $valueComission[1] : null,
					isset($valueComission[2]) ? $valueComission[2] : null,
					isset($valueComission[3]) ? $valueComission[3] : null,
					isset($valueComission[4]) ? $valueComission[4] : null
				);
			}
		}
	}

	protected function getParcel()
	{
		if (isset($this->data['parcel'])) {
			foreach ($this->data['parcel'] as $keyParcel => $valueParcel) {
				$this->api->addParcel(
					isset($valueParcel[0]) ? $valueParcel[0] : null,
					isset($valueParcel[1]) ? $valueParcel[1] : null,
					isset($valueParcel[2]) ? $valueParcel[2] : null,
					isset($valueParcel[3]) ? $valueParcel[3] : false
				);
			}
		}
	}
}