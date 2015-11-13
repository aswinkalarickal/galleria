<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Galleria :: localhost</title>

    <link href='https://fonts.googleapis.com/css?family=Exo:300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.0.9/themes/default/style.min.css" />

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.0.9/jstree.min.js"></script>
</head>
<body>

<style>

    body {
        font-family: 'Exo', sans-serif;
        font-weight: 300;
        font-size: 16px;
        margin: 0;
        padding: 0;
    }

    #breadcrumbs {
        height: 50px;
        line-height: 50px;
        padding: 0px 10px;
        width: 100%;
        position: fixed;
        background-color: black;
        color: white;
    }

    #folder {
        width: 25%;
        height: calc(100% - 50px);
        top: 50px;
        overflow: auto;
        float: left;
        position: fixed;
        padding: 10px;
    }

    #gallery {
        width: 75%;
        margin-top: 50px;
        min-height: 100px;
        height: auto;
        float: right;
        padding: 10px;
    }

    #gallery img {
        max-width: 100%;
        width: auto;
        height: auto;
    }

</style>
<div id="breadcrumbs">
    <?php
    error_reporting(0);
    $path = $_GET['path'];
    if($path == '') {
        $path = ".";
    }
    echo $path
    ?>
</div>

<div id="folder">
    <?php
    $root = '.';

    function getDirContents($dir, $rootpath) {
        echo '<ul>';
        $files = scandir($dir);

        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
            $exclude_array = array(".", "..", ".idea", ".git");
            if(is_dir($path) && !in_array($value, $exclude_array)) {
                $filepath = $rootpath.DIRECTORY_SEPARATOR.$value;
                echo '<li><a href="?path='.$filepath.'">'.$value.'</a>';
                getDirContents($path, $filepath);
                echo '</li>';
            } else {

            }
        }
        echo '</ul>';
    }

    echo '<ul><li><a href="?path=.">localhost</a>';
    getDirContents($root, '.');
    echo '</li></ul>';
    ?>
</div>

<div id="gallery">
    <?php
    $dir = $_GET['path'];
    if($dir == '') {
        $dir = '.';
    }

    $iter = scandir($dir);

    $index = 0;
    foreach ($iter as $path) {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        if(in_array($extension, array("jpg", "JPG", "jpeg", "JPEG", "png", "PNG"))) {
            $fullpath = $dir.'/'.$path;
            echo '<a href="'.$fullpath.'"><img src="'.$fullpath.'"></a><br>';
            $index++;
        }
    }

    if($index == 0) {
        echo 'Empty directory';
    }
    ?>
</div>

<script>
    $(document).ready(function() {
        $("#folder").bind("loaded.jstree", function(event, data) {
            data.instance.open_all();
        });
        $("#folder").jstree()
            .bind("select_node.jstree", function (e, data) {
                $('#folder').jstree('save_state');
            }).on("activate_node.jstree", function(e,data){
                window.location.href = data.node.a_attr.href;
            });
    });
</script>

</body>
</html>