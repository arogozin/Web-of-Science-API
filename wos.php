<?php

class WOS
{
	public static function search($query, $limit = 100, $offset = 1)
	{
		/* 
			Web Of Science - Authentication for SOAP
		*/
		$auth_url				=	"http://search.isiknowledge.com/esti/wokmws/ws/WOKMWSAuthenticate?wsdl";
		$auth_client			=	@new SoapClient($auth_url, array('cache_wsdl' => WSDL_CACHE_NONE));
		$auth_response			=	$auth_client->authenticate();
		
		$search_url				=	"http://search.isiknowledge.com/esti/wokmws/ws/WokSearch?wsdl";
		$search_client			=	@new SoapClient($search_url, array('cache_wsdl' => WSDL_CACHE_NONE));
		$search_client->__setCookie('SID',$auth_response->return);
		
		/*
			Query Processing
		*/
		$search_array			=	array
									(
										'queryParameters'		=>	array
																	(
																		'databaseID'	=>	'WOS',
																		'userQuery'		=>	$query,
																		'editions'		=>	array
																							(
																								array('collection' => 'WOS', 'edition' => 'SSCI'),
																								array('collection' => 'WOS', 'edition' => 'SCI')
																							),
																		'queryLanguage'	=>	'en'
																	),
										'retrieveParameters'	=>	array
																	(
																		'count'			=>	$limit,
																		'firstRecord'	=>	$offset,
																		'fields'		=>	array()
																	)
									);
		
		try
		{
			$result				=	$search_client->search($search_array);
			return $result;
		}
		catch (Exception $e)
		{
			echo $e->getMessage() ."\n";
		}
	}
}
