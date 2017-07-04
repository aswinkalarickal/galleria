<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Galleria</title>

    <link href='https://fonts.googleapis.com/css?family=Exo:300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

    #gallery #settings {
        margin-bottom: 10px;
        text-align: right;
    }

    #gallery #images {
        margin-top: 40px;
    }

    #gallery #images .image {
        margin-bottom: 15px;
    }

    #gallery #images .image img {
        height: auto;
        width: auto;
        max-width: 100%;
    }

    #gallery #images a.col-md-4 .image {
        height: 150px;
        background-size: cover;
        margin-bottom: 30px;
        overflow: hidden;
    }

    #gallery #images a.col-md-4 .image.filename {
        padding: 15px;
        background-color: #f0f0f0;
    }
    
    @media (max-width: 991px) {

        #gallery #settings {
            display: none;
        }

        #gallery #images {
            margin-top: 0;
        }

        #gallery #images a.col-md-4 .image {
            height: auto;
            overflow: auto;
            margin-bottom: 0px;
        }

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
                echo '<a href="'.$full_path.'" class="col-md-4">
                        <div class="image filename">
                            <i class="fa fa-file-image-o" aria-hidden="true"></i> '.end($file_name_parts).'
                        </div>
                    </a>';
            } else {
                echo '<a href="'.$full_path.'" class="col-md-4">
                        <div class="image">
                            <img src="'.$full_path.'">
                        </div>
                    </a>';
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
    <div id="settings" class="col-md-12">
        <div class="view-type col-md-3 col-md-offset-9">
            <div class="btn-group" role="group">
                <button type="button" id="setViewFullSize" class="btn btn-default set-view">
                    <i class="fa fa-square" aria-hidden="true"></i>
                </button>
                <button type="button" id="setViewThumbnail" class="btn btn-default set-view">
                    <i class="fa fa-th-large" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
    <div id="images">
        <?php renderImages($path); ?>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>

    $(document).ready(function() {

        $('.set-view').on('click', function(e) {
            if(this.id === 'setViewThumbnail') {
                $('#gallery #images a').addClass('col-md-4');
            } else {
                $('#gallery #images a').removeClass('col-md-4');
            }
        });

    });
</script>

</body>
</html>