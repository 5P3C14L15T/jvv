<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../vendor/autoload.php';

function enviarConfirmacaoNewsletter($nome, $email)
{
    $mail = new PHPMailer(true);

    try {
        // Configuração SMTP
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'marketing@joaovictorvieira.com.br';
        $mail->Password = 'Joao1@2@3@';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('marketing@joaovictorvieira.com.br', 'BLOG Agência JVV');
        $mail->addAddress($email, $nome);

        $mail->isHTML(true);
        $mail->Subject = '🎉 Confirmação de Inscrição na Newsletter';

        $mail->Body = '
        <div style="max-width:600px;margin:0 auto;padding:20px;background-color:#f7f7f7;font-family:Arial,sans-serif;">
            <div style="background:#111;color:#fff;padding:15px;border-radius:5px 5px 0 0;">
                <h2 style="margin:0;">Bem-vindo à Agência JVV!</h2>
            </div>
            <div style="background:#fff;padding:20px;border:1px solid #ccc;border-top:0;">
                <p>Olá <strong>' . htmlspecialchars($nome) . '</strong>,</p>
                <p>Recebemos seu cadastro na nossa newsletter com sucesso!</p>
                <p>A partir de agora, você receberá conteúdos exclusivos, dicas e novidades diretamente no seu e-mail.</p>
                <p style="margin-top:30px;">Seja bem-vindo(a) à comunidade da <strong>Agência JVV</strong>! 🚀</p>
                <hr style="margin:20px 0;">
                <p style="font-size:12px;color:#555;">Se você não se cadastrou, pode ignorar esta mensagem.</p>
            </div>
        </div>';

        // Para debugging/log
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = function ($str, $level) {
            file_put_contents('log_smtp.txt', "SMTP DEBUG [$level]: $str\n", FILE_APPEND);
        };

        $mail->send();
        return true;
    } catch (Exception $e) {
        file_put_contents('log_news.txt', "📧 ERRO EMAIL: " . $mail->ErrorInfo . "\n", FILE_APPEND);
        return false;
    }
}
