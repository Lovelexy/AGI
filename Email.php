<?php

//Load Composer's autoloader
require_once dirname(__FILE__) . "/vendor/autoload.php";
require_once dirname(__FILE__) . "/vendor/phpmailer/phpmailer/PHPMailerAutoload.php";

class Email extends PHPMailer
{
    public function __construct()
    {
        parent::__construct();

        $this->IsSMTP();
        $this->Host = "email-smtp.sa-east-1.amazonaws.com";
        $this->SMTPAuth = true;
        $this->SMTPSecure = 'tls';
        $this->Username = "AKIA3PLVSE2SVEZYEIGB";
        $this->Password = "BN6aWnSmN9ezibaiWLWFL3Wg56RpoZVxsWpEPbakwLEQ";
        $this->Port = 587;
        $this->setFrom("agi@condominioarcositaim.com.br", "AGI");

        $this->IsHtml(true);
        $this->CharSet = "utf-8";
    }

    public function setAssunto($assunto)
    {
        $this->Subject = $assunto;
    }

    public function setMensagem($msg)
    {
        $this->Body = $msg;
    }
}
