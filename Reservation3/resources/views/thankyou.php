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

// Fetch the most recent reservation to display details
// Assumes your table name is 'reservations'
$sql = "SELECT * FROM reservations ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$reservation = $result->fetch_assoc();
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
            alt="Logo" 
            class="w-24 h-24 rounded-full mx-auto mb-4">
        <h1 class="font-slackey text-5xl m-0">Cerebral Corner</h1>
        <p class="font-slackey text-2xl opacity-90 mt-2">This is your brain playground.</p>
    </header>

    <main class="max-w-3xl mx-auto my-10 px-5">
        <section class="bg-white p-10 mb-5 rounded-3xl shadow-md text-brand-text text-center border border-[#d4b89e]">
            <h2 class="font-slackey text-4xl text-brand-title mb-4">Thank You!</h2>
            <div class="w-16 h-1 bg-brand-subtitle mx-auto mb-6"></div>
            
            <p class="text-lg mb-8">
                Your submission was successful. We are excited to have you join our community!
            </p>

            <?php if ($reservation): ?>
            <div class="bg-brand-bg/50 rounded-2xl p-6 text-left border border-brand-subtitle/20">
                <h3 class="text-xl font-bold text-brand-subtitle mb-4 border-b border-brand-subtitle/10 pb-2">Reservation Summary</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium opacity-70">Date:</span>
                        <span class="font-bold"><?php echo htmlspecialchars($reservation['date']); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium opacity-70">Table / Room:</span>
                        <span class="font-bold"><?php echo htmlspecialchars($reservation['space']); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium opacity-70">Time Slots:</span>
                        <span class="font-bold">
                            <?php 
                                // Displays time slots. Assumes they are stored as a comma-separated string
                                echo htmlspecialchars($reservation['time_slots'] ?? $reservation['time'] ?? 'Not specified'); 
                            ?>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium opacity-70">Chosen Game:</span>
                        <span class="font-bold"><?php echo htmlspecialchars($reservation['game'] ?? 'None'); ?></span>
                    </div>
                </div>
            </div>
            <?php else: ?>
                <p class="text-red-600">No reservation data found.</p>
            <?php endif; ?>

            <p class="text-md italic opacity-80 mt-8">
                Get ready for your next mental storm.
            </p>
        </section>

        <nav class="text-center my-8">
            <a href="introduction.html" class="inline-block bg-brand-subtitle text-white py-3 px-8 rounded-full text-base font-bold transition-colors duration-300 hover:bg-brand-title shadow-lg">
                Return to Home
            </a>
        </nav>
    </main>

    <footer class="text-center p-5 mt-10 bg-brand-header text-brand-header-text">
        <p>&copy; 2026 Cerebral Corner. All Rights Reserved.</p>
    </footer>

</body>
</html>
