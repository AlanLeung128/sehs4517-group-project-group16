<!DOCTYPE html>
<html lang="zh-en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Failed - Cerebral Corner</title>
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
<body class="bg-brand-bg text-brand-text font-['Helvetica_Neue'] leading-relaxed">
    <header class="bg-brand-header text-brand-header-text p-6 text-center">
        <a href="introduction.php" class="text-brand-header-text hover:opacity-80">
            <h1 class="font-slackey text-3xl m-0">Cerebral Corner</h1>
        </a>
    </header>
    
    <main class="max-w-md mx-auto my-10 px-5">
        <div class="bg-white p-8 rounded-lg shadow-md text-center">
            <div class="text-6xl mb-4">🎲</div>
            <h2 class="font-slackey text-3xl text-red-600 mb-4">Your dice roll failed!</h2>
            <p class="text-brand-text mb-6">Invalid email or password. Please try again.</p>
            
            <div class="flex gap-4">
                <!--link-->
                <a href="login.php" class="flex-1 bg-brand-subtitle text-white py-3 rounded-md font-bold transition-colors duration-300 hover:bg-brand-title text-center">Try Again</a>
                <a href="introduction.html" class="flex-1 bg-gray-400 text-white py-3 rounded-md font-bold transition-colors duration-300 hover:bg-gray-500 text-center">Back to Introduction</a>
            </div>
        </div>
    </main>
    
    <footer class="text-center p-5 mt-10 bg-brand-header text-brand-header-text">
        <p>&copy; 2026 Cerebral Corner. All Rights Reserved.</p>
    </footer>
</body>
</html>
