<?php
echo "[] Input Email = ";
$email=trim(fgets(STDIN));
echo "[] Input Kode Reff = ";
$kodereff=trim(fgets(STDIN));
$headers = explode("\n","Host: ecoinofficial.org\nUser-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:72.0) Gecko/20100101 Firefox/72.0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nAccept-Language: id,en-US;q=0.7,en;q=0.3\nDNT: 1\nConnection: keep-alive\nUpgrade-Insecure-Requests: 1");
$cookie=request("https://ecoinofficial.org/referral/$kodereff",null,$headers,'GET');
$cfduid=get_between($cookie[1],"__cfduid=",";");
$referral=get_between($cookie[1],"referral_cookie=",";");
$code=get_between($cookie[1],"referral_code=",";");
$connect=get_between($cookie[1],"connect.sid=",";");
$nama=gen_nama();
$password=$nama[0]."".mt_rand(100,999);
echo "$email|$password = ";
$headers2 = explode("\n","Host: ecoinofficial.org\nUser-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:72.0) Gecko/20100101 Firefox/72.0\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\nAccept-Language: id,en-US;q=0.7,en;q=0.3\nDNT: 1\nConnection: keep-alive\nUpgrade-Insecure-Requests: 1\nCookie: __cfduid=$cfduid; referral_cookie=$referral; referral_code=$code; connect.sid=$connect; returning=1; view=$code\nContent-Type: application/x-www-form-urlencoded\nReferer: https://ecoinofficial.org/signup\nOrigin: https://ecoinofficial.org");
$regis=request("https://ecoinofficial.org/users/signup","firstName=$nama[0]&lastName=$nama[1]&username=$email&password=$password&confirmPassword=$password&agree=on",$headers2,"POST");
if(strrpos($regis[0],"/signup")){
	echo "Berhasil\n";
	
	
}
else{
	echo "Gagal\n";
}

function request($url, $param, $headers, $request = 'POST',$proxy=null) {
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		if($param!==null){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		}
		if($headers!==null){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		if($proxy!==null){
		//curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		}
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$execute = curl_exec($ch);
		$cookies = array();
		preg_match_all('/Set-Cookie:(?<cookie>\s{0,}.*)$/im', $execute, $cookies);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($execute, 0, $header_size);
		$body = substr($execute, $header_size);
		curl_close($ch);
		return [$body, $header, $cookies['cookie']];
}
function get_between($string, $start, $end){
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
}
function gen_nama(){
$c = curl_init();
	curl_setopt($c, CURLOPT_URL, "https://randomuser.me/api/?inc=name&nat=uk");
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($c, CURLOPT_HEADER, true);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($c);
	$f=str_replace(' ', '', get_between($response, '"first":"', '"'));
	$l=str_replace(' ', '', get_between($response, '"last":"', '"'));
	return [$f,$l];
}
