<?php
ini_set('display_errors', 1);

require_once dirname(__FILE__) . '/Email.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = array(); // array to hold validation errors
    $data   = array(); // array to pass back data

    $name    = stripslashes(trim($_POST['nome']));
    $email   = stripslashes(trim($_POST['email']));
    $file    = $_FILES['fileToUpload'];  // recebe o arquivo carregado

    if (empty($name)) {
        $errors[] = 'Nome é obrigatório.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email é inválido.';
    }

    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = 'Arquivo é obrigatório.';
    }

    // if there are any errors in our errors array, return a success boolean or false
    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors']  = $errors;
    } else {
        try {
            $body = '
                <strong>Nome: </strong>' . $name . '<br />
                <strong>E-mail: </strong>' . $email . '<br />
            ';

            $email = new Email();
            $email->setAssunto('Envio de procuração');
            $email->setMensagem($body);

            $email->addAttachment($file['tmp_name'], $file['name']);

            $emailTo = 'agi@oma.com.br';
            $email->AddAddress($emailTo, 'Teste');

            if ($email->send()) {
                $data['success'] = true;
                $data['confirmation'] = 'Sua mensagem foi enviada com sucesso. Em caso dúvidas, entre em contato pelo e-mail agi@oma.com.br';
            } else {
                $data['success'] = false;
                $data['errors']  = ['Falha ao enviar o email.'];
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    echo json_encode($data);
}
