<div class="form-group form-float">
    <div class="form-line<?= $this->error ? ' error' : '' ?>">
        <input type="text" class="form-control" name="<?=$this->key?>"
               value="<?=htmlspecialchars($this->value)?>"<?= ($this->required ? ' required' : '') ?>>
        <label class="form-label"><?= mb_convert_case(str_replace('_', ' ', $this->title), MB_CASE_TITLE, "UTF-8") ?></label>
    </div>
    <?php if ($this->error) : ?>
        <label id="id-error-<?=$this->key?>" class="error" for="<?= $this->key ?>"><?= $this->error ?></label>
    <?php endif ?>
</div>