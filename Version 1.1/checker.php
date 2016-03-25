    <?php
    /* 
	# Modified and adapted for multi-threading by Samuel Allan <naclo3samuel@gmail.com>
	# You can use this script for any purposes, as long as you leave a line of credit somewhere on your page.
	#
	# Credits to W. Al Maawali for the original script which was great!
	# The guy is a founder of Eagle Eye Digital Solutions,
	# And a direct link to his demonstration of how this works can be found on:
	# https://www.digi77.com/validating-proxy-via-php/
	#
	# 
	*/
	
	//Set max execution time
	set_time_limit(100);
	
	
	
	//We support two ways of using this script,
	//The first way is to directly supply an ip:port and get the results.
	//Another way to use this script is to supply a filename (e.g. checker.php?file=somefile.txt)
	//To determine whether we are being supplied an ip-port or a filename, we'll take a look at the get params
	
	//Step 1 - Check whether the user specified a timeout
	if(!isset($_GET['timeout']))
	{
		die("You must specify a timeout in seconds in your request (checker.php?...&timeout=20)");
	}

	//Step 2 - Go through a loop to determine what the user wants us to do
	if(isset($_GET['file']))
	{
		//If we can't find the file, complain
		if(!file_exists($_GET['file']))
		{
			die("Could not find file '" . $_GET['file'] . "'");
		}
		
		//Convert the file to a list of proxies
		$array = file($_GET['file']);
		CheckMultiProxy($array, $_GET['timeout']);
	}
	else if(isset($_GET['ip']) && isset($_GET['port']))
	{
		CheckSingleProxy($_GET['ip'], $_GET['port'], $_GET['timeout']);
	}
	else
	{
		die("<h2>Could not find the required GET parameters.</h2><br /><b>To check a proxy use:</b><br /><i>checker.php?ip=...&port=...</i><br /><b>To go through a list of proxies (IP:PORT Format) use:</b><br /><i>checker.php?file=...</i>");
	}
	
	
     
    function CheckMultiProxy($proxies, $timeout)
	{
		$data = array();
		foreach($proxies as $proxy)
		{
			$parts = explode(':', trim($proxy));
			$url = strtok(curPageURL(),'?');
			$data[] = $url . '?ip=' . $parts[0] . "&port=" . $parts[1] . "&timeout=" . $timeout;
		}
		$results = multiRequest($data);
		$holder = array();
		foreach($results as $result)
		{
			
			$holder[] = json_decode($result, true)["result"];
		}
		$arr = array("results" => $holder);
		echo json_encode($arr);
	}
     
	 
	 
	 function CheckSingleProxy($ip, $port, $timeout, $echoResults=true)
	 {
		$passByIPPort= $ip . ":" . $port;
		 
		 
		// You can use virtually any website here, but in case you need to implement other proxy settings (show annonimity level)
		// I'll leave you with whatismyipaddress.com, because it shows a lot of info.
		$url = "http://whatismyipaddress.com/";
		 
		// Get current time to check proxy speed later on
		$loadingtime = microtime(true);
		 
		$theHeader = curl_init($url);
		curl_setopt($theHeader, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($theHeader, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($theHeader, CURLOPT_PROXY, $passByIPPort);
		
		//This is not another workaround, it's just to make sure that if the IP uses some god-forgotten CA we can still work with it ;)
		//Plus no security is needed, all we are doing is just 'connecting' to check whether it exists!
		curl_setopt($theHeader, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($theHeader, CURLOPT_SSL_VERIFYPEER, 0);
		 
		//Execute the request
		$curlResponse = curl_exec($theHeader);
		 
		 
		 
		if ($curlResponse === false) 
		{
			$arr = array(
				"result" => array(
					"success" => false,
					"error" => curl_error($theHeader),
					"proxy" => array(
						"ip" => $ip,
						"port" => $port
					)
				)
			);
			if($echoResults)
			{ 
				echo json_encode($arr);
			}
			return $arr;
		} 
		else 
		{
			$arr = array(
				"result" => array(
					"success" => true,
					"proxy" => array(
						"ip" => $ip,
						"port" => $port,
						"speed" => floor((microtime(true) - $loadingtime)*1000)
					)
				)
			);

			if($echoResults)
			{ 
				echo json_encode($arr);
			}
			return $arr;
		}
	 }
	 function multiRequest($data, $options = array()) 
	 {
	 
	  // array of curl handles
	  $curly = array();
	  // data to be returned
	  $result = array();
	 
	  // multi handle
	  $mh = curl_multi_init();
	 
	  // loop through $data and create curl handles
	  // then add them to the multi-handle
	  foreach ($data as $id => $d) {
	 
		$curly[$id] = curl_init();
	 
		$url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
		curl_setopt($curly[$id], CURLOPT_URL,            $url);
		curl_setopt($curly[$id], CURLOPT_HEADER,         0);
		curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
	 
		// post?
		if (is_array($d)) {
		  if (!empty($d['post'])) {
			curl_setopt($curly[$id], CURLOPT_POST,       1);
			curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
		  }
		}
	 
		// extra options?
		if (!empty($options)) {
		  curl_setopt_array($curly[$id], $options);
		}
	 
		curl_multi_add_handle($mh, $curly[$id]);
	  }
	 
	  // execute the handles
	  $running = null;
	  do {
		curl_multi_exec($mh, $running);
	  } while($running > 0);
	 
	 
	  // get content and remove handles
	  foreach($curly as $id => $c) {
		$result[$id] = curl_multi_getcontent($c);
		curl_multi_remove_handle($mh, $c);
	  }
	 
	  // all done
	  curl_multi_close($mh);
	 
	  return $result;
	}
function curPageURL() {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" .
            $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}	
	 ?>