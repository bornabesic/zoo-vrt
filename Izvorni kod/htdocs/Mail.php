<?php
    require_once("PHPMailer/PHPMailerAutoload.php");
    ini_set('default_charset', 'utf-8');
    /*
        U class.phpmailer.php se bira ime posiljatelja:
        public $FromName = 'ZOO Vrt';
    */

    function send_donation_mail($receiver, $first_last_name, $city){

        $html_1="Poštovani,

        Vaše ime sada se nalazi na popisu posvojitelja našeg ZOO Vrta.
        Iznimno smo zahvalni na Vašem interesu, a u nastavku šaljemo podatke za uplatu Vaše donacije.

        Platitelj: ";

        $html_2="
        Primatelj: ZOO Vrt
        Iznos: 300,00 HRK
        Broj računa primatelja / IBAN: HR5924020063208059208

        ZOO Vrt - grupa Synchronized";


        ini_set("SMTP","ssl://smtp.gmail.com"); 
        ini_set("smtp_port","465");
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->SMTPAuth = true;
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPSecure = "ssl";
        $mail->Username = "zoovrt.synchronized@gmail.com";
        $mail->Password = "zoovrtsynchronized";
        $mail->Port = "465";
        $mail->isSMTP(); 

        $mail->AddAddress($receiver);
        $mail->Subject  = "ZOO Vrt - podaci od uplati donacije";
        $mail->Body     = $html_1 . $first_last_name . ", " . $city . $html_2;
        $mail->WordWrap = 200;
        if(!$mail->Send()) {
            return json_encode(array("error" => $mail->ErrorInfo));
        }
    }
?>