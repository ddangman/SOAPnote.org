<?php 
define('BASE_URL', 'http://www.soapnote.org/generator/github/github.php');
define('BASE_GIT_PATH', 'https://api.github.com/repos/soapnote/SOAPnote-site/contents/');

if(empty($_GET['path'])) {
        
        $path = 'generator/txt';
        $url = BASE_GIT_PATH . '/' . $path;
        $parentPath = '';
} else {
        $path = trim($_GET['path']);
        if($path == 'generator/txt') {
                $url = BASE_GIT_PATH . '/' . $path;
                $parentPath = '';
        } else {
                $url = BASE_GIT_PATH . '/' . $path;
                $parentPathPart = explode('/',$path);
                $parentPath = '';
                for($i=0; $i < count($parentPathPart) - 1; $i++) {
                        if(!$parentPath) {
                                $parentPath .= $parentPathPart[$i];
                        } else {
                                $parentPath .= '/' . $parentPathPart[$i];
                        }
                }
                
        }
        
}
$url = $url . '?access_token=f07bd44ef52ee4a3f8cf785d8f98f7d12c46df90';
$ch = curl_init($url);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt ($ch, CURLOPT_REFERER, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Awesome-Octocat-App'));
$content = curl_exec ($ch);

$json_git_list = json_decode($content,true);


$dirList = array();
$fileList = array();

if(!empty($json_git_list['download_url'])) {
        $fileContent = file_get_contents($json_git_list['download_url']);
} else {
        $fileContent = '';
        foreach ($json_git_list as $gitObj) {
                if($gitObj['type'] == 'dir') {
                        $dirList[] = $gitObj;
                } else {
                        $fileList[] = $gitObj;
                }
        }
}

?>

<html lang="en" class="">
    <head>
        <link rel="stylesheet" media="all" href="https://assets-cdn.github.com/assets/github-2a9ef34e65be9a62a9e257383844e3d635d8416ec7b4d40f4c72cbcac03d4cb7.css" crossorigin="anonymous">
        <link rel="stylesheet" media="all" href="https://assets-cdn.github.com/assets/github2-2636fb226b1998b444ae391afdb526dd51ffc15c99c699d9bb1e2d4086442e30.css" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <?php if($fileContent) { ?>
        <script type="text/javascript">
			$(document).ready(function(){   
                                var phpData = <?php echo json_encode(array('fileContent' => $fileContent)); ?>;
                                $(window.self.top.document).find('#content').val(phpData.fileContent);
                                $(window.self.top.document).find('#archived_file_frame').attr('src','<?php echo BASE_URL .'?path=' . $parentPath ; ?>')
                                $(window.self.top.document).find('#myModal .close').trigger('click');
                        });
        </script>
        <?php } ?>
    </head>
    <body>
        <div class="main-content" role="main">
            <div itemtype="http://schema.org/WebPage" itemscope="">
                <div class="container" style="width:100%">
                    <div class="repository-with-sidebar repo-container new-discussion-timeline">
                        <div data-pjax-container="" class="repository-content context-loader-container" id="js-repo-pjax-container" style="width:100%">
                            <div class="file-wrap" style="margin-top:20px;">
                                <table data-pjax="" class="files js-navigation-container js-active-navigation-container">
                                        <?php if($parentPath) { ?>
                                        <tr class="up-tree js-navigation-item">
                                            <td></td>
                                            <td><a title="Go to parent directory" rel="nofollow" class="js-navigation-open" href="<?php echo BASE_URL .'?path=' . $parentPath ; ?>">..</a></td>
                                        </tr>
                                        <?php } ?>
                                        <?php foreach ($dirList as $dir) {
                                                
                                         ?>
                                        <tr class="js-navigation-item">
                                            <td class="icon">
                                                <span class="octicon octicon-file-directory"></span>
                                                <img width="16" height="16" src="https://assets-cdn.github.com/images/spinners/octocat-spinner-32.gif" class="spinner" alt="">
                                            </td>
                                            <td class="content">
                                                <span class="css-truncate css-truncate-target"><a title="<?php echo $dir['name']; ?>" id="" class="js-directory-link js-navigation-open" href="<?php echo BASE_URL .'?path=' . $dir['path'] ; ?>"><?php echo $dir['name']; ?></a></span>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                        <?php foreach ($fileList as $dir) {
                                                
                                         ?>
                                        <tr class="js-navigation-item">
                                            <td class="icon">
                                               <span class="octicon octicon-file-text"></span>
                                                <img width="16" height="16" src="https://assets-cdn.github.com/images/spinners/octocat-spinner-32.gif" class="spinner" alt="">
                                            </td>
                                            <td class="content">
                                                <span class="css-truncate css-truncate-target"><a title="<?php echo $dir['name']; ?>" id="" class="js-directory-link js-navigation-open" href="http://www.soapnote.org/generator/github/github.php?path=<?php echo $dir['path'] ?>"><?php echo $dir['name']; ?></a></span>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>