<?php
  /* @var $this AutoChooserWidget */
$el_id = 'AutoChooser-'.uniqid();
?>
<div class="autoChooserWrapper <?=$el_id?>">
  <ul class="auto-type">
    <?php foreach($this->types as $type) { ?>
       <li class="auto-type-item">
         <input type="radio" id="<?=$el_id?>-<?=$type->id?>" name="auto-type" <?= (($type->id == $this->type)?'checked':''); ?> value="<?=$type->id?>" >
         <label for="<?=$el_id?>-<?=$type->id?>" class="ta-<?=$type->id?> auto-type-item-<?=$type->id?>"><?=$type->{'name_'.$this->lang}?></label>
       </li>
    <?php }?>
  </ul>
  <div class="brand col-xs-24 col-sm-12 col-md-12">
    <select name="auto-brand" class="auto-brand customSelect">
      <?php foreach($this->brands as $brand) { ?>
          <option value="<?=$brand->id_brand?>" <?=(($brand->id_brand == $this->brand)?"selected":"")?>><?=$brand->brand?></option>
      <?php }?>
    </select>
  </div>
  <div class="model col-xs-24 col-sm-12 col-md-12">
    <select name="auto-model" class="auto-model customSelect">
      <?php foreach($this->models as $model) { ?>
          <option value="<?=$model->id_model?>"><?=$model->model?></option>
      <?php }?>
    </select>
  </div>
</div>


<script type="text/javascript">
  (function($){
    $(document).ready(function(){
      $('.<?=$el_id?> .auto-type input').on('change', function(){
        $('.<?=$el_id?> .auto-brand').load('/autoBrands/getBrands/type/'+$('.<?=$el_id?> .auto-type input:checked').val(), function(){
          $('.<?=$el_id?> .auto-brand').change();
          $('.<?=$el_id?> .auto-brand').selectbox('detach').selectbox('attach');
        });
      });
      $('.<?=$el_id?> .auto-brand').on('change', function(){
        $('.<?=$el_id?> .auto-model').load('/autoModels/getModels/brand/'+$('.<?=$el_id?> .auto-brand option:selected').val()+'/type/'+$('.<?=$el_id?> .auto-type input:checked').val(), function(){
          $('.<?=$el_id?> .auto-model').selectbox('detach').selectbox('attach');
        });
      });
    });
  })(jQuery);
</script>

<?php



