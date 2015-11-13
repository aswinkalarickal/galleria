<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Galleria :: localhost</title>

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

    #gallery img {
        max-width: 100%;
        width: auto;
        height: auto;
    }

</style>

<nav id="breads">
    <ol id="" class="breadcrumb">
        <?php
        error_reporting(0);
        $path = $_GET["path"];
        if($path == "") {
            $path = ".";
        }
        $crumbs = explode("/", $path);
        $folder_path = array();
        foreach($crumbs as $folder_name) {
            if($folder_name == ".") {
                $name = "localhost";
            } else {
                $name = $folder_name;
            }
            array_push($folder_path, $folder_name);
            echo '<li><a href="?path='.implode("/", $folder_path).'">'.$name.'</a></li>';
        }
        ?>
    </ol>
</nav>

<div id="folder" class="col-md-3">
    <ul class="list-group">
        <?php
        $path = $_GET["path"];
        if($path == "") {
            $path = ".";
        }

        function getDirContents($dir) {
            $files = scandir($dir);
            if($dir != ".") {
                $crumbs = explode("/", $dir);
                array_pop($crumbs);
                echo '<li class="list-group-item"><a href="?path=' . implode("/", $crumbs) . '">Go up &uparrow;</a></li>';
            }

            foreach($files as $key => $value){
                $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
                $exclude_array = array(".", "..", ".idea", ".git");
                if(is_dir($path) && !in_array($value, $exclude_array)) {
                    $filepath = $dir.DIRECTORY_SEPARATOR.$value;
                    echo '<li class="list-group-item"><a href="?path='.$filepath.'">'.$value.'</a></li>';
                }
            }
        }

        getDirContents($path);
        ?>
    </ul>
</div>

<div id="gallery" class="col-md-9 col-md-offset-3">
    <?php
    $dir = $_GET['path'];
    if($dir == '') {
        $dir = '.';
    }

    $folders = scandir($dir);

    $index = 0;
    foreach ($folders as $path) {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        if(in_array($extension, array("jpg", "JPG", "jpeg", "JPEG", "png", "PNG"))) {
            $fullpath = $dir.'/'.$path;
            echo '<a href="'.$fullpath.'"><img src="'.$fullpath.'"></a><br><br>';
            $index++;
        }
    }

    if($index == 0) {
        echo 'No items to view!';
    }
    ?>
</div>

</body>
</html>