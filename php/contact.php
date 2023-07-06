<?php
if(isset($_POST["submit"])) {
  $nome = $_POST['nome'];
  $email = $_POST['email'];

  $target_dir = "/uploads/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $to = 'lucas_rc15@live.com';
    $subject = 'Novo arquivo enviado';
    $message = 'Arquivo enviado por ' . $nome . ' (' . $email . ').';
    $headers = 'From: webmaster@example.com' . "\r\n" .
               'Reply-To: webmaster@example.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    $file = $target_file;
    $content = file_get_contents($file);
    $content = chunk_split(base64_encode($content));

    $separator = md5(time());
    $eol = "\r\n";

    $headers .= "MIME-Version: 1.0".$eol; 
    $headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"".$eol;
    $headers .= "Content-Transfer-Encoding: 7bit".$eol;
    $headers .= "This is a MIME encoded message.".$eol;

    $body = "--".$separator.$eol;
    $body .= "Content-Type: text/plain; charset=\"iso-8859-1\"".$eol;
    $body .= "Content-Transfer-Encoding: 8bit".$eol;
    $body .= $message.$eol;

    $body .= "--".$separator.$eol;
    $body .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"".$eol; 
    $body .= "Content-Transfer-Encoding: base64".$eol;
    $body .= "Content-Disposition: attachment".$eol;
    $body .= $content.$eol;
    $body .= "--".$separator."--";

    if (mail($to, $subject, $body, $headers)) {
      echo "Email enviado com sucesso!";
    } else {
      echo "Erro ao enviar email.";
    }

  } else {
    echo "Erro no upload do arquivo.";
  }
}
?>
