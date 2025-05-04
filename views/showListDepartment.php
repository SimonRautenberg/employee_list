<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no,
        initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/style.css">
  <title>Alle Abteilungen</title>
</head>
<body>
<div class="wrapper">
  <h1 class="heading"><?= $view->getHeading() ?></h1>
  <?php include 'views/navigation.php' ?>
  <div class="table">
    <div class="row blue">
<!--      <div class="cell">ID</div>-->
      <div class="cell">Abteilung</div>
      <div class="cell center">Löschen</div>
      <div class="cell center">Ändern</div>
      <div class="cell">Liste MA</div>
    </div>
    <?php
    foreach ($departments as $dep) { ?>
      <div class="row">
        <!--          <div class="cell" data-title="id">--><?php //echo $emp->getId() ?><!--</div>-->
<!--        <div class="cell" data-title="Id">--><?php //echo $dep->getId() ?><!--</div>-->
        <div class="cell" data-title="name"><?php echo $dep->getName() ?></div>
        <div class="cell center" data-title="löschen">
          <a href="index.php?id=<?php echo $dep->getId() ?>&action=delete&area=department">
            <button class="delete symbol">&#10006;</button>
          </a>
        </div>
        <div class="cell center" data-title="ändern">
          <a href="index.php?id=<?php echo $dep->getId() ?>&action=showUpdate&area=department">
            <button class="symbol">&#10000;</button>
          </a>
        </div>
        <div class="cell center">
          <a href="index.php?id=<?php echo $dep->getId() ?>&action=showAllEmployees&area=employee">
            <button class="symbol">&#9776;</button>
          </a>
        </div>
      </div>
    <?php } ?>
  </div>
</body>
</html>
