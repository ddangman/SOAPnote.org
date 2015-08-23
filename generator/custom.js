<?php 

//getHtml("http://www.soapnote.org/cardiovascular/coumadin-calculator/", dirname(__FILE__) . '/test.zip');
getHtml("http://www.soapnote.org/mental-health/panic-severity/", dirname(__FILE__) . '/test.zip');


exit;

function getHtml($url, $zipFilePath) {
	$zip = new ZipArchive();
	if (file_exists($zipFilePath)) {
		unlink($zipFilePath);
	}
	$res = $zip->open($zipFilePath, ZipArchive::CREATE);
	if ($res === true) {
		$data = file_get_contents($url);
		preg_match_all('/<div class="embed_box".*?>.*?<textarea.*?>.*?src="(.*?)".*?<\/textarea>/s', $data, $matches);

		if (!isset($matches[1])) {
			exit;
		}

		$urlEmbbed = $matches[1][0];

		//$dir = dirname(__FILE__);
		$content = file_get_contents ($urlEmbbed);
		$content = preg_replace('/<div class="client_browse">.*?<h1 class="entry-title">/s', '<h1 class="entry-title">', $content);

		preg_match_all('/<link.*?href="(.*?.css)".*?>/s', $content, $matches);
		if (count($matches) > 1) {
			$fileName = trim($matches[1][0], '/');
			$fileName = substr($matches[1][0], strrpos($matches[1][0], '/')+1);
			if ($fileName == 'style.css') {
				$content = file_get_contents ($urlEmbbed);
				$content = preg_replace('/<div class="client_browse">.*?<h1 class="entry-title">/s', '<h1 class="entry-title">', $content);
				preg_match_all('/<link.*?href="(.*?.css)".*?>/s', $content, $matches);
				if (count($matches) > 1) {
					$fileName = trim($matches[1][0], '/');
					$fileName = substr($matches[1][0], strrpos($matches[1][0], '/')+1);
					//file_put_contents( $dir . "/data/" . $fileName , file_get_contents($matches[1][0]));
					$dataTemp = file_get_contents($matches[1][0]);
					$zip->addFromString($fileName, file_get_contents($matches[1][0]));
					
				}
			} else {
				//file_put_contents( $dir . "/data/" . $fileName , file_get_contents($matches[1][0]));
				$dataTemp = file_get_contents($matches[1][0]);
				$zip->addFromString($fileName, file_get_contents($matches[1][0]));
			}
			$content = str_replace("http://www.soapnote.org/wp-content/themes/customizr_childtheme/A.style.css.pagespeed.cf.e0ue58amRv.css",$fileName,$content);
		}

		preg_match_all('/<script.*?src="(.*?)"><\/script>/s', $content, $matchesScripts);

		if (count($matchesScripts) > 1) {
			foreach($matchesScripts[1] as $script) {
				$fileName = trim($script, '/');
				$fileName = substr($script, strrpos($script, '/')+1);
				//file_put_contents( $dir . "/data/" . $fileName , file_get_contents($script));
				$dataTemp = file_get_contents($script);
				$zip->addFromString($fileName, file_get_contents($script));
				$content = str_replace($script,$fileName,$content);
			}
		}

		//file_put_contents( $dir . "/data/soapnote.html" , $content);
		$zip->addFromString("soapnote.html" , $content);
	}
	$zip->close();
}

?>