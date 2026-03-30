<?php
$host = 'localhost';
$db   = 'boardgame_cafe';
$user = 'root';
$pass = '';  

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("database connect failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>

<!DOCTYPE html>
<html lang="zh-en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - Cerebral Corner</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Slackey&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'slackey': ['Slackey', 'sans-serif'],
                        'sans': ['Helvetica Neue', 'Arial', 'sans-serif'],
                    },
                    colors: {
                        'brand-bg': '#F5EFE6',
                        'brand-text': '#4A3933',
                        'brand-header': 'rgba(62, 44, 27, 0.8)',
                        'brand-header-text': '#F5EFE6',
                        'brand-title': '#8B4513',
                        'brand-subtitle': '#A0522D',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-bg text-brand-text font-sans leading-relaxed">

    <header class="bg-brand-header text-brand-header-text p-8 text-center">
        <img 
            src="logo.png" 
            alt="咖啡馆徽标" 
            class="w-24 h-24 rounded-full mx-auto mb-4">
        <h1 class="font-slackey text-5xl m-0">Cerebral Corner</h1>
        <p class="font-slackey text-2xl opacity-90 mt-2">This is your brain playground.</p>
    </header>

    <main class="max-w-3xl mx-auto my-10 px-5">
        <section class="bg-white p-10 mb-5 rounded-lg shadow-md text-brand-text text-center">
            <h2 class="font-slackey text-4xl text-brand-title mb-4">Thank You!</h2>
            <div class="w-16 h-1 bg-brand-subtitle mx-auto mb-6"></div>
            <p class="text-lg mb-6">
                Your submission was successful. We are excited to have you join our community of board game enthusiasts!
            </p>
            <p class="text-md italic opacity-80">
                Get ready for your next mental storm.
            </p>
        </section>

        <nav class="text-center my-8">
            <a href="introduction.html" class="inline-block bg-brand-subtitle text-white py-3 px-8 rounded-md text-base font-bold transition-colors duration-300 hover:bg-brand-title">
                Return to Home
            </a>
        </nav>
    </main>

    <footer class="text-center p-5 mt-10 bg-brand-header text-brand-header-text">
        <p>&copy; 2026 Cerebral Corner. All Rights Reserved.</p>
    </footer>

</body>
</html>
