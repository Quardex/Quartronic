<?php Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/jquery-validation/jquery.validate.js')?>
<?php Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/js/pages/forms/form-validation.js')?>

<form id="form_validation" method="POST">
    <?php
    foreach ($this->fields as $field) {

        if (isset($this->model) && $this->model->scenario != 'create' || empty($field->renderValues['autoincrement']))
            $field->render(false);
    }
    ?>
    <button class="btn bg-orange waves-effect" type="submit">SUBMIT</button>
</form>