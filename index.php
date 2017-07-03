<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Galleria</title>

    <link href='https://fonts.googleapis.com/css?family=Exo:300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
</head>
<body>

<style>

    body {
        font-family: 'Exo', sans-serif;
        font-weight: 300;
        font-size: 16px;
    }

    @media (min-width: 992px) {

        #breads {
            position: fixed;
            width: 100%;
            z-index: 1000;
        }

        #folder {
            margin-top: 58px;
            height: calc(100% - 58px);
            position: fixed;
            overflow-y: auto;
        }

        #gallery {
            margin-top: 58px;
        }

    }

    #folder ul.list-group>li {
        padding: 0;
    }
    
    #folder ul.list-group>li a {
        display: block;
        padding: 10px 15px;
    }

    #gallery img {
        max-width: 100%;
        width: auto;
        height: auto;
    }

    #gallery .filename {
        border: 1px solid #dddddd;
        padding: 10px 15px;
        margin-bottom: 20px;
    }

</style>

<?php

error_reporting(0);

$path = $_GET["path"];
if($path == "") {
    $path = ".";
}

function renderCrumbs($dir) {
    $crumbs = explode(DIRECTORY_SEPARATOR, $dir);
    foreach($crumbs as $key => $value) {
        if($value == ".") {
            $name = "localhost";
        } else {
            $name = $value;
        }
        $folder_path = implode(DIRECTORY_SEPARATOR, array_slice($crumbs, 0, $key + 1));
        if($key + 1 < count($crumbs)) {
            echo '<li><a href="?path='.$folder_path.'">'.$name.'</a></li>';
        } else {
            echo '<li>'.$name.'</li>';
        }
    }
}

function renderFolders($dir) {
    $files = scandir($dir);
    if($dir != ".") {
        $up_path = substr($dir, 0, strrpos($dir, '/'));
        echo '<li class="list-group-item"><a href="?path='.$up_path.'">Go up &uparrow;</a></li>';
    }

    $exclude_array = array(".", "..", ".DS_Store", ".idea", ".git");
    foreach($files as $file_name){
        if(!in_array($file_name, $exclude_array)) {
            $real_path = realpath($dir.DIRECTORY_SEPARATOR.$file_name);
            if(is_dir($real_path)) {
                $folder_path = $dir.DIRECTORY_SEPARATOR.$file_name;
                echo '<li class="list-group-item"><a href="?path='.$folder_path.'">'.$file_name.'</a></li>';
            }
        }
    }
}

function renderImages($dir) {
    $files = scandir($dir);
    $index = 0;
    foreach ($files as $file_name) {
        $full_path = $dir.DIRECTORY_SEPARATOR.$file_name;
        if(is_array(getimagesize($full_path))) {
            if(exif_imagetype($full_path) == IMAGETYPE_PSD) {
                $file_name_parts = explode(DIRECTORY_SEPARATOR, $full_path);
                echo '<a href="'.$full_path.'" class="filename col-xs-12">'.end($file_name_parts).'</a><br><br>';
            } else {
                echo '<a href="'.$full_path.'"><img src="'.$full_path.'"></a><br><br>';
            }
            $index++;
        }
    }

    if($index == 0) {
        echo 'No items to view!';
    }
}

?>

<nav id="breads">
    <ol id="" class="breadcrumb">
        <?php renderCrumbs($path); ?>
    </ol>
</nav>

<div id="folder" class="col-md-3">
    <ul class="list-group">
        <?php renderFolders($path); ?>
    </ul>
</div>

<div id="gallery" class="col-md-9 col-md-offset-3">
    <?php renderImages($path); ?>
</div>

</body>
</html>