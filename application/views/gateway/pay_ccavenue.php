<html>
<head>
    <title> Non-Seamless-kit</title>
</head>
<body>

<?php
require_once APPPATH . '/libraries/Ccavenue.php';
error_reporting(0);

$merchant_data = '';
$working_key = $payment['ccavenue_working_key'];
$access_code = $payment['ccavenue_access_code'];

foreach ($_POST as $key => $value) {
    $merchant_data .= $key . '=' . $value . '&';
}
$encrypted_data = encrypt_cc($merchant_data, $working_key); // Method for encrypting the data.
if ($payment['ccavenue_test_mode'] === true) {
    $url = 'https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
} else {
    $url = 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
}
?>
<form method="post" name="redirect" action="<?= $url ?>">
    <?php
    echo "<input type=hidden name=encRequest value=$encrypted_data>";
    echo "<input type=hidden name=access_code value=$access_code>";
    ?>
</form>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>