<input type="button" class="soapnote_submit exclude" value="Submit"/>
<div class="soapnote_result_container exclude">
Result - Copy or Print this text:
<!-- it doesn't really copy (except chrome) and print isn't really needed
<div class="result_buttons">
<a  onclick="copyToClipboard('.soapnote_result')" rel="nofollow" title="Copy to clipboard" href="javascript:void(0)" class="button copy-button exclude">Copy</a>
<a rel="nofollow" title="Print" href="javascript:void(0)" class="button print-button exclude">Print</a>
</div>
-->
<textarea class="soapnote_result exclude" cols="80" rows="12" onclick="this.focus();this.select();" readonly="readonly"></textarea>
</div>
</form>
##CONDITIONAL_CONTENT##
<script type="text/javascript">

jQuery(document).ready(function($){
$(".print-button").click(function(){
                doPrint($(this).parents('.soapnote_result_container').find('.soapnote_result').val().replace(/\n/g,"\r\n"));
        });
});
</script>
<script type="text/javascript" src="##DATA_URL##/html/js/form.js"></script>
</div>
</div>
</body>
</html>
