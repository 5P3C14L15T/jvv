<!doctype html>
<html lang="pt-br">

<head>
    <title>Império Soluções Públicas | Sobre</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <meta name="description" content="Entre em contato com a Império Soluções Públicas. Estamos prontos para ajudar com soluções inovadoras para o setor público, garantindo eficiência e qualidade em todos os processos.">
    
    <!-- Meta Author -->
    <meta name="author" content="Império Soluções Públicas">
    
    <!-- Favicon -->
    <link rel="icon" href="images/icon.png" type="image/x-icon">
    

     <!-- Open Graph Protocol Meta Tags -->
     <meta property="og:title" content="Império Soluções Públicas | Entre em Contato" />
    <meta property="og:description" content="Entre em contato com a Império Soluções Públicas. Estamos prontos para ajudar com soluções inovadoras para o setor público, garantindo eficiência e qualidade em todos os processos." />
    <meta property="og:image" content="images/mkt.jpg" />
    <meta property="og:url" content="https://joaovictorvieira.com.br/job/isp/contato.php" />
    <meta property="og:type" content="website" />


    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Navbar (fora do Header) -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php">
                <img src="images/logo-branca.png" class="img-fluid" alt="Logo">
            </a>
            <!-- Mobile Menu Icon -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sobre.php">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contato.php">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="header" style="background-image: url('images/bg.jpg');">
        <!-- Header Content -->
        <div class="container header-content mt-5 text-center">
            <h1>ENTRE EM CONTATO</h1>
            <p class="subtitle text-white">Como nós podemos lhe ajudar?</p>
        </div>
    </header>

    <section id="listaAtas" class="listaAtas">
        <div class="container text-left">
            <div class="main">
                <!-- Contêiner de Busca -->


                <section class="contact-section">
                    <div class="container contact-container">
                        <!-- Formulário de Contato -->
                        <div class="contact-form">
                            <h2 class="section-title">Entre em Contato</h2>
                            <form id="contactForm" method="GET" action="https://api.whatsapp.com/send" target="_blank">
    <input type="hidden" name="phone" value="5515999999999"> <!-- Insira o número de telefone com o código do país -->
    <div class="form-group">
        <input type="text" id="name" name="name" placeholder="Nome" required>
    </div>
    <div class="form-group">
        <input type="tel" id="phone" name="user_phone" placeholder="Telefone" required>
    </div>
    <div class="form-group">
        <input type="text" id="subject" name="subject" placeholder="Assunto" required>
    </div>
    <div class="form-group">
        <textarea id="message" name="message" rows="5" placeholder="Mensagem" required></textarea>
    </div>
    <button type="submit" class="btn">Enviar Mensagem</button>
</form>

                        </div>
                
                        <!-- Google Maps e Informações de Contato -->
                        <div class="contact-info">
                            <div class="map">
                                <!-- Google Maps Embed -->
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d112555.38570506878!2d-56.04075985!3d-15.614407349999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x939da56670d84fc5%3A0x4e504e08900e510e!2sCuiab%C3%A1%20-%20Coxip%C3%B3%20da%20Ponte%2C%20Cuiab%C3%A1%20-%20MT!5e1!3m2!1spt-BR!2sbr!4v1725205640950!5m2!1spt-BR!2sbr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                  </div>
                            <!-- Informações de Contato -->
                            <div class="contact-details">
                                <h3 class="section-title">Informações de Contato</h3>
                                <p><strong>Endereço:</strong> Rua Exemplo, 123, Centro, Cuiabá - MT, 78000-000</p>
                                <p><strong>Telefone:</strong> (65) 99999-9999</p>
                                <p><strong>E-mail:</strong> contato@imperiosolucoespublicas.com.br</p>
                                <p><strong>Horário de Funcionamento:</strong> Segunda a Sexta, das 8h às 18h</p>
                            </div>
                        </div>
                    </div>
                </section>
                
                
            </div>
        </div>
    </section>

    <footer>
        <!-- Place footer here -->
        <div class="footer">
            <div class="container">
                <div class="row">
                    <img src="images/logo.png" alt="" class="img-fluid">
                    <div class="direitos">
                        Todos os Direitos Reservados <br>
                        Criado em 2024
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
        </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
        </script>
    <!-- JavaScript Personalizado -->
    <script src="js/script.js"></script>

    <script>
        document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Impede o envio normal do formulário

    const name = document.getElementById('name').value;
    const userPhone = document.getElementById('phone').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;

    // Formatar a mensagem para WhatsApp
    const whatsappMessage = `Nome: ${name}\nTelefone: ${userPhone}\nAssunto: ${subject}\nMensagem: ${message}`;

    // Codifica a mensagem para ser usada em uma URL
    const encodedMessage = encodeURIComponent(whatsappMessage);

    // Redireciona para a API do WhatsApp com a mensagem formatada
    const whatsappUrl = `https://api.whatsapp.com/send?phone=5565996335509&text=${encodedMessage}`;
    
    // Abre a URL no WhatsApp (abre em uma nova aba)
    window.open(whatsappUrl, '_blank');
});

    </script>
</body>

</html>