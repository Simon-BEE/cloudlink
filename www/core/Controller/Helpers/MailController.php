<?php

namespace Core\Controller\Helpers;

class MailController
{
    /**
     * Méthode pour envoyer un email à un utilisateur ou autre
     * Attention à bien changer l'email d'envoi (setFrom)
     */
    public static function sendMailToHim($objet, $mailto, $msg, $cci = true)//:string
    {
        if (!is_array($mailto)) {
            $mailto = [ $mailto ];
        }
        // Create the Transport
        if (getenv('ENV_DEV')) {
            $transport = (new \Swift_SmtpTransport(getenv('CONTAINER_MAIL'), 25));
        }else{
            $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
            ->setUsername(getenv('GMAIL_USER'))
            ->setPassword(getenv('GMAIL_PWD'));
        }
        
        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);
        // Create a message
        $message = (new \Swift_Message($objet))
            ->setFrom(["montluconaformac2019@gmail.com"]);
        if ($cci) {
            $message->setBcc($mailto);
        } else {
            $message->setto($mailto);
        }
        if (is_array($msg) && array_key_exists("html", $msg) && array_key_exists("text", $msg)) {
            $message->setBody($msg["html"], 'text/html');
            // Add alternative parts with addPart()
            $message->addPart($msg["text"], 'text/plain');
        } elseif (is_array($msg) && array_key_exists("html", $msg)) {
            $message->setBody($msg["html"], 'text/html');
            $message->addPart($msg["html"], 'text/plain');
        } elseif (is_array($msg) && array_key_exists("text", $msg)) {
            $message->setBody($msg["text"], 'text/plain');
        } elseif (is_array($msg)) {
            die('erreur une clé n\'est pas bonne');
        } else {
            $message->setBody($msg, 'text/plain');
        }
        
        // Send the message
        return $mailer->send($message);
    }

    /**
     * Méthode pour envoyer un email au webmaster, ou au gérant du site
     * Attention à bien changer l'email du receveur (setTo)
     */
    public static function sendMailToMe($objet, $mailFrom, $msg, $cci = true)//:string
    {
        // Create the Transport
        if (getenv('ENV_DEV')) {
            $transport = (new \Swift_SmtpTransport(getenv('CONTAINER_MAIL'), 25));
        }else{
            $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
            ->setUsername(getenv('GMAIL_USER'))
            ->setPassword(getenv('GMAIL_PWD'));
        }
        
        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);
        // Create a message
        $message = (new \Swift_Message($objet))
            ->setFrom([$mailFrom])
            ->setto(['montluconaformac2019@gmail.com']);

        if (is_array($msg) && array_key_exists("html", $msg) && array_key_exists("text", $msg)) {
            $message->setBody($msg["html"], 'text/html');
            // Add alternative parts with addPart()
            $message->addPart($msg["text"], 'text/plain');
        } elseif (is_array($msg) && array_key_exists("html", $msg)) {
            $message->setBody($msg["html"], 'text/html');
            $message->addPart($msg["html"], 'text/plain');
        } elseif (is_array($msg) && array_key_exists("text", $msg)) {
            $message->setBody($msg["text"], 'text/plain');
        } elseif (is_array($msg)) {
            die('erreur une clé n\'est pas bonne');
        } else {
            $message->setBody($msg, 'text/plain');
        }
        
        // Send the message
        return $mailer->send($message);
    }
    
    /**
     * Génére un message stylisée pour qu'un utilisateur puisse confirmer son inscription
     */
    public static function setMsgCheck($url, $user)
    {
        return
        "<body style='border: 20px solid #1b1b1e;background-color: #efefef;'>
			<section style='margin: 0 auto;background-color: #fff;width: 60%;padding: 5%;'>
				<h1 style='letter-spacing: 1.5px;'>Veuillez confirmer votre compte</h1>
				<p style='line-height: 25px;'>Merci pour inscription, il ne reste plus qu'une étape pour profiter pleinement de toutes les fonctions de notre site internet, il suffit de vérifier que votre adresse email correspond bien aux informations que vous nous avez envoyé.</p>
				<p style='line-height: 25px;'>Pour ce faire, veuillez cliquer sur <a style='color: #857555;text-decoration: none;' href='$url'>ce lien</a></p>
				<p style='line-height: 25px;'>A très bientôt $user, sur notre site !</p>
			</section>
		</body>";
    }

        /**
     * Génére un message stylisée pour qu'un utilisateur puisse obtenir un nouveau mot de passe (provisoire)
     */
    public static function setMsgPassword($password, $user)
    {
        return
        "<body style='border: 20px solid #1b1b1e;background-color: #efefef;'>
			<section style='margin: 0 auto;background-color: #fff;width: 60%;padding: 5%;'>
				<h1 style='letter-spacing: 1.5px;'>Nouveau mot de passe provisoire</h1>
				<p style='line-height: 25px;'>Vous avez demandé un nouveau mot de passe, car vous ne vous souvenez plus du votre.</p>
				<p style='line-height: 25px;'>Voici votre nouveau mot de passe : $password</p>
				<p style='line-height: 25px;'>Veiller à le changer une fois connecté.</p>
				<p style='line-height: 25px;'>A très bientôt $user, sur notre site !</p>
				<p style='line-height: 25px;'>PS: Si vous n'avez pas fait cette demande, veuillez contacter au plus vite un administrateur.</p>
			</section>
		</body>";
    }

        /**
     * Génére un message stylisée pour que le webmaster puisse recevoir les demandes de contact
     */
    public static function setMsgContact($name, $mail, $content)
    {
        return
        "<body style='border: 20px solid #1b1b1e;background-color: #efefef;'>
            <h1>$name || $mail - vous a contacté</h1>
            <p>$content</p>
		</body>";
    }
    
}