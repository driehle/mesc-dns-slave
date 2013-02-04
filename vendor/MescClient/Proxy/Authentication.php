<?php

class MescClient_Proxy_Authentication extends Zend_XmlRpc_Client_ServerProxy
{
	/**
	 * Namespace of this proxy
	 * @var string
	 */
	const PROXY_NAMESPACE = 'dnszone';
	
	/**
	 * Namespace of this proxy
	 * @var string
	 */
	const PROXY_NAMESPACE = 'dnszone';
	
	/**
	 * Class constructor
	 *
	 * @param Client $client
	 */
	public function __construct(MescClient_Client $client)
	{
		$this->client = $client;
		parent::__construct($client, self::PROXY_NAMESPACE);
	}
	
	/**
	 * Validates an API token
	 * @param string $token
	 */
	public function validate($token)
	{
		return $this->client->call(self::PROXY_NAMESPACE . '.validate', array($token), true);
	}
}