<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
  <title><?php echo $form['name'] ?></title>
  <style type="text/css">
      .btn.btn-xxs.btn-danger.component-settings-button.component-settings-button-remove, 
      .btn.btn-xxs.btn-default.component-settings-button.component-settings-button-copy, 
      .btn.btn-xxs.btn-default.component-settings-button.component-settings-button-edit,
      .formio-component-submit .submit-fail::after, 
      i.glyphicon.glyphicon-refresh.glyphicon-spin.button-icon-right {
        display: none !important;
      }
  </style>
</head>
<body style="width: 96%;margin-left: auto;margin-right: auto;margin-top: 2%;margin-bottom: 2%;">
  <div class="container-fluid">
    <div class="row">
      <link rel='stylesheet' href="<?php echo base_url('assets/lib/bootstrap/dist/css/bootstrap.min.css')?>">
      <link rel='stylesheet' href="<?php echo base_url('assets/lib/form-builder/formio.full.min.css')?>">
      <script src="<?php echo base_url('assets/lib/jquery/jquery.min.js')?>"></script>
      <script src="<?php echo base_url('assets/lib/form-builder/formio.full.min.js')?>"></script>
      <div id="builder">
      </div>
      <div class="alert alert-success fade in successMessage" style="display: none;">
        <a class="close" data-dismiss="alert">&times;</a>
        <strong>
          <div id="success"></div>
        </strong>
      </div>
      <div class="alert alert-danger fade in" style="display: none;">
        <a class="close" data-dismiss="alert">&times;</a>
          <div id="errors"></div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    //var data = '<?php //echo $form['form_data'] ?>';
    var token = '<?php echo $form['token'] ?>';
    //var submit_text = '<?php //echo $form['submit_text'] ?>';
    var base_url = '<?php echo base_url('forms/save_lead')?>';
    var otherData = '<?php echo json_encode($otherData) ?>';
    window.onload = function() {
      Formio.createForm(document.getElementById('builder'), {components: JSON.parse('<?php echo $form['form_data'] ?>')}, {
        language: 'es',  i18n: { 'es': { Submit: '<?php echo $form['submit_text'] ?>' } } }, {
          readOnly: false
        }).then(function(form) {
          form.i18next.options.resources.es = {
            translation : {
              'Your Name' : JSON.parse(otherData).name,
              'Translations' : JSON.parse(otherData).translations,
              'Must Match Other Field' : JSON.parse(otherData).matching_text_field,
              'Please correct all errors before submitting.' : JSON.parse(otherData).please_correct_all_errors,
              'Matching Text Field' : JSON.parse(otherData).matching_text_field,
              'Confirm Text Field' : JSON.parse(otherData).confirm_text_field,
              error : JSON.parse(otherData).error_message,
              invalid_date :JSON.parse(otherData).invalid_date,
              invalid_email : JSON.parse(otherData).form_invalid_email,
              invalid_regex : JSON.parse(otherData).invalid_regex,
              max : JSON.parse(otherData).max,
              maxLength : JSON.parse(otherData).maxLength,
              min : JSON.parse(otherData).min,
              minLength : JSON.parse(otherData).minLength,
              next : JSON.parse(otherData).next,
              pattern : JSON.parse(otherData).pattern,
              previous : JSON.parse(otherData).previous,
              required : JSON.parse(otherData).required
            }
          };
          window.setLanguage = function (lang) {
            form.language = lang;
          };
          form.on('makeSpanish', function () {
            window.setLanguage('es');
          });
          form.on('submit', (submission) => {
            $('#success').empty();
            $('#errors').empty();
            $('.alert-danger').css('display', 'none');
            $('.alert-success').css('display', 'none');
            submission.token = token;
            $('button[type="submit"]').attr('disabled', 'disabled');
            $.ajax({
              url: base_url,
              method: 'POST',
              data: submission,
              type: 'JSON',
              success: (response) => {
                var response = JSON.parse(response);
                if (response.success == true) {
                  $('#builder').css('display', 'none');
                  $('.alert-success').css('display', 'none');
                  $('.successMessage').css('display', 'block');
                  $('#success').append(response.message);
                  $('button[type="submit"]').attr('disabled', false);
                } else {
                  $('.alert-danger').css('display', 'block');
                  $('#errors').append(response.message);
                  $('button[type="submit"]').attr('disabled', false);
                }
              }
            });
          });
          form.on('error', (errors) => {
            console.log('We have errors!');
          });
        });
      }
    $('.close').on('click', function() {
      $('.alert').css('display', 'none');
    });
  </script>
</body>
</html>