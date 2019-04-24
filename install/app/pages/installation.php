<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <!-- CUSTOM MESSAGE -->
            <div id="message"></div>
        </div>
        <div class="col-sm-12">
            <!-- BEGIN FORM -->
            <form action="./app/controller/Setup_process.php" method="POST" id="setupForm">
            
            <input type="hidden" name="cname" value=<?php $_POST['cname']?>>
            <input type="hidden" name="cburl" value=<?php $_POST['cburl']?>>
            <input type="hidden" name="cbtin" value=<?php $_POST['cbtin']?>>

            <div style="border:2px solid red;text-align:center;">
                <div style="border:1px solid;width: 50%;margin:auto;text-align:left;padding:15px;">
                    <h3><strong>Create Admin Account</strong></h3>
                    <label for="cemail"><b>Admin Email Address*</b></label>
                    <input type="email" name="cemail" id="cemail" placeholder="ex. admin@email.com" class="form-control" >
                    <br>

                    <label for="cpassword"><b>Password*</b></label>
                    <div class="input-group">
                      <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Password">
                      <div class="input-group-btn ">
                        <button class="btn btn-default btn-sm" type="submit">
                          <i class="glyphicon glyphicon-eye-open"></i>
                        </button>
                      </div>
                    </div>
                    <br>

                    <label for="ccpassword"><b>Confirm Password*</b></label>
                    <div class="input-group">
                      <input type="password" name="ccpassword" id="ccpassword" class="form-control" placeholder="Confirm Password">
                      <div class="input-group-btn ">
                        <button class="btn btn-default btn-sm" type="submit">
                          <i class="glyphicon glyphicon-eye-open"></i>
                        </button>
                      </div>
                    </div>

                    <br>
                    <div class="text-center">
                      <button class="btn btn-default" id="install_btn" onclick="goBack()"><i class="fa fa-backward"></i> Previous</button>
                      <button class="btn btn-default" id="install_btn">Install</button>
                    </div>
                </div>
              </div>
              <!-- HIDDEN CSRF VALUE-->
              <input type="hidden" name="csrf_token" value="<?= (!empty($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : null) ?>" width=100%>

              <!-- UPLOAD SQL FILE -->
              <div class="form-group" style="display: none">
                <label for="upload">Upload SQL File</label>
                <input type="file" class="form-control" id="upload">
                <p class="text-danger" id="upload-help-block">
                  <?php 
                    if(file_exists('./public/files/Load.sql')) {
                      echo '<div role="alert" class="alert alert-success alert-icon alert-icon-border alert-dismissible">
                    <div class="icon"><span class="mdi mdi-check"></span></div>
                    <div class="message">
                      <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true" class="mdi mdi-close"></span></button><strong>Good!</strong>The database file is already exits!. you can replace it by uploading another database file.
                    </div>
                  </div>';
                    } else {
                      echo "The Upload SQL File field is required";
                    }
                  ?>
                </p>                
              </div>
              <?php
              $cname = str_replace(' ', '_', $_POST['cname']);
              ?>
              

              <!-- HOSTNAME  -->
              <div class="form-group">
                <label for="hostname">Hostname</label>
                <input type="text" class="form-control" id="hostname" placeholder="Hostname" name="hostname" value="<?= (isset($_POST['hostname']) ? $_POST['hostname'] : 'localhost') ?>">
              </div>
              <!-- USERNAME -->
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" placeholder="Username" name="username" value="<?= (isset($_POST['username']) ? $_POST['username'] : 'root') ?>">
              </div>
              <!-- PASSWORD -->
              <div class="form-group">
                <label for="password">Password</label>
                <input type="text" class="form-control" id="password" placeholder="Password" name="password" value="<?= (isset($_POST['password']) ? $_POST['password'] : null) ?>">
              </div>
              <!-- DATABASE -->
              <div class="form-group">
                <label for="database">Database</label>
                <input type="text" class="form-control" id="database" placeholder="Database" name="database" value="<?= (isset($cname) ? $cname : null) ?>">
              </div>

              <!-- HTACCESS -->
<?php
$htaccess = "
RewriteEngine on 
RewriteCond %{REQUEST_FILENAME} !-f 
RewriteCond %{REQUEST_FILENAME} !-d 
RewriteRule ^(.*)$ index.php?/$1 [L] 
"; 
?>
              <div class="form-group">
                <label for="htaccess">Htaccess <input type="checkbox" id="is_htaccess" name="isHtaccess" value="1"> (if you want to add .htacess file in your application then click on this checkbox)</label>
                <div class=" hide">
                    <textarea class="form-control" id="htaccess" rows="5" placeholder="Htaccess" name="htaccess"><?= (isset($_POST['htaccess']) ? $_POST['htaccess'] : $htaccess) ?></textarea>
                    <p class="help-block maroon">If you have custom htaccess, please paste your htaccess code here else nothing needs to do.</p>
                </div>
              </div>


              <!-- BUTTONS -->
              <div class="divider"></div>
              <a href="?step=requirements" class="btn btn-default left-right"> <i class="fa fa-backward"></i> Previous</a>
              <div class="pull-right btn-group">
                <button type="reset" class="btn btn-default">Reset</button>
                <button type="submit" class="btn btn-default">Install</button>
              </div>

            </form> 
            <!-- ENDS FORM -->
        </div>
    </div>
</div>
<script>
function goBack() {
  window.location.href = '?step=requirements';
}
</script>