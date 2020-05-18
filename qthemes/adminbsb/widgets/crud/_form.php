<?php Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/jquery-validation/jquery.validate.js')?>
<?php Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/js/pages/forms/form-validation.js')?>

<form id="form_validation" method="POST">

        <?php
        foreach ($model->fieldList as $field) {
            echo '
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" class="form-control" name="'.$field.'" value="'.htmlspecialchars($model->$field).'"'.($model->structure[$field]['required'] ? ' required':'').'>
                        <label class="form-label">'.mb_convert_case(str_replace('_', ' ', $field), MB_CASE_TITLE, "UTF-8").'</label>
                    </div>
                    <!--label id="id-error" class="error" for="'.$field.'">This field is required.</label-->
                </div>';
        }
        ?>
    <button class="btn bg-orange waves-effect" type="submit">SUBMIT</button>
</form>