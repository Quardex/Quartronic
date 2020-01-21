<div class="block-header">
    <h2>Model CRUD</h2>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2><?=$title?></h2>
            </div>
            <div class="body table-responsive dataTables_wrapper">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <?php
                        foreach ($crud->modelFields as $field) {
                            echo "<th>".mb_convert_case(str_replace('_', ' ', $field), MB_CASE_TITLE, "UTF-8")."</th>";
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i = 0;
                        foreach ($crud->list as $model) {
                            foreach ($model->fields as $item) {
                                echo '<td>'.$item.'</td>';
                            }
                            echo '<td style="width:100px">'.
                                '<a href="view?'.http_build_query($model->primaryKey).'"><i class="material-icons">visibility</i></a>&nbsp;'.
                                '<a href="edit?'.http_build_query($model->primaryKey).'"><i class="material-icons">edit</i></a>&nbsp;'.
                                '<a href="delete?'.http_build_query($model->primaryKey).'"><i class="material-icons">delete</i></a>'.
                                '</td>';
                            echo '</tr>';
                        }
                    ?>
                    </tbody>
                </table>
                <div class="dataTables_info" id="DataTables_Table_1_info" role="status" aria-live="polite">Showing 1 to <?=count($crud->list)?> of <?=$countAll?> entries</div>
                <?php $this->widget('Pagination', [
                    'total' => $countAll,
                    'currentPage' => $crud->page,
                    'pageSize' => $crud->limit,
                ])?>
             </div>
        </div>
    </div>
</div>