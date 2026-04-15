<!DOCTYPE html>
<html lang="zh-HK">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Registration - Cerebral Corner</title>
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
        <h1 class="font-slackey text-5xl m-0">Cerebral Corner</h1>
        <p class="font-slackey text-2xl opacity-90 mt-2">Join the playground.</p>
    </header>

    <main class="max-w-3xl mx-auto my-10 px-5">
        <section class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="font-slackey text-3xl text-brand-title border-b-2 border-gray-200 pb-2 mb-6">Member Registration</h2>
            <p class="mb-6 opacity-75 italic">Please fill in the following to register your account.</p>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="memberRegisterForm" method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="lastname" class="block font-bold mb-1">Last Name</label>
                        <input id="lastname" name="lastname" type="text" value="{{ old('lastname') }}"
                               class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-brand-subtitle outline-none"/>
                    </div>
                    <div>
                        <label for="firstname" class="block font-bold mb-1">First Name</label>
                        <input id="firstname" name="firstname" type="text" value="{{ old('firstname') }}"
                               class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-brand-subtitle outline-none"/>
                    </div>
                </div>

                <div>
                    <label for="address" class="block font-bold mb-1">Mail Address</label>
                    <textarea id="address" name="address" rows="3" class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-brand-subtitle outline-none">{{ old('address') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block font-bold mb-1">Contact Phone Number</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                               class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-brand-subtitle outline-none" />
                    </div>
                    <div>
                        <label for="email" class="block font-bold mb-1">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                               class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-brand-subtitle outline-none" />
                    </div>
                </div>

                <div>
                    <label for="password" class="block font-bold mb-1">Password</label>
                    <input id="password" name="password" type="password" 
                           class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-brand-subtitle outline-none" />
                </div>

                <div>
                    <label for="password_confirmation" class="block font-bold mb-1">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" 
                           class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-brand-subtitle outline-none" />
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4 pt-4 border-t border-gray-100">
                    <button type="submit" class="w-full sm:w-auto bg-brand-subtitle text-white py-3 px-8 rounded-md font-bold transition-colors duration-300 hover:bg-brand-title">
                        Register
                    </button>

                    <button type="button" id="clearBtn" class="w-full sm:w-auto bg-gray-200 text-brand-text py-3 px-8 rounded-md font-bold transition-colors duration-300 hover:bg-gray-300">
                        Clear
                    </button>
                </div>
            </form>
        </section>
    </main>

    <footer class="text-center p-5 mt-10 bg-brand-header text-brand-header-text">
        <p>&copy; 2026 Cerebral Corner. All Rights Reserved.</p>
    </footer>

    <script>
        document.getElementById('clearBtn').addEventListener('click', function () {
            document.getElementById('memberRegisterForm').reset();
        });
    </script>
</body>
</html>