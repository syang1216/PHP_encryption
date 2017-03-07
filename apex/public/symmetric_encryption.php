<?php
  require_once('../private/initialize.php');

  $plain_text = '';
  $encode_key = '';
  $encrypted_text = '';
  $cipher_text = '';
  $decode_key = '';
  $decrypted_text = '';
  $encode_algorithm = 'AES-256-CBC';
  $decode_algorithm = 'AES-256-CBC';
  global $cipher_methods;
  if(isset($_POST['submit'])) {
  
    if(isset($_POST['encode_key'])) {
    
      // This is an encode request
      $plain_text = isset($_POST['plain_text']) ? $_POST['plain_text'] : nil;
      $encode_key = isset($_POST['encode_key']) ? $_POST['encode_key'] : nil;
      $encrypted_text = key_encrypt($plain_text, $encode_key);
      $cipher_text = $encrypted_text;
      $encode_algorithm = isset($_POST['encode_algorithm']) ? $_POST['encode_algorithm'] : nil;
      $decode_algorithm = $encode_algorithm;
    
    } else {
    
      // This is a decode request
      $cipher_text = isset($_POST['cipher_text']) ? $_POST['cipher_text'] : nil;
      $decode_key = isset($_POST['decode_key']) ? $_POST['decode_key'] : nil;
      $decrypted_text = key_decrypt($cipher_text, $decode_key);
      $decode_algorithm = isset($_POST['decode_algorithm']) ? $_POST['decode_algorithm'] : nil;
      $encode_algorithm = $decode_algorithm;
    }
  }

?>

<!doctype html>

<html lang="en">
  <head>
    <title>Symmetric Encryption: Encrypt/Decrypt</title>
    <meta charset="utf-8">
    <meta name="description" content="">
    <link rel="stylesheet" media="all" href="includes/styles.css" />
  </head>
  <body>
    
    <a href="index.php">Main menu</a>
    <br/>

    <h1>Symmetric Encryption</h1>
    
    <div id="encoder">
      <h2>Encrypt</h2>

      <form action="" method="post">
        <div>
          <label for="encode_algorithm">Algorithm</label>
          <select name="encode_algorithm">
                        <?php 
            foreach(array_keys($cipher_methods) as $key) {
              echo '<option value="' . h($key) . '"';
              if($encode_algorithm === $key) {echo 'selected';}
              echo '>' . $key . '</option>';
            } 
            ?>
          </select>
        </div>
        <div>
          <label for="plain_text">Plain text</label>
          <textarea name="plain_text"><?php echo h($plain_text); ?></textarea>
        </div>
        <div>
          <label for="encode_key">Key</label>
          <input type="text" name="encode_key" value="<?php echo $encode_key; ?>" />
        </div>
        <div>
          <input type="submit" name="submit" value="Encrypt">
        </div>
      </form>
    
      <div class="result"><?php echo h($encrypted_text); ?></div>
      <div style="clear:both;"></div>
    </div>
    
    <hr />
    
    <div id="decoder">
      <h2>Decrypt</h2>

      <form action="" method="post">
        <div>
          <label for="decode_algorithm">Algorithm</label>
          <select name="decode_algorithm">
              <?php 
            foreach(array_keys($cipher_methods) as $key) {
              echo '<option value="' . h($key) . '"';
              if($decode_algorithm === $key) {echo 'selected';}
              echo '>' . $key . '</option>';
            } 
            ?>
          </select>
        </div>
        <div>
          <label for="cipher_text">Cipher text</label>
          <textarea name="cipher_text"><?php echo h($cipher_text); ?></textarea>
        </div>
        <div>
          <label for="decode_key">Key</label>
          <input type="text" name="decode_key" value="<?php echo $decode_key; ?>" />
        </div>
        <div>
          <input type="submit" name="submit" value="Decrypt">
        </div>
      </form>

      <div class="result"><?php echo h($decrypted_text); ?></div>
      <div style="clear:both;"></div>
    </div>
    
  </body>
</html>
