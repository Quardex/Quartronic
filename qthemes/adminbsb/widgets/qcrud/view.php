<?php Q()->render->registerJs('deleteAlert', "
$(function () {
    $('.js-sweetalert button').on('click', function () {
        var self = this;
        swal({
            title: 'Are you sure?',
            text: 'You will not be able to recover this imaginary file!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: true,
        }, function () {
            document.location = $(self).data('link');
        });
    });
});");
?>

<div class="block-header">
    <h2>Model CRUD</h2>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header js-sweetalert">
                <div style="float:right; margin: -9px -9px 0 0">
                    <a href="<?=Q()->urlManager->route('./index')?>" type="button" class="btn btn-info waves-effect">
                        <i class="material-icons">list</i>
                        <span>List</span>
                    </a>
                </div>
                <div style="float:right; margin: -9px 10px 0 0">
                    <button class="btn btn-danger waves-effect" data-type="confirm" data-link="<?=Q()->urlManager->route('./delete',['id'=>$model->id])?>">
                        <i class="material-icons">delete</i>
                        <span>Delete</span>
                    </button>
                </div>
                <div style="float:right; margin: -9px 10px 0 0">
                    <a href="<?=Q()->urlManager->route('./edit',['id'=>$model->id])?>" type="button" class="btn btn-primary waves-effect">
                        <i class="material-icons">edit</i>
                        <span>Edit</span>
                    </a>
                </div>
                <h2><?=$title?></h2>
            </div>
            <div class="body table-responsive dataTables_wrapper">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach ($model->fieldList as $field) {
                            echo "<tr></tr><td>".mb_convert_case(str_replace('_', ' ', $field), MB_CASE_TITLE, "UTF-8")."</td><td>".$model->$field."</td></td>";
                        }
                    ?>
                    </tbody>
                </table>
             </div>
        </div>
    </div>
</div>