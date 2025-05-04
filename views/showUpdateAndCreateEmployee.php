<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no,
        initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/style.css">
  <title><?= $view->getHeading() ?></title>
</head>
<body>
<div class="wrapper">
  <h1 class="heading"><?= $view->getHeading() ?></h1>
  <?php include 'views/navigation.php' ?>
  <form action="index.php" method="post">
    <div class="table">
      <div class="row green">
        <!--        <div class="cell">ID</div>-->
        <div class="cell">Vorname</div>
        <div class="cell">Nachname</div>
        <div class="cell">Abteilung</div>
        <div class="cell center">save</div>
        <div class="cell center">reset</div>
      </div>
      <div class="row">

        <!--  hidden fields zur stillen übergabe der jeweiligen Parameter -->
        <input type="hidden" name="area" value="employee">
        <input type="hidden" name="action"
               value="<?php echo (isset($employee)) ? 'update' : 'create' ?>">
        <!--        id Übergabe nur bei update-->
        <?php if (isset($employee)) { ?>
          <input type="hidden" name="id"
                 value="<?= $employee->getId() ?>">
        <?php } ?>

        <div class="cell">
          <input type="text" name="firstName"
                 value="<?php if (isset($employee)) echo $employee->getFirstname() ?>"
                 size="16" autocomplete="off" autofocus required>
        </div>
        <div class="cell">
          <input type="text" name="lastName"
                 value="<?php if (isset($employee)) echo $employee->getLastname() ?>"
                 size="16" autocomplete="off" required>
        </div>
        <div class="cell">

          <?php
          $preselected = (isset($employee)) ?  $employee->getDepartmentId() :  null;
            echo HtmlHelper::buildSelectOption($departments, 'departmentId', $preselected);
          ?>
        </div>
        <div class="cell center">
          <input class="save symbol" type="submit" value=&#10004;>
        </div>
        <div class="cell center">&nbsp;&nbsp;
          <input class="reset symbol" type="reset" value=&olarr;>
        </div>
      </div>
    </div>
  </form>
  <div class="warning">
    <span class="message"></span>
  </div>
</body>
</html>