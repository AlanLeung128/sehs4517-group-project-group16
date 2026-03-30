<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation - Cerebral Corner</title>
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
        <div class="flex justify-between items-center max-w-4xl mx-auto">
            <a href="introduction.php" class="text-brand-header-text hover:opacity-80">
                <h1 class="font-slackey text-2xl m-0">Cerebral Corner</h1>
            </a>
            <div class="flex items-center gap-4">
                <span class="text-sm">Welcome, <?php echo htmlspecialchars($email); ?></span>
                <a href="logout.php" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700">Logout</a>
            </div>
        </div>
    </header>
    
    <main class="max-w-lg mx-auto my-10 px-5">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="font-slackey text-3xl text-brand-title text-center mb-6">🎮 Make a Reservation</h2>
            
            <form method="post" action="reservation-process.php">
                <div class="mb-4">
                    <label class="block text-brand-text font-bold mb-2">Date</label>
                    <input type="date" name="date" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-brand-subtitle">
                </div>
                
                <div class="mb-4">
                    <label class="block text-brand-text font-bold mb-2">Time Slot</label>
                    <select name="timeslot" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-brand-subtitle">
                        <option value="">Select time slot</option>
                        <option value="2-4 PM">2:00 PM - 4:00 PM</option>
                        <option value="6-9 PM">6:00 PM - 9:00 PM</option>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label class="block text-brand-text font-bold mb-2">Table / Room</label>
                    <select name="table" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-brand-subtitle">
                        <option value="">Select a table</option>
                        <option value="Standard Table (1-4 players)">🎲 Standard Table (1-4 players)</option>
                        <option value="Premium Table (1-6 players)">⭐ Premium Table (1-6 players)</option>
                        <option value="Dragon's Den - Private Room (8 players)">🐉 Dragon's Den - Private Room (8 players)</option>
                    </select>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-brand-subtitle text-white py-3 rounded-md font-bold transition-colors duration-300 hover:bg-brand-title">Reserve</button>
                    <button type="reset" class="flex-1 bg-gray-400 text-white py-3 rounded-md font-bold transition-colors duration-300 hover:bg-gray-500">Clear</button>
                    <a href="introduction.php" class="flex-1 bg-gray-500 text-white py-3 rounded-md font-bold transition-colors duration-300 hover:bg-gray-700 text-center">Cancel</a>
                </div>
            </form>
        </div>
    </main>
    
    <footer class="text-center p-5 mt-10 bg-brand-header text-brand-header-text">
        <p>&copy; 2026 Cerebral Corner. All Rights Reserved.</p>
    </footer>
</body>
</html>
