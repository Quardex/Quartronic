<?php if ($this->autoHeight) {
    Q()->render->registerJsFile(Q()->qRootDir.'../adminbsb/plugins/tinymce/tinymce.js');
    Q()->render->registerJs('inputAutosize', "$(function () {autosize($('textarea.auto-growth'));})");
}?>

<div class="form-group form-float">
    <div class="form-line<?=$this->error?' error':''?>">
    <?php if (!$this->autoHeight) : ?>
        <textarea rows="<?=$this->rows?>" name="<?=$this->key?>" class="form-control no-resize"><?=htmlspecialchars($this->value)?></textarea>
    <?php else : ?>
        <textarea rows="1" name="<?=$this->key?>" class="form-control no-resize auto-growth" style="overflow: hidden; overflow-wrap: break-word; height: 32px;"><?=htmlspecialchars($this->value)?></textarea>
    <?php endif; ?>
        <label class="form-label"><?=mb_convert_case(str_replace('_', ' ', $this->key), MB_CASE_TITLE, "UTF-8")?></label>
    </div>
    <?php if ($this->error) : ?>
        <label id="id-error-<?=$this->key?>" class="error" for="<?=$this->key?>"><?=$this->error?></label>
    <?php endif ?>
</div>

<?php
?>
