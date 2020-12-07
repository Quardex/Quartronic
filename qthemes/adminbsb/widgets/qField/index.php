<div class="form-group form-float">
 <div class="form-line">
  <input type="text" class="form-control" name="<?=$this->key?>" value="<?=htmlspecialchars($this->value)?>"<?=($this->required ? ' required':'')?>>
   <label class="form-label"><?=mb_convert_case(str_replace('_', ' ', $this->key), MB_CASE_TITLE, "UTF-8")?></label>
  </div>
  <!--label id="id-error" class="error" for="'.$key.'">This field is required.</label-->
</div>