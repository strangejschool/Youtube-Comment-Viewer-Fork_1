<head>
	<title>Proxy</title>
</head>

<body>
    <topnav style="background-color:grey;overflow:hidden;">
    <form action="" method="get">
        <input placeholder="http://"id="input"type="text"name="url">
        <input type="submit"value="Go">
    </form>
    <button style="display:inline;"onclick="reload()">Reset</button>
    </topnav>
    <p>Test: Proxies</p>
</body>

<?php
$siteURI = "";
$url = $_GET["url"];
$redirect = $_GET["r"];
if (isset($redirect)) {
	header("Location: ".$redirect);
}
function loadSite($site) {
    if (empty($site)==false) {
        echo '<hr><div id="site">';
        //Creates an element called $html that has all the html of the site
        $c = curl_init($site);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $html = curl_exec($c);
        curl_close($c);
        //Advanced Filtering
        //Filter elements, i.e. div, title, etc.
        $filter1 = array('/\<title/','/\<div class="_3q-XSJ2vokDQrvdG6mR__k"/');
        $filter2 = array('/\<\/title/','/\<\/div/');
        //if (isset($_GET["loadScripts"])) {array_push($filter1,'script');} //Uncomment this to disable Scripts
        $html = preg_replace($filter1,'<div style="display:none;"',$html);
        $html = preg_replace($filter2,'</div',$html);
        echo $html;
        echo '</div>';
    }
}
loadSite($url);
/*
$sites = array(
	array("Canvas","https://iowacityschools.instructure.com/?login_success=1"), 
	array("Google Drive", "https://drive.google.com/drive/my-drive"),
	array("Clever | Portal", "https://clever.com/in/iccsd/student/portal") 
);
$url2 = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$arrVal = rand(0,count($sites));

echo '$arrVal: ' . $arrVal . '<br>';
echo '$sites['.$arrVal.'][0]: ' . $sites[$arrVal][0]. '<br>';
echo '$sites['.$arrVal.'][1]: ' . $sites[$arrVal][1]. '<br>';
echo 'URL: ' . $url;

echo '<title>'.$sites[$arrVal][0].'</title><script>window.history.pushState({},"","'.$siteURI + $url2. '?r=' .$sites[$arrVal][1].'")</script>';*/
?>

<script>
var siteURL = "";
function reload() {
    window.location.href=siteURL;
}
window.addEventListener('load', function () {
    var anchors = document.getElementsByTagName("a");
    const re1 = /http/g;
    const re2 = /https/g;
    <?php echo 'var load = "'. strval(isset($_GET["loadScripts"])) .'";';?>
    for (var i=0;i<=anchors.length;i++){
        var anchor = anchors[i];
        var href = anchor.getAttribute("href");
        <?php echo 'var url = "'.$_GET["url"].'";'; ?>
        var domain = url.replace('http://','').replace('https://','').split(/[/?#]/)[0];
        var protocol = url.split(/[/?#]/)[0].slice(0,-1);
        if (load=="1") {
            load = "&loadScripts=on";
        } else if(load=="0") {
        	load = "";
        }
        var count1 = [...href.matchAll(re1)].length;
        var count2 = [...href.matchAll(re2)].length;
        var count3 = count1+count2;
        if (count3 > 0){
            anchor.setAttribute("href",siteURL+"/?url="+href);
        } else {
            anchor.setAttribute("href",siteURL+"/?url="+protocol+"://"+domain+href);
        }
    }
    history.replaceState(null,'',siteURL);
});
</script>
