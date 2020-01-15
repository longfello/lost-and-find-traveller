<?php
  /*
  foreach ($data['errors'] as $type => $error) {
    echo($type . ' -> ' . print_r($error, true).'<br>');
  }
  echo('<br><pre>');
  print_r($data);
  echo("</pre>");

    $sUser->email = $uloginModel->email;
    $sUser->login = Core::translit($uloginModel->full_name);
    $sUser->pass  = Core::genCode(8);
    $sUser->user_name = $uloginModel->full_name;
  */
?>

<div id="content" class="annotation">
  <div id="main-page-puk" class="cont-in">
    <div class="ulogin-assoc">
      <div class="left">
        <h2>Создать новый аккаунт</h2>
        <form method="post" class="ajax-verify ulogin-create" action="<?= $this->createUrl('/ulogin/assoc') ?>" data-type="create">
          <?php foreach($nested_fields as $key => $one) {
            echo("
             <div class='row ulogin-{$key}'>
               <label for='create-{$key}'>{$one['label']}</label>
               <input type='text' name='{$key}' data-name='{$key}' value='{$one['val']}' id='create-{$key}'>
             </div>
            ");
          }
          ?>
          <?=$network_param?>
          <div class='error'></div>
          <button type='submit' class='button'>Зарегитрироватся</button>
        </form>
      </div>
      <div class="right">
        <h2>Привязать к существующему акаунту</h2>
        <form method="post" class="ajax-verify ulogin-associate" action="<?= $this->createUrl('/ulogin/assoc') ?>" data-type="assoc">
          <div class="row ulogin-login">
            <label for='assoc-login'>Телефон</label>
            <input type='text' name='phone' data-name='login' value='' id='assoc-login'>
          </div>
          <div class="row ulogin-password">
            <label for='assoc-password'>Пароль</label>
            <input type='password' name='password' data-name='password' value='' id='assoc-password'>
          </div>
          <?=$network_param?>
          <div class='error'></div>
          <button type='submit' class='button'>Привязать</button>
        </form>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('.ajax-verify').on('submit', function(e){
      e.preventDefault();
      $('.ulogin-assoc').css('opacity', '0.3');
      var valType = $(this).data('type');
      var form_data = $(this).blur().serializeArray();
      form_data.push({
        'name':"type",
        'value':valType
      });
      $.ajax({
        url: '/ulogin/validate',
        dataType: 'JSON',
        method: 'POST',
        data: form_data,
        success: function(data){
          $('.ulogin-assoc').css('opacity', '1');
          $('.ulogin-assoc .error').html('');
          if (data.result) {
            document.location.href = '/';
          } else {
            for(i in data.errors) {
              $('.ulogin-'+i+' .error').html(data.errors[i]);
            }
          }
        }
      });
    });
  });
</script>