<?php
//var_dump(file_get_contents("https://metactf.com/assets/img/jake.jpg"));

$error = false;
if (isset($_GET["input_form"]) && isset($_GET["input_url"])) {
  //"https://raw.githubusercontent.com/ianare/exif-samples/master/jpg/Pentax_K10D.jpg");
  if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $_GET["input_url"])) {
    $error = "Error! An IP address was found in the provided URL. IP addresses are forbidden for security reasons.";
  } else {
    $img = file_get_contents($_GET["input_url"]);
    if (!$img) {
      $error = "Error! The file could not be downloaded.";
    } else {
      $img_b64 = "data://image/jpeg;base64," . base64_encode($img);
      $exif = exif_read_data($img_b64);
      if (!$exif) {
        $error = "Error! No exif data was found.";
      }
    }
  }
}
//file_get_contents("https://raw.githubusercontent.com/ianare/exif-samples/master/jpg/Pentax_K10D.jpg")
//var_dump();

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Free Image EXIF Viewer</title>
  </head>
  <body>
    <main role="main">
      <div class="album py-5">
        <div class="container">
          <?php
          if ($error) {
              echo '<div class="row"><div class="col-md-12"><div class="alert alert-danger" role="alert">'.$error.'</div></div></div><br>';
          }
          if ($img) {
              echo '<div class="row"><div class="col-md-8 offset-md-2 text-center"><h3 style="margin-bottom:10px">Image preview:</h3><img class="img-fluid mx-auto" style="max-width:75%" src="'.$img_b64.'"></div></div><br>';
          }
          if ($exif) {
              echo '<br><div class="row"><div class="col-md-8 offset-md-2"><h3 style="margin-bottom:10px" class="text-center">Extracted EXIF data:</h3><pre>'.json_encode($exif, JSON_PRETTY_PRINT).'</pre></div></div><br><br>';
          }
          ?>
          <div class="row">
            <div class="col-md-12 text-center">
              <h1>Free Image EXIF Viewer</h1>
              <h3>Enter the URL of a JPEG image below to view it's EXIF data:</h3>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-8 offset-md-2">
              <form method="GET" action="./">
                <input type="text" name="input_url" class="form-control" placeholder="https://example.com/test.jpg" required autofocus>
                <input type="hidden" name="input_form" value="submit">
                <br>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Load EXIF!</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>