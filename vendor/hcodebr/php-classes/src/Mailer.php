<?php
/**
 * Created by PhpStorm.
 * User: JULIO
 * Date: 17/09/2018
 * Time: 16:58
 */

namespace Hcode;

use Rain\Tpl;

class Mailer
{
    const USERNAME = "julio@disnorteagil.com.br";
    const PASSWORD = "712196j";
    const NAMEFROM = "HcodeStore";

    private $mail;



    public  function  __construct($toAdress, $toName, $subject, $tplName, $data = array())
    {

        $config = array(
            "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/email/",
            "cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
            "debug"         => false // set to false to improve the speed
        );

        Tpl::configure( $config );

        $tpl = new Tpl;

        foreach ($data as $key => $value){

            $tpl->assign($key,$value);

        }

        $html = $tpl->draw($tplName, true);


        $this->mail = new \PHPMailer();

        $this->mail->isSMTP();

        $this->mail->SMTPDebug = 0;

        $this->mail->Debugoutput = 'html';
        
        $this->mail->Host = 'smtp.terra.com.br';

        $this->mail->SMTPAuth = true;

        $this->mail->SMTPSecure = 'tls';

        $this->mail->Port = 587;

        $this->mail->CharSet = 'UTF-8';

        $this->mail->Username = Mailer::USERNAME;

        $this->mail->Password = Mailer::PASSWORD;

        $this->mail->setFrom(Mailer::USERNAME, Mailer::NAMEFROM);

        $this->mail->addAddress($toAdress, $toName);

        $this->mail->Subject = $subject;

        $this->mail->msgHTML($html);



    }

    public function send(){

        return $this->mail->send();

    }


}