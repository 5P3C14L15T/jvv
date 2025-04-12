<?php
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    
    // Número de telefone para enviar a mensagem via WhatsApp
    $phoneNumber = "5565996335509"; // Substitua pelo número desejado

    // Construa a mensagem
    $whatsappMessage = "*Site Agência*\nNome: $name\nEmail: $email\nMensagem: $message";

    // Encode a mensagem para enviar via URL
    $encodedMessage = urlencode($whatsappMessage);

    // URL para enviar a mensagem via WhatsApp
    $whatsappUrl = "https://api.whatsapp.com/send?phone=$phoneNumber&text=$encodedMessage";

    // Redireciona para o WhatsApp
    header("Location: $whatsappUrl");
    exit;
}
?>
