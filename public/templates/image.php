<?php declare(strict_types=1) ?>

<?php require_once __DIR__ . '/../index.php' ?>
<!doctype html>
<html lang="en">
<head>
    <title>Gallery - <?=$this->title?></title>
    <?php include_once __DIR__ . '/../common/meta.html' ?>
    <link rel="stylesheet" href="../css/home.css"/>
    <link rel="stylesheet" href="../css/masthead.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php if ($this->type === 'self') {
        echo '<script defer src="../js/image-form.js"></script>';
    } ?>
</head>
<body class="header-top-padding">
<?php include_once __DIR__ . '/../common/auto-header.php' ?>
<main class="masthead d-flex">
    <div class="container align-self-center">
        <?php if ($this->images !== '[]') {
            include_once __DIR__ . '/../common/image-body.php';
        }
        if ($this->type === 'self') {
            include_once __DIR__ . '/../common/image-form.php';
        } ?>
    </div>
</main>
<?php include_once __DIR__ . '/../common/footer.html' ?>
</body>
</html>