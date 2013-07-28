<?php


	// Keywords from Query String
	$q = 'celtics';
	$searched_lower = strtolower($q);
	$searched = strtoupper($searched_lower);
	$searchedURL = urlencode($searched); 
	
	$endpoint_stubhub = "http://publicfeed.stubhub.com/listingCatalog/select/";
	
	
	// StubHub API Query - JSON Response
	$url = "$endpoint_stubhub?q=%252BstubhubDocumentType%253Aevent%250D%250A%252B"
			. "%2Bleaf%253A%2Btrue%250D%250A%252B"
			. "%2Bdescription%253A%2B%22$searchedURL%22%250D%250A%252B"
			. "%3B$sort_what%20$sort_how"
			. "&version=2.2"
			. "&start=0"
			. "&indent=on"
			. "&wt=json"
			. "&fl=description+event_date+event_date_local+event_time_local+geography_parent+venue_name+city+state+genreUrlPath+urlpath+leaf+channel";
	
	
	//$url = 'http://www.stubhub.com/boston-celtics-tickets/boston-celtics-boston-td-garden-1-24-2013-4118558/';
	
	// Send Request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$body = curl_exec($ch);
	
	curl_close($ch);
	
	// Process JSON string - Convert JSON to PHP Array
	$json = json_decode($body);
print_r($json);
?>