<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pokemon Database</title>
</head>
<body>
    <?php
    include './proj3_images/1st_Generation';
        $folder_image = "./proj3_images/1st_Generation";
        $dir_handle = opendir($folder_image);
        if ($dir_handle) {
            while (($file = readdir($dir_handle)) !== false) {
                if ($file != '.' && $file != '..') {
                
                    echo '<img src="./proj3_images/1st_Generation/' . $file . '" alt="' . $file . '" />';
                }
            }
            closedir($dir_handle);
        } else {
            echo "Could not open directory.";
        }
        
   
    ?>
   
</body>
</html>
