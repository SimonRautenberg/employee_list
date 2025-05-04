<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Foldit&family=IBM+Plex+Mono&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <title>Fehlermeldungen</title>
  <style>
    body {
        font-family: 'IBM Plex Mono', Arial, sans-serif;
    }

    h1 {
        font-family: 'Foldit', Arial, sans-serif;
        font-size: 4rem;
        letter-spacing: .3rem;
    }
  </style>
</head>
<body>
<h1><?= $view->getHeading() ?></h1>
<span class="error"><?= $e->getMessage() ?> 🤷</span><br/>
<?php
echo "<pre>";
print_r($e);
echo "</pre>";
?>
</body>
</html>
