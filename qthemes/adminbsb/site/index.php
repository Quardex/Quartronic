<div class="block-header">
    <h2>DASHBOARD</h2>
</div>

<!-- Widgets -->
<?php if (\quarsintex\quartronic\qcore\QUpdater::checkVersion() > Q()->version) : ?>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="info-box bg-light-green hover-expand-effect">
            <div class="icon">
                <i class="material-icons">info</i>
            </div>
            <div class="content">
                <div class="text">NEW VERSION IS AVAILABLE</div>
                <div class="text">Please run "<b>composer update</b>" from the concole</div>
                <div class="number count-to" data-from="0" data-to="243" data-speed="1000" data-fresh-interval="20"></div>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<!-- #END# Widgets -->
<!-- CPU Usage -->
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="header">
                <div class="row clearfix">
                    <div class="col-xs-12 col-sm-6">
                        <h2>Welcome!</h2>
                    </div>
                </div>
            </div>
            <div class="body">
                <div>Welcome to Quartronic CMS!</div>
            </div>
        </div>
    </div>
</div>
<!-- #END# CPU Usage -->
