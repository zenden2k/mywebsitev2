<?php
//phpinfo();
//die();
if (!function_exists('getallheaders')) {
    function getallheaders() {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $headers['Content-Type'] = $_SERVER['CONTENT_TYPE'];
		}
        return $headers;
    }
}
	$name = '';
	$version = '';
	$requestHeaders = array();
	foreach (getallheaders() as $name => $value)
	{
        $requestHeaders[$name] = $value;
	}

 	$requestContentType = isset($requestHeaders['Content-Type'])?$requestHeaders['Content-Type']:'';

 	if($requestContentType == 'application/json') {
 		$entityBody = file_get_contents('php://input');
 		$req = json_decode($entityBody, true);
 		if ($req != NULL) {
 			if (isset($req['Package']['Name'])) {
                $name = $req['Package']['Name'];
            }
            if (isset($req['AppVersion']['FullString'])) {
                $version = $req['AppVersion']['FullString'];
            }
		}
	} else {
        $name = isset($_GET["package"]) ? $_GET["package"] : null;
        $version = isset($_GET['ver']) ? $_GET['ver'] : null;
    }
	function abort()
	{
		header("HTTP/1.1 404 Not Found");
		$name = $_GET["package"];
		echo "zenden.ws: package not found\r\n";
		echo "Package name: ".$name;
		die("\r\nError :( Sorry!");	
	}
	
	
	if(preg_match('/[0-9A-Za-z_]/',$name))
	{
        /*if ( (strpos($version, '1.2.5') !== FALSE || strpos($version, '1.2.6.3771') !== FALSE ) && $name == 'iu_core') {
            $filename ='iu_core_old.xml';
        } else if ( strpos($version, '1.3.1') === 0  && $name == 'iu_core') {
            $filename ='iu_core_beta.xml';
        }else */
        if ( strpos($version, '1.3.2') !== FALSE && $name != 'iu_ffmpeg') {
            $name = $name .'_beta';
        }
        {
            $filename = $name . ".xml";
        }
		if(!file_exists($filename)) {
			//echo "File '$filename' not found!!";
			abort();
		}



       file_put_contents('log.txt', "IP: ".$_SERVER['REMOTE_ADDR']." ".json_encode($_GET)."\r\n", FILE_APPEND);
		header("Content-Type: text/xml");
		echo file_get_contents($filename);
	}
	else abort();
?>
