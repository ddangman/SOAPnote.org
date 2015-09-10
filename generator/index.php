<?php 
 include(dirname(__FILE__).'/functions.php');
 $userId = getUserId(true);
 $content = '';
 $fileName = '';
 if(isset($_GET['file'])) {
        $content = file_get_contents($_GET['file']); 
        $fileName = explode('/',$_GET['file']);
        $fileName = str_replace('.txt','',$fileName[count($fileName) - 1]);
 }
include('../lib/main-header.php');
include('../lib/generator-header.php');

 /*
$content = file_get_contents('testData.txt'); 
$regex =  get_shortcode_regex( );
preg_match_all("/$regex/is",$content,$matched);

 foreach($matched[0] as $key => $item) {
        $shortcodeName = $matched[2][$key];
        $itemText = $matched[3][$key];
        $itemAtts = shortcode_parse_atts($itemText) ;
        $itemFunc = $shortcode_tags[$shortcodeName];
        $itemContent = $matched[5][$key];
        $itemHtml = call_user_func($itemFunc, $itemAtts,$itemContent);
        
        $content = str_replace($item,$itemHtml,$content);
 }
 */
?>
<!DOCTYPE html>
<body>
        <h1>Form generator</h1>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                 <button id="open_btn" class="btn btn-primary">Open File</button>
                 <button id="save_btn" class="btn btn-primary">Save file</button>
                 <button id="download_txt_btn" class="btn btn-primary" style="display:none">Download txt file</button>
                </div>
                <div class="panel-body">
                <div class="form-group">
                  <label >Form Title:</label>
                  <input type="text" class="form-control" id="formTitle" name="formTitle" value="<?php echo $fileName ?>">
                </div>
                <div class="form-group">
                <label >Form Content:</label>
                <textarea name="content" id="content" class="form-control" rows=25><?php echo $content ?></textarea>
                </div>
                </div>
                <div class="panel-footer" style="text-align:center">
                        <button id="generate_btn" class="btn btn-primary">Generate Form</button>
                        <button id="download_form" class="btn btn-primary" style="display:none">Download form</button>
                </div>
        </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                 Form Display 
                </div>
                <div class="panel-body">
                <iframe src="" id="iframeForm" style="width:100%; height:800px"></iframe>
                </div>
        </div>
        
</div>

<script type="text/javascript">
        $(document).ready(function(){   
                $("#open_btn").click(function() {
                        $.FileDialog({
                                 accept: "text/plain",
                                 dropheight: 150,
                                 multiple: false,
                                 readAs : "Text"
                        }).on('files.bs.filedialog', function(ev) {
                                        var files_list = ev.files;
                                        $('#content').val(files_list[0].content);
                                        $('#formTitle').val(files_list[0].name.replace('.txt',''));
                                        
                                        
                                });
                });
                
                $("#download_txt_btn").click(function() { 
                        window.open("<?php echo APP_URL ?>/downloadFile.php?type=txt", "_blank");
                });
                
                $("#download_form").click(function() { 
                        window.open("<?php echo APP_URL ?>/downloadFile.php?type=zip", "_blank");
                });
                
                $("#generate_btn").click(function() { 
                        $.post('<?php echo APP_URL ?>/ajax.php', {act: "generateForm",formTitle:$('#formTitle').val(), content: $('#content').val()}, function(result){
                                if(result == '') {
                                        alert('something wrong');
                                } else {
                                        $("#iframeForm").attr('src', result);
                                        $("#download_form").show();
                                }
                        });
                        
                });
                
                $("#save_btn").click(function() { 
                        $.post('<?php echo APP_URL ?>/ajax.php', {act: "saveFile", formTitle:$('#formTitle').val(), content: $('#content').val()}, function(result){
                                if(result == '') {
                                        alert('something wrong');
                                } else {
                                        $( "#download_txt_btn" ).trigger( "click" );
                                }
                        });
                        return;
                });
                
                <?php
                if(!empty($content)) {
                        echo '$( "#generate_btn" ).trigger( "click" );';
                }
                ?>
        });
        
</script>
</body>
</html>