<?php Q()->render->registerJs('deleteAlert', "
$(function () {
    $('.js-sweetalert a.swal').on('click', function () {
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
        return false;
    });
});");
?>
<div class="block-header">
    <h2>Model CRUD</h2>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card" style="position: relative">
            <div style="position:absolute; z-index:1; top:11px; right:11px">
                <a href="<?=Q()->urlManager->route('add')?>" type="button" class="btn btn-success waves-effect">
                    <i class="material-icons">add_circle</i>
                    <span>Add</span>
                </a>
            </div>
            <div class="header">
                <h2><?=$title?></h2>
            </div>
            <div class="body table-responsive dataTables_wrapper">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <?php
                        foreach ($crud->modelFields as $field) {
                            if ($crud->isIgnoredFields($field)) continue;
                            echo "<th>".mb_convert_case(str_replace('_', ' ', $field), MB_CASE_TITLE, "UTF-8")."</th>";
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i = 0;
                        foreach ($crud->list as $model) {
                            foreach ($model->fields as $fieldName => $item) {
                                if ($crud->isIgnoredFields($fieldName)) continue;
                                echo '<td>'.mb_strimwidth(strip_tags($item), 0, 150, "...").'</td>';
                            }
                            echo '<td class="js-sweetalert" style="width:100px">'.
                                '<a href="'.Q()->urlManager->route('view', $model->primaryKey).'"><i class="material-icons">visibility</i></a>&nbsp;'.
                                '<a href="'.Q()->urlManager->route('edit', $model->primaryKey).'"><i class="material-icons">edit</i></a>&nbsp;'.
                                '<a class="swal" href="'.Q()->urlManager->route('delete', $model->primaryKey).'" data-type="confirm" data-link="'.Q()->urlManager->route('delete', $model->primaryKey).'"><i class="material-icons">delete</i></a>'.
                                '</td>';
                            echo '</tr>';
                        }
                    ?>
                    </tbody>
                </table>
                <div class="dataTables_info" id="DataTables_Table_1_info" role="status" aria-live="polite">Showing <?=$countAll ? $crud->offset+1 : 0?> to <?=count($crud->list)?> of <?=$countAll?> entries</div>
                <?php $this->widget('QPagination', [
                    'total' => $countAll,
                    'currentPage' => $crud->page,
                    'pageSize' => $crud->pageSize,
                ])?>
             </div>
        </div>
    </div>
</div>