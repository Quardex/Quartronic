<?php

Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/jquery/jquery.min.js', self::POSITION_HEAD_BEGIN);
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/bootstrap/js/bootstrap.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/node-waves/waves.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/jquery-validation/jquery.validate.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/node-waves/waves.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/js/admin.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/js/pages/examples/sign-in.js');

Q()->render->registerCssFile(Q()->qRootDir.'../adminbsb/plugins/bootstrap/css/bootstrap.css', self::POSITION_HEAD_BEGIN);
Q()->render->registerCssFile(Q()->qRootDir.'../adminbsb/plugins/node-waves/waves.css', self::POSITION_HEAD_BEGIN);
Q()->render->registerCssFile(Q()->qRootDir.'../adminbsb/plugins/animate-css/animate.css', self::POSITION_HEAD_BEGIN);
Q()->render->registerCssFile(Q()->qRootDir.'../adminbsb/css/style.css', self::POSITION_HEAD_BEGIN);

?>
<!DOCTYPE html>
<html>

<head>
    <?=Q()->render->attachResources(self::POSITION_HEAD_BEGIN)?>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sign In | Quartronic CMS</title>
    <!-- Favicon-->
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <?=Q()->render->attachResources(self::POSITION_HEAD_END)?>
</head>

<body class="login-page" style="background-color: #111111">
<?=Q()->render->attachResources(self::POSITION_BODY_BEGIN)?>
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Quartronic CMS</a>
            <!--small>Admin BootStrap Based - Material Design</small-->
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST">
                    <div class="msg">Sign in to start your session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line<?php if ($model->errors) echo ' error'?>">
                            <input type="text" class="form-control" name="username" placeholder="Username"<?php if ($model->username) echo ' value="'.$model->username.'"';?> required autofocus>
                        </div>
                        <?php if ($model->errors): ?>
                        <label id=username-error" class="error" for="username"><?php echo $model->errors['username']?></label>
                        <?php endif ?>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <!--input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label-->
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" type="submit">SIGN IN</button>
                        </div>
                    </div>
                    <!--div class="row m-t-15 m-b--20">
                        <div class="col-xs-6">
                            <a href="sign-up.html">Register Now!</a>
                        </div>
                        <div class="col-xs-6 align-right">
                            <a href="forgot-password.html">Forgot Password?</a>
                        </div>
                    </div-->
                </form>
            </div>
        </div>
    </div>
<?=Q()->render->attachResources(self::POSITION_BODY_END)?>
</body>

</html>