<?php

Q()->render->registerJsFile(Q()->rootDir.'../adminbsb/plugins/jquery/jquery.min.js', self::POSITION_HEAD_BEGIN);
Q()->render->registerJsFile(Q()->rootDir.'../adminbsb/plugins/bootstrap/js/bootstrap.js');
Q()->render->registerJsFile(Q()->rootDir.'../adminbsb/plugins/node-waves/waves.js');

Q()->render->registerCssFile(Q()->rootDir.'../adminbsb/plugins/bootstrap/css/bootstrap.css', self::POSITION_HEAD_BEGIN);
Q()->render->registerCssFile(Q()->rootDir.'../adminbsb/plugins/node-waves/waves.css', self::POSITION_HEAD_BEGIN);
Q()->render->registerCssFile(Q()->rootDir.'../adminbsb/css/style.css', self::POSITION_HEAD_BEGIN);

?>
<!DOCTYPE html>
<html>

<head>
    <?=Q()->render->attachResources(self::POSITION_HEAD_BEGIN)?>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>500 | Quartronic CMS</title>
    <!-- Favicon-->
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <?=Q()->render->attachResources(self::POSITION_HEAD_END)?>
</head>

<body class="five-zero-zero">
<?=Q()->render->attachResources(self::POSITION_BODY_BEGIN)?>
    <div class="five-zero-zero-container">
        <div class="error-code">500</div>
        <div class="error-message">Internal Server Error</div>
        <div class="button-place">
            <a href="<?=Q()->urlManager->route('/')?>" class="btn btn-default btn-lg waves-effect">GO TO HOMEPAGE</a>
        </div>
    </div>
<?=Q()->render->attachResources(self::POSITION_BODY_END)?>
</body>

</html>