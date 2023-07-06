<?php
$subjectPrefix = 'Prefixo';
$emailTo       = 'lucas_rc15@live.com';  // altere isso para o endereço de email desejado

$errors = array(); // array to hold validation errors
$data   = array(); // array to pass back data

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = stripslashes(trim($_POST['nome']));
    $email   = stripslashes(trim($_POST['email']));
    $file    = $_FILES['fileToUpload'];  // recebe o arquivo carregado

    if (empty($name)) {
        $errors['nome'] = 'Nome é obrigatório.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email é inválido.';
    }

    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        $errors['file'] = 'Arquivo é obrigatório.';
    }

    // if there are any errors in our errors array, return a success boolean or false
    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors']  = $errors;
    } else {
        $subject = "Message from $subjectPrefix";
        $body    = '
            <strong>Name: </strong>'.$name.'<br />
            <strong>Email: </strong>'.$email.'<br />
        ';

        $headers  = "MIME-Version: 1.1" . PHP_EOL;
        $headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
        $headers .= "Content-Transfer-Encoding: 8bit" . PHP_EOL;
        $headers .= "Date: " . date('r', $_SERVER['REQUEST_TIME']) . PHP_EOL;
        $headers .= "Message-ID: <" . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>' . PHP_EOL;
        $headers .= "From: " . "=?UTF-8?B?".base64_encode($name)."?=" . " <$email> " . PHP_EOL;
        $headers .= "Return-Path: $emailTo" . PHP_EOL;
        $headers .= "Reply-To: $email" . PHP_EOL;
        $headers .= "X-Mailer: PHP/". phpversion() . PHP_EOL;
        $headers .= "X-Originating-IP: " . $_SERVER['SERVER_ADDR'] . PHP_EOL;

        if (mail($emailTo, "=?utf-8?B?" . base64_encode($subject) . "?=", $body, $headers)) {
          $data['success'] = true;
          $data['confirmation'] = 'Parabéns. Sua mensagem foi enviada com sucesso';
        } else {
          $data['success'] = false;
          $data['errors']  = 'Falha ao enviar o email.';
        }
    }

    // return all our data to an AJAX call
    echo json_encode($data);
}
?>
