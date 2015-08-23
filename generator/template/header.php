<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>soapnote.org = calculators + decision tools + SOAP samples</title>
    <link rel="stylesheet" href="##DATA_URL##/html/css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="##DATA_URL##/html/js/jquery.min.js"></script>
    <script type="text/javascript" src="##DATA_URL##/html/js/jquery-ui.min.js"></script>
    <script>
        function copyToClipboard(element) {
                $("body").append("<textarea id='temp' style='position:absolute;opacity:0;'></textarea>");
                $("#temp").val($(element).val()).select();
                document.execCommand("copy");
                $("#temp").remove();
                alert('Result has been copied!');
        }
    </script>
    </head>
    <body>
        <div id="container" class="embed_client">
                <h1 class="entry-title">##FORM_TITLE##</h1>
                <div class="form-view-content ">
                        <form id="form_1" method="get" autocomplete="off">
                                <input type="hidden" class="input_permalink" name="input_permalink" value=""/>