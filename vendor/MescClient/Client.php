<?php

class MescClient_Client extends Zend_XmlRpc_Client
{
	/**
	 * Authentication token
	 * @var string
	 */
	protected $_token;
	
	/**
	 * Cache for service proxies
	 * @var Zend_XmlRpc_Client_ServerProxy[]
	 */
	protected $_serviceProxies;
	
	/**
	 * Set the authentication token
	 * @param string $token
	 */
	public function setToken($token)
	{
		$this->_token = (string) $token;
		return $this;
	}
	
	/**
	 * Get the authentication token
	 * @return string|null
	 */
	public function getToken()
	{
		return $this->_token;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Zend_XmlRpc_Client::call()
	 */
	public function call ($method, $params = array(), $noToken = false)
	{
		if (!$noToken && substr($method, 0, 7) != 'system.') {
			// add token as first parameter
			array_unshift($params, $this->getToken());
		}
		
		return parent::call($method, $params);
	}
	
	/**
	 * Get the proxy for authentication
	 * @return MescClient_Client_Proxy_Authentication
	 */
	public function getAuthenticationProxy()
	{
		if (!isset($this->_serviceProxies[MescClient_Proxy_Authentication::PROXY_NAMESPACE])) {
			$this->_serviceProxies[MescClient_Proxy_Authentication::PROXY_NAMESPACE]
				= new MescClient_Proxy_Authentication($this);
		}
		
		return $this->_serviceProxies[MescClient_Proxy_Authentication::PROXY_NAMESPACE];
	}
	
	/**
	 * Get the proxy for DNS zones
	 * @return MescClient_Client_Proxy_DnsZone
	 */
	public function getDnsZoneProxy()
	{
		if (!isset($this->_serviceProxies[MescClient_Proxy_DnsZone::PROXY_NAMESPACE])) {
			$this->_serviceProxies[MescClient_Proxy_DnsZone::PROXY_NAMESPACE]
				= new MescClient_Proxy_DnsZone($this);
		}
		
		return $this->_serviceProxies[MescClient_Proxy_DnsZone::PROXY_NAMESPACE];
	}
}