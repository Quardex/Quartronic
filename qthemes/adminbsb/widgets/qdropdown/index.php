<?php
Q()->render->registerCssFile(Q()->qRootDir . '../adminbsb/plugins/bootstrap-select/css/bootstrap-select.css', \quarsintex\quartronic\qcore\QRender::POSITION_HEAD_BEGIN);
Q()->render->registerJsFile(Q()->qRootDir . '../adminbsb/plugins/bootstrap-select/js/bootstrap-select.js');
Q()->render->registerFile(Q()->qRootDir . '../adminbsb/plugins/bootstrap-select/css/bootstrap-select.css.map', 'css');
Q()->render->registerJs('initDropdown', "
        $(function() {
            let selects = $('.form-dropdown select');
            selects.parents('.form-line').addClass('focused');
            
            selects.on('change', function() {
                let optionSelected = $('option:selected', this);
                let wrapper = $(this).parents('.form-line');
                if (optionSelected.attr('value')) {
                    wrapper.removeClass('error').addClass('focused');
                    wrapper.parent().children('label.error').css('display', 'none');
                }
            });
        });
    ");
?>

<div class="form-group form-float">
    <div class="form-line<?= $this->error ? ' error' : '' ?>">
        <div class="form-dropdown">
            <select class="form-control show-tick" name="<?=$this->key?>">
                <option value="">--- Please select ---</option>
                <?php foreach($this->options as $key => $option): ?>
                <option value="<?=$key?>"<?php if ($this->current===$key) echo ' selected';?>><?=$option?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <label class="form-label"><?= mb_convert_case(str_replace('_', ' ', $this->key), MB_CASE_TITLE, "UTF-8") ?></label>
    </div>
    <?php if ($this->error) : ?>
        <label id="id-error-<?= $this->key ?>" class="error" for="<?= $this->key ?>"><?= $this->error ?></label>
    <?php endif ?>
</div>

<?php
?>
