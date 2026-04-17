<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cerebral Corner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css'])
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
        <a href="{{ route('introduction') }}" class="text-brand-header-text hover:opacity-80">
            <h1 class="font-slackey text-3xl m-0">Cerebral Corner</h1>
        </a>
    </header>
    
    <main class="max-w-md mx-auto my-10 px-5">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="font-slackey text-3xl text-brand-title text-center mb-6">Login to Reserve</h2>
            
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ $errors->first() }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-brand-text font-bold mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                           class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-brand-subtitle">
                </div>
                
                <div class="mb-6">
                    <label class="block text-brand-text font-bold mb-2">Password</label>
                    <input type="password" name="password" required 
                           class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-brand-subtitle">
                </div>
                
                <button type="submit" class="w-full bg-brand-subtitle text-white py-3 rounded-md font-bold transition-colors duration-300 hover:bg-brand-title">
                    Login
                </button>
            </form>
            
            <div class="text-center mt-6">
                <a href="{{ route('register') }}" class="text-brand-subtitle hover:text-brand-title">Don't have an account? Register</a>
                <span class="mx-2 text-gray-400">|</span>
                <a href="{{ route('introduction') }}" class="text-brand-subtitle hover:text-brand-title">Back to Introduction</a>
            </div>
        </div>
    </main>
    
    <footer class="text-center p-5 mt-10 bg-brand-header text-brand-header-text">
        <p>&copy; 2026 Cerebral Corner. All Rights Reserved.</p>
    </footer>
</body>
</html>
