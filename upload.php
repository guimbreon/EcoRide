<?php
$target_dir = "/home/asw12/public_html/projeto/uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}    

$nome = htmlspecialchars($_GET["nome"]);
$nif = htmlspecialchars($_GET["nif"]);
$tlmv = htmlspecialchars($_GET["tlmv"]);
$email = htmlspecialchars($_GET["email"]);
$pass = htmlspecialchars($_GET["pass"]);
$type = htmlspecialchars($_GET["type"]);

// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
  header("Location: scripts/insere.php?" . http_build_query($_GET));
}
// Check file size
echo 'size   ', $_FILES["fileToUpload"]["size"];
if ($_FILES["fileToUpload"]["size"] > 2000000) {
    echo "Sorry, your file is too large. Maximum allowed size is 2MB.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}


// Check if $uploadOk is set to 0 by an error
echo $_FILES["fileToUpload"]["tmp_name"],"     ", $target_file;
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if ($_FILES["fileToUpload"]["size"] > 100000) {
        // Compress the image more aggressively
        $source = $_FILES["fileToUpload"]["tmp_name"];
        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/jpg') {
            $image = imagecreatefromjpeg($source);
            imagejpeg($image, $target_file, 50); // Lower quality for higher compression
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
            imagepng($image, $target_file, 8); // Higher compression level
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
            imagegif($image, $target_file);
        } else {
            echo "Sorry, unsupported image format for compression.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been compressed and uploaded.";
            header("Location: scripts/insere.php?" . http_build_query($_GET));
        } else {
            echo "Sorry, there was an error compressing your file.";
        }
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            header("Location: scripts/insere.php?" . http_build_query($_GET));
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>