<?php
if (($editor = mb_strtolower($this->editor)) == 'ckeditor') {
    Q()->render->registerFile(Q()->qRootDir . '../adminbsb/plugins/ckeditor/ckeditor.js', 'ckeditor');
    Q()->render->registerDir(Q()->qRootDir . '../adminbsb/plugins/ckeditor', 'ckeditor');
    Q()->render->registerJs('initEditor', "
        $(function () {
               for (let i=1;i<=".$totalCount.";i++) {
                    CKEDITOR.replace('ckeditor'+i);
               }
               CKEDITOR.config.height = 300;
    
        })
    ");
}

if (($editor = mb_strtolower($this->editor)) == 'tinymce') {
    Q()->render->registerFile(Q()->qRootDir . '../adminbsb/plugins/tinymce/tinymce.js', 'tinymce', 'js');
    Q()->render->registerDir(Q()->qRootDir . '../adminbsb/plugins/tinymce', 'tinymce');
    Q()->render->registerJs('initEditor', "
        tinymce.init({
            selector: 'textarea.tinymce',
            theme: 'modern',
            height: 300,
            plugins: [ 
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools'
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons',
            image_advtab: true
        });
        tinymce.suffix = '.min';
    ");
}
?>

<div class="form-group form-float">
    <div class="form-line<?=$this->error?' error':''?>">
        <label class="form-label" style="position:unset"><?=mb_convert_case(str_replace('_', ' ', $this->title), MB_CASE_TITLE, "UTF-8")?></label>
        <textarea id="<?=$editor.($totalCount = $this->count)?>" name="<?= $this->key ?>" class="form-control <?=$editor?>"><?=htmlspecialchars($this->value)?></textarea>
    </div>
    <?php if ($this->error) : ?>
        <label id="id-error-<?= $this->key ?>" class="error" for="<?= $this->key ?>"><?= $this->error ?></label>
    <?php endif ?>
</div>