<?php

Q()->render->registerCssFile(Q()->qRootDir.'../adminbsb/plugins/bootstrap/css/bootstrap.css', self::POSITION_HEAD_BEGIN);
Q()->render->registerCssFile(Q()->qRootDir.'../adminbsb/plugins/node-waves/waves.css', self::POSITION_HEAD_BEGIN);
Q()->render->registerCssFile(Q()->qRootDir.'../adminbsb/plugins/animate-css/animate.css', self::POSITION_HEAD_BEGIN);
Q()->render->registerCssFile(Q()->qRootDir.'../adminbsb/plugins/morrisjs/morris.css', self::POSITION_HEAD_BEGIN);
Q()->render->registerCssFile(Q()->qRootDir.'../adminbsb/css/themes/all-themes.css', self::POSITION_HEAD_BEGIN);
Q()->render->registerCssFile(Q()->qRootDir.'../adminbsb/css/materialize.css', self::POSITION_HEAD_BEGIN);
Q()->render->registerCssFile(Q()->qRootDir.'../adminbsb/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css', self::POSITION_HEAD_BEGIN);
Q()->render->registerCssFile(Q()->qRootDir.'../adminbsb/plugins/sweetalert/sweetalert.css', self::POSITION_HEAD_BEGIN);
Q()->render->registerCssFile(Q()->qRootDir.'../adminbsb/css/style.css', self::POSITION_HEAD_BEGIN);

Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/jquery/jquery.min.js', self::POSITION_HEAD_BEGIN);
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/bootstrap/js/bootstrap.js', self::POSITION_HEAD_END);
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/jquery-slimscroll/jquery.slimscroll.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/node-waves/waves.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/jquery-countto/jquery.countTo.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/raphael/raphael.min.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/morrisjs/morris.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/chartjs/Chart.bundle.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/flot-charts/jquery.flot.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/flot-charts/jquery.flot.resize.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/flot-charts/jquery.flot.pie.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/flot-charts/jquery.flot.categories.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/flot-charts/jquery.flot.time.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/jquery-sparkline/jquery.sparkline.js');
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/sweetalert/sweetalert.min.js');

Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/js/admin.js');
//Q()->render->registerJsFile(Q()->rootDir.'../adminbsb/js/demo.js');

Q()->render->registerDir(Q()->qRootDir.'../adminbsb/images', 'images');
Q()->render->registerDir(Q()->qRootDir.'../adminbsb/plugins/bootstrap/fonts', 'fonts');

Q()->render->registerCssFile(Q()->qRootDir.'qthemes/adminbsb/assets/css/qstyle.css');
Q()->render->registerDir(Q()->qRootDir.'qthemes/adminbsb/assets/images', 'qimages');

Q()->render->registerJs('logout', "
$(function () {
    $('#logout').on('click', function () {
        $.post($(this).attr('href')).always(function() {
          location.reload();
        });
        return false;
    });
});");
?>
<!DOCTYPE html>
<html>

<head>
<?=Q()->render->attachResources(self::POSITION_HEAD_BEGIN)?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Quartronic CMS</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

<?=Q()->render->attachResources(self::POSITION_HEAD_END)?>
</head>

<body class="theme-cyan">
<?=Q()->render->attachResources(self::POSITION_BODY_BEGIN)?>
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <nav class="navbar" style="background-color: rgb(1, 1, 1)">
        <div class="container-fluid">
            <div class="navbar-header">
                <a style="display: none" href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="<?=Q()->urlManager->route('/')?>">Quartronic CMS</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right" style="display:none">
                    <!-- Call Search -->
                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                    <!-- #END# Call Search -->
                    <!-- Notifications -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">notifications</i>
                            <span class="label-count">7</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">NOTIFICATIONS</li>
                            <li class="body">
                                <ul class="menu">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">person_add</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>12 new members joined</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 14 mins ago
</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-cyan">
                                                <i class="material-icons">add_shopping_cart</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>4 sales made</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 22 mins ago
</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-red">
                                                <i class="material-icons">delete_forever</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>Nancy Doe</b> deleted account</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 3 hours ago
</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-orange">
                                                <i class="material-icons">mode_edit</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>Nancy</b> changed name</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 2 hours ago
</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-blue-grey">
                                                <i class="material-icons">comment</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>John</b> commented your post</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 4 hours ago
</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">cached</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>John</b> updated status</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 3 hours ago
</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-purple">
                                                <i class="material-icons">settings</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>Settings updated</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> Yesterday
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="javascript:void(0);">View All Notifications</a>
                            </li>
                        </ul>
                    </li>
                    <!-- #END# Notifications -->
                    <!-- Tasks -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">flag</i>
                            <span class="label-count">9</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">TASKS</li>
                            <li class="body">
                                <ul class="menu tasks">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
Footer display issue
<small>32%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 32%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
Make new buttons
<small>45%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-cyan" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
Create new dashboard
<small>54%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 54%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
Solve transition issue
<small>65%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 65%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
Answer GitHub questions
<small>92%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 92%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="javascript:void(0);">View All Tasks</a>
                            </li>
                        </ul>
                    </li>
                    <!-- #END# Tasks -->
                    <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="https://i1.sndcdn.com/avatars-000160000025-vq31vm-t500x500.jpg" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="email">&nbsp;</div>
                    <?php if (!empty(Q()->user->username)): ?>
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=Q()->user->username?></div>
                    <?php endif; ?>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <!--li><a href="javascript:void(0);"><i class="material-icons">person</i>Profile</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="javascript:void(0);"><i class="material-icons">group</i>Followers</a></li>
                            <li><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i>Sales</a></li>
                            <li><a href="javascript:void(0);"><i class="material-icons">favorite</i>Likes</a></li>
                            <li-- role="separator" class="divider"></li-->
                            <li><a id="logout" href="<?=str_replace(Q()->router->subWebPath, '', Q()->urlManager->route('/site/logout'))?>"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MENU</li>
                    <?php if (Q()->router->subWebPath) : ?>
                    <li>
                        <a href="<?=str_replace(Q()->router->subWebPath, '', Q()->urlManager->route('/'))?>">
                            <i class="material-icons">arrow_back</i>
                            <span>Back</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li><!-- class="active"-->
                        <a href="<?=Q()->urlManager->route('/')?>">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?=Q()->urlManager->route('/user')?>">
                            <i class="material-icons">perm_identity</i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?=Q()->urlManager->route('/group')?>">
                            <i class="material-icons">supervisor_account</i>
                            <span>Groups</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?=Q()->urlManager->route('/role')?>">
                            <i class="material-icons">recent_actors</i>
                            <span>Roles</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?=Q()->urlManager->route('/section')?>">
                            <i class="material-icons">account_tree</i>
                            <span>Sections</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?=Q()->urlManager->route('/crud')?>">
                            <i class="material-icons">vertical_split</i>
                            <span>Cruds</span>
                        </a>
                        <ul class="ml-menu" style="display: block;">
                            <?php foreach (\quarsintex\quartronic\qcore\QCrud::loadConfig() as $alias => $config) : ?>
                            <?php $link = Q()->urlManager->route('/'.$alias); ?>
                            <li>
                                <a href="<?=$link.'/settings'?>" class="settings">
                                    <i class="material-icons">settings_applications</i>
                                </a>
                                <a href="<?=$link?>" class="sublink">
                                    <i class="material-icons"><?=(!empty($config['icon']) ? $config['icon'] : 'toc')?></i>
                                    <span><?=!empty($config['name']) ? $config['name'] : ucfirst($alias)?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li>
                        <a href="<?=Q()->urlManager->route('/storage')?>">
                            <i class="material-icons">sd_storage</i>
                            <span>Storage</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2019 - 2020 <b>Quartronic CMS</b> v.<?=Q()->version?>
                </div>
                <div class="version copyright">
                    Designed by <a target="_blank" href="https://gurayyarar.github.io/AdminBSBMaterialDesign">AdminBSB - Material Design</a>
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        <aside id="rightsidebar" class="right-sidebar">
            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                <li role="presentation" class="active"><a href="#skins" data-toggle="tab">SKINS</a></li>
                <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                    <ul class="demo-choose-skin">
                        <li data-theme="red" class="active">
                            <div class="red"></div>
                            <span>Red</span>
                        </li>
                        <li data-theme="pink">
                            <div class="pink"></div>
                            <span>Pink</span>
                        </li>
                        <li data-theme="purple">
                            <div class="purple"></div>
                            <span>Purple</span>
                        </li>
                        <li data-theme="deep-purple">
                            <div class="deep-purple"></div>
                            <span>Deep Purple</span>
                        </li>
                        <li data-theme="indigo">
                            <div class="indigo"></div>
                            <span>Indigo</span>
                        </li>
                        <li data-theme="blue">
                            <div class="blue"></div>
                            <span>Blue</span>
                        </li>
                        <li data-theme="light-blue">
                            <div class="light-blue"></div>
                            <span>Light Blue</span>
                        </li>
                        <li data-theme="cyan">
                            <div class="cyan"></div>
                            <span>Cyan</span>
                        </li>
                        <li data-theme="teal">
                            <div class="teal"></div>
                            <span>Teal</span>
                        </li>
                        <li data-theme="green">
                            <div class="green"></div>
                            <span>Green</span>
                        </li>
                        <li data-theme="light-green">
                            <div class="light-green"></div>
                            <span>Light Green</span>
                        </li>
                        <li data-theme="lime">
                            <div class="lime"></div>
                            <span>Lime</span>
                        </li>
                        <li data-theme="yellow">
                            <div class="yellow"></div>
                            <span>Yellow</span>
                        </li>
                        <li data-theme="amber">
                            <div class="amber"></div>
                            <span>Amber</span>
                        </li>
                        <li data-theme="orange">
                            <div class="orange"></div>
                            <span>Orange</span>
                        </li>
                        <li data-theme="deep-orange">
                            <div class="deep-orange"></div>
                            <span>Deep Orange</span>
                        </li>
                        <li data-theme="brown">
                            <div class="brown"></div>
                            <span>Brown</span>
                        </li>
                        <li data-theme="grey">
                            <div class="grey"></div>
                            <span>Grey</span>
                        </li>
                        <li data-theme="blue-grey">
                            <div class="blue-grey"></div>
                            <span>Blue Grey</span>
                        </li>
                        <li data-theme="black">
                            <div class="black"></div>
                            <span>Black</span>
                        </li>
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="settings">
                    <div class="demo-settings">
                        <p>GENERAL SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Report Panel Usage</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Email Redirect</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>SYSTEM SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Notifications</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Auto Updates</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>ACCOUNT SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Offline</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Location Permission</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>
        <!-- #END# Right Sidebar -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <?= $this->content ?>
        </div>
    </section>

<?=Q()->render->attachResources(self::POSITION_BODY_END)?>
</body>

</html>
