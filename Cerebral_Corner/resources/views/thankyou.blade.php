<!DOCTYPE html>
<html lang="en">
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
                    fontFamily: { 'slackey': ['Slackey', 'sans-serif'] },
                    colors: {
                        'brand-bg': '#F5EFE6',
                        'brand-text': '#4A3933',
                        'brand-header': '#3E2C1B',
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

    <!-- Header -->
    <header class="bg-brand-header text-brand-header-text p-8 text-center">
        <img src="{{ asset('images/logo.png') }}" 
             alt="Cerebral Corner Logo" 
             class="w-24 h-24 rounded-full mx-auto mb-4 shadow-lg">
        <h1 class="font-slackey text-5xl">Cerebral Corner</h1>
        <p class="font-slackey text-2xl opacity-90 mt-2">This is your brain playground.</p>
    </header>

    <main class="max-w-3xl mx-auto my-12 px-6">
        <div class="bg-white p-10 rounded-3xl shadow-md text-center border border-[#d4b89e]">
            <h2 class="font-slackey text-4xl text-brand-title mb-4">Thank You!</h2>
            <div class="w-16 h-1 bg-brand-subtitle mx-auto mb-8"></div>
            
            <p class="text-xl mb-10">Thank you for reserving your game session!<br>We look forward to seeing you at Cerebral Corner!</p>

            @if(isset($reservations) && count($reservations) > 0)
            <div class="bg-brand-bg/60 rounded-2xl p-8 text-left border border-brand-subtitle/30">
                <h3 class="text-2xl font-bold text-brand-subtitle mb-6 border-b pb-3">Reservation Summary</h3>
                
                <div class="space-y-4 text-lg">
                    
                    <div class="flex justify-between">
                        <span class="font-medium">Email Address:</span>
                        <span class="font-bold">{{ $email }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-medium">Date:</span>
                        <span class="font-bold">{{ \Carbon\Carbon::parse($reservations[0]['date'])->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Room / Table:</span>
                        <span class="font-bold">{{ $reservations[0]['space_name'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Time Slot(s):</span>
                        <span class="font-bold">
                            @foreach($reservations as $res)
                                {{ \Carbon\Carbon::parse($res['time_slot'])->format('g:i') }}-{{ \Carbon\Carbon::parse($res['time_slot'])->addHour()->format('g:i') }}<br>
                            @endforeach
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Board Game:</span>
                        <span class="font-bold">{{ $reservations[0]['game'] ?? 'Not selected' }}</span>
                    </div>
                </div>
            </div>
            @endif

            <p class="text-md italic mt-10 opacity-75">Get ready for your next mental storm.</p>
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('reservation.index') }}" class="inline-block bg-brand-subtitle hover:bg-brand-title text-white py-4 px-10 rounded-3xl text-xl font-bold transition-all">
                Make Another Reservation
            </a>
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('introduction') }}" class="inline-block bg-brand-subtitle hover:bg-brand-title text-white py-4 px-10 rounded-3xl text-xl font-bold transition-all">
                OK
            </a>
        </div>
    </main>

    <footer class="text-center py-6 text-brand-header-text bg-brand-header mt-12">
        <p class="text-sm">&copy; 2026 Cerebral Corner. All Rights Reserved.</p>
    </footer>
</body>
</html>