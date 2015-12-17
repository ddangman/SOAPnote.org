<?php 
 include('../../lib/main-header.php');
?>
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/categories/">Categories</a></li>
  <li><a href="/men/">Men's Health</a></li>
  <li class="active">Here</li>
</ol>

<div id="container" class="container">

<?php 
// get soapnote html and remove some specific tags ( to make html code valid
ob_start();
 include('soapnote.html');
$soapnote = ob_get_clean();

$soapnote = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>soapnote.org = calculators + decision tools + SOAP samples</title>', '', $soapnote);

$soapnote = str_replace('</head>
    <body>
', '', $soapnote);

$soapnote = str_replace('</body>
</html>
', '', $soapnote);

echo $soapnote;
?>

</div>
<?php 
 include('../../lib/main-footer.php');
?>