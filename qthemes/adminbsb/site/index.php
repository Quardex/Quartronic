<?php

use quarsintex\quartronic\qcore\QUpdater;

Q()->render->registerCssFile(Q()->qRootDir.'../adminbsb/plugins/waitme/waitMe.css', self::POSITION_HEAD_BEGIN);
Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/waitme/waitMe.js');

Q()->render->registerCss('deleteAlert', "
.waitMe_container .waitMe .waitMe_text {
    margin: 10px 0 0;
}
.waitMe_container .waitMe * {
    font-size: 12px;
}
.waitMe_container .waitMe_progress.rotation > div {
    width: 20px;
    height: 20px;
    border-width: 2px;
}");

Q()->render->registerJs('deleteAlert', "
$(function () {
    initLoading();
});

//Init Loading
function initLoading() {
    $('#update-button').on('click', function () {

        var \$loading = $(this).parents('.card').waitMe({
            effect: 'rotation',
            text: 'Updating...',
            bg: 'rgba(1,1,1,0.9)',
            color: '#00BCD4'
        });

        $.ajax({
          url: \"./update\",
        }).done(function( data ) {
            location.reload();
        });
    });
}");

?>

<div class="block-header">
    <h2>DASHBOARD</h2>
</div>

<?php if (true || (QUpdater::ver2int($v = Q()->lastVersion)) > QUpdater::ver2int(Q()->version)) : ?>
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="info-box bg-light-green hover-expand-effect card">
            <div class="icon">
                <i class="material-icons">info</i>
            </div>
            <div class="content">
                <div class="text">NEW VERSION IS AVAILABLE: <b><?=$v?></b></div>
                <div class="text for-hide">Please make backup before start update</div>
                <div class="number count-to" data-from="0" data-to="243" data-speed="1000" data-fresh-interval="20"></div>
            </div>
            <div class="update-wrapper">
                <button id="update-button" type="button" class="btn bg-teal btn-block btn-lg waves-effect" data-toggle="cardloading" style="outline-width: 0px !important; user-select: auto !important;">UPDATE</button>
            </div>
         </div>
    </div>
</div>
<?php endif ?>

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
