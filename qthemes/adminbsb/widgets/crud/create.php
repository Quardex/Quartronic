<div class="block-header">
    <h2>Model CRUD</h2>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div style="position:absolute; z-index:10; top:11px; right:11px">
                <a href="<?=Q()->urlManager->route('index')?>" type="button" class="btn btn-info waves-effect">
                    <i class="material-icons">list</i>
                    <span>List</span>
                </a>
            </div>
            <div class="header">
                <h2>Create <?=$title?></h2>
            </div>
            <div class="body">
                <?php include('_form.php')?>
            </div>
        </div>
    </div>
</div>