<?php
session_start();
if (empty($_SESSION['userId'])){
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
		<title>線上檔案管理系統</title>

		<!-- Require JS (REQUIRED) -->
		<!-- Rename "main.default.js" to "main.js" and edit it if you need configure elFInder options or any things -->
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
		<script data-main="./main.default.js" src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.6/require.min.js"></script>

        <script>
            define('elFinderConfig', {
                // elFinder options (REQUIRED)
                // Documentation for client options:
                // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
                defaultOpts : {
                    url : 'php/connector.minimal.php' // connector URL (REQUIRED)
                    ,commandsOptions : {
                        edit : {
                            extraOptions : {
                                // set API key to enable Creative Cloud image editor
                                // see https://console.adobe.io/
                                creativeCloudApiKey : '',
                                // browsing manager URL for CKEditor, TinyMCE
                                // uses self location with the empty value
                                managerUrl : ''
                            }
                        }
                        ,quicklook : {
                            // to enable preview with Google Docs Viewer
                            googleDocsMimes : ['application/pdf', 'image/tiff', 'application/vnd.ms-office', 'application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
                        }
                    }
                    // bootCalback calls at before elFinder boot up 
                    ,bootCallback : function(fm, extraObj) {
                        // disable fullscreen cmd
                        delete fm.commands.fullscreen;
                        /* any bind functions etc. */
                        fm.bind('init', function() {
                            // any your code
                        });
                        // for example set document.title dynamically.
                        var title = document.title;
                        fm.bind('open', function() {
                            var path = '',
                                cwd  = fm.cwd();
                            if (cwd) {
                                path = fm.path(cwd.hash) || null;
                            }
                            document.title = path? path + ':' + title : title;
                        }).bind('destroy', function() {
                            document.title = title;
                        });
                    }
                    ,height : '100%' // you can use '100%' to fit window height
                    ,resizable: false
                    ,lang : 'zh_TW'

                },
                managers : {
                    // 'DOM Element ID': { /* elFinder options of this DOM Element */ }
                    'elfinder': {}
                }
            });
        </script>
	</head>
    <body style="margin:0; padding:0;">
        <div id="elfinder" style="border: 0; width: 100%; height: 100%"></div>
    </body>
    <script>
        let userId = "<?php echo $_SESSION['userId'];?>";
        setInterval(() => addBtn() , 1000)
        function addBtn(){
            $(".loginUser").html(userId);
            if($(".loginDiv").length != 0){
              return; 
            }
            const loginIconImg = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAgCAYAAAAbifjMAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3wwODgkfXMcF3gAAA0ZJREFUSMftlVFo1VUYwH/nf+/uvW4O3NA23R0uHdPV1qJ6CwwiCgKfJNCXIiuxKAipoIdi9Bb1WA8rCcKXJMQgjIweTCgUdWipTMttuEWhbd61sf3/5/u+c3rYdrcbblhvQQcO39P3+w7nfN/vOP7FunjwlXdDsANmOp3/J4mXP3n15xCss7ChlZHRSzQnBa0C+vv7YwiBxW1m1bi3J5IQKKxvYe3dnQQLxJGfEMmoAsyMcrkMgHOuJsZ4kaaHHgYc2cwEJhkxBkSUZDkA4ER6nEjEOVfdZkYwI62MI7NTBPHEYIhktQDnHH9kN/mq8kUNQNUTTDD1BPUEzYgh4H1KfucHj8VggQt6isFbP9DR1oGqcXTsMLva9szD1RNVCeIx9RAdMQRUUvKmxlOP7kKCoaZoUMSEM+k5Phs+xJ6tTyMLlU0yoqa0bLjBL1HxPiOvYngVJtNJvAliAji679nOYHqer0eP0VEPpor5jMaGWzSvm6AuN8e0pCSqRmaeQlKkmCtRzJXIuRxnzw1SGf+TJ7fsRCQlaIZJRmUyx+joejJfN/+MKsrhLz9HxTA1yuU2NBhT49M8f99+nHN4nxFUCJJhIkxkeQgBEU9y8u3T7vt3zrru3/p4+d4DTP06w8T1Ci/0vbj0CpLO34EKpguwEFD1S42kqjjniBbZf/9LNc3kJZtPlowgHucCMa4A2Nu3r1p5ESAyX7W+qZ2ggmazEH/HTHEDAwNxaGiIsbGxmv5fHndvGaWhsZFiqYFCqZ66QpHpmQrXrwzedHcyhR8/136XmW41k24z7QrBykm+sNskm3L/+6B2nCORE+nxavKd+KDmBN/OHWPKV3D1rqYX/u4DcEs+WAQMNZ9nU8tGmmwdh4YPYmrV+fiw/YGVfQDwxPuP/Ni6qZXerl40KNs7tyELXjhy5OjqPnj8vR2fbt64ube3q4dKWmFWZhETfBAa8mtRNUT8yj745vWTz1wZvnrp9IUzJC5HMVeilF9DKbeGzDwqtroPAL5761TPjjdcnEvnUFWuXRvBNGBqBAur+qB6iVtubGPEXSWJOV578M2aXtDLH9X4wLl81QfJ8nF+tnsf0Wr/hNv5IIi/vQ+SJPkP+uAvyoJNfRlNM5QAAAAASUVORK5CYII=";
            $(".ui-state-default.elfinder-button").parent().last().first().after('<div class="ui-widget-content ui-corner-all elfinder-buttonset loginDiv"><div class="elfinder-button" title="登出"><div><span class="loginIcon"></span> <span class="loginUser">ID</span></div></div>');
            $(".loginDiv").next().remove();
            $(".loginDiv").click(function(){
                loginFn();
            });
            $(".loginDiv").css({
                "background-color": "#eeeeee",
            	"position": "relative",
            	"top": "50%",
            	"margin": "0 auto +transform(translateY(- 50%))",
            	"color": "#000000"
            });
            $(".loginIcon").css({
                "width": "16px",
                "height": "16px",
                "display": "inline-block",
                "background": "url("+loginIconImg+")",
                "background-position": "0 0"
            });
            $(".loginDiv").hover(function(){
                    $(".loginDiv").css({
                        "background-color":"#e5e5e5",
                        "color": "#0000C6"
                    });
                    $(".loginIcon").css("background-position", "0 -16px");
                },function(){
                    $(".loginDiv").css({
                        "background-color":"#eeeeee",
                        "color": "#000000"
                    });
                    $(".loginIcon").css("background-position", "0 0");
            });
        }
        function loginFn(){
            const jsonData = { "do": "logout" };
            $.post("api.php", JSON.stringify(jsonData),
                function(result){
                    if(result.success){
                        alert("登出成功");
                        window.location.href = "login.php";
                    }else{
                        alert("操作失敗，請重試。");
                    }
            }, "json");
            return false;
        }
    </script>
</html>