String.prototype.is=function(value){if(this==value)return true;return false;}
Array.prototype.is=function(value){for(var i=0;i<this.length;i++){if(value==this[i]){return true;}}
return false;}
String.prototype.isNot=function(value){if(this==value)return false;return true;}
Array.prototype.isNot=function(value){for(var i=0;i<this.length;i++){if(value==this[i]){return false;}}
return true;}
String.prototype.isLess=function(value){if(this<value)return true;return false;}
String.prototype.isLessOrEqual=function(value){if(this<=value)return true;return false;}
String.prototype.isGreater=function(value){if(this>value)return true;return false;}
String.prototype.isGreaterOrEqual=function(value){if(this>=value)return true;return false;}
jQuery(document).ready(function($){$.generateFile=function(options){options=options||{};if(!options.script||!options.nonce||!options.postid){throw new Error("Please enter all the required config options!");}
var iframe=$('<iframe>',{width:1,height:1,frameborder:0,css:{display:'none'}}).appendTo('body');var formHTML='<form action="" method="post">'+'<input type="hidden" name="action" value="export_form" />'+'<input type="hidden" name="nonce" />'+'<input type="hidden" name="postid" />'+'</form>';setTimeout(function(){var body=(iframe.prop('contentDocument')!==undefined)?iframe.prop('contentDocument').body:iframe.prop('document').body;body=$(body);body.html(formHTML);var form=body.find('form');form.attr('action',options.script);form.find('input[name=nonce]').val(options.nonce);form.find('input[name=postid]').val(options.postid);form.submit();},50);};doExport=function(id){$.generateFile({postid:id,nonce:ajaxinfo.nonce,script:ajaxinfo.ajaxurl});};$.generatePDF=function(options){options=options||{};if(!options.script||!options.result){throw new Error("Please enter all the required config options!");}
var iframe=$('<iframe>',{width:1,height:1,frameborder:0,css:{display:'none'}}).appendTo('body');var formHTML='<form action="" method="post">'+'<input type="hidden" name="filename" />'+'<textarea name="result"></textarea>'+'</form>';setTimeout(function(){var body=(iframe.prop('contentDocument')!==undefined)?iframe.prop('contentDocument').body:iframe.prop('document').body;body=$(body);body.html(formHTML);var form=body.find('form');form.attr('action',options.script);form.find('input[name=filename]').val(options.name);form.find('textarea[name=result]').val(options.result);form.submit();},50);};doExportPDF=function(name,result){$.generatePDF({name:name,result:result,script:pdfurl});};doPrint=function(result){var html="<html><head></head><body><pre>";html+=result;html+="</pre></body></html>";var printWin=window.open('','','left=0,top=0,width=960,height=600,status=0,menubar=1');printWin.document.write(html);printWin.document.close();printWin.focus();printWin.print();printWin.close();}
$('.form-view').click(function(){$(this).siblings().removeClass('active');$(this).addClass('active');wrapper=$(this).parents('.form-wrapper');wrapper.find('.markup-view-content').removeClass('active');wrapper.find('.form-view-content').addClass('active');});$('.markup-view').click(function(){$(this).siblings().removeClass('active');$(this).addClass('active');wrapper=$(this).parents('.form-wrapper');wrapper.find('.markup-view-content').addClass('active');wrapper.find('.form-view-content').removeClass('active');});$('.markup-view a').click(function(e){e.stopPropagation();});$('.help').click(function(){$_popup=$(this).find('.help_popup');$_content=$('#content_box');if($_popup.is(':visible')){$_popup.addClass('hidden');return;}
$_popup.css('visibility','hidden').removeClass('hidden');contentRight=$_content.offset().left+$_content.outerWidth();popupRight=$(this).offset().left+$_popup.outerWidth();if(contentRight<popupRight)$_popup.addClass('help_popup_left');else $_popup.removeClass('help_popup_left');$_popup.css('visibility','visible');});$('.form-view-content .date_today').val($.datepicker.formatDate('mm/dd/yy',new Date()));$('.form-view-content .date').datepicker({dateFormat:"mm/dd/yy"});$('.soapnote_submit').click(function(){form=$(this).parent("form");result_clone=form.clone();variables={};$('.exclude,.hidden',result_clone).each(function(){$(this).remove();});$('p',result_clone).not(':first-child').not(':last-child').each(function(){$(this).append("<br/>");$(this).prepend("<br/>");});$('.date',form).each(function(){if($(this).attr("name")!='')variables[$(this).attr("name")]=$(this).val();$('input[name='+$(this).attr("name")+']',result_clone).replaceWith($(this).val());});$('input[type=text]',form).each(function(){if($(this).attr("name")!='')variables[$(this).attr("name")]=$(this).val();$('input[name='+$(this).attr("name")+']',result_clone).replaceWith($(this).val());});$('textarea',form).each(function(){if($(this).attr("name")!='')variables[$(this).attr("name")]=$(this).val();$('textarea[name='+$(this).attr("name")+']',result_clone).replaceWith($(this).val().replace(/\n/g,"<br/>"));});$('select',form).each(function(){value=$(this).val();if($(this).attr("name")!='')variables[$(this).attr("name")]=value;$('select[name='+$(this).attr("name")+']',result_clone).replaceWith(value.split("=").shift());});$('.checkbox',form).each(function(){val='';num=0;$(':checked',$(this)).each(function(){if(val=='')val+=$(this).val().split("=").shift();else val+=', '+$(this).val().split("=").shift();num_val=$(this).val().split("=").pop();num+=!isNaN(num_val)?eval(num_val):0;});if($(this).attr("name")!='')variables[$(this).attr("name")]=val+"="+num;$('.checkbox[name='+$(this).attr("name")+']',result_clone).replaceWith(val);});$('.radio',form).each(function(){val='';num=0;value=$(':checked',$(this)).val();if(typeof value=='undefined')value='';val=value.split("=").shift();num_val=value.split("=").pop();num=!isNaN(num_val)?eval(num_val):0;if($(this).attr("name")!='')variables[$(this).attr("name")]=val+"="+num;$('.radio[name='+$(this).attr("name")+']',result_clone).replaceWith(val);});$('.var',result_clone).each(function(){val=variables[$(this).attr("name")]===undefined?'':variables[$(this).attr("name")];val_array=val.split("=");$(this).replaceWith(val_array.shift());});$.each(variables,function(index,value){val_array=value.split("=");$('.calc_value',result_clone).each(function(){calc=$(this).text();regex=new RegExp('\\('+index+'\\)',"g");calc=calc.replace(regex,val_array[val_array.length-1]);$(this).html(calc);});});$('.calc',result_clone).each(function(){try{if($(this).hasClass('hidefo')){eval($(this).find('.calc_value').text());$(this).remove();}
else
$(this).html(eval($(this).find('.calc_value').text()));}
catch(err){}});result_clone.html(result_clone.html().replace(/\n/g,""));if(document.body.innerText)
final_result=result_clone.get(0).innerText;else{$('br',result_clone).replaceWith("\n");final_result=result_clone.text();}
final_result = $.trim(final_result);
final_result+="\n\n"+form.find('.input_permalink').val();$('.soapnote_result',form).val(final_result).show(); $('.soapnote_result').focus(); $('.soapnote_result').select();});});