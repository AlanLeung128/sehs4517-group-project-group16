<!DOCTYPE html>
<html lang="zh-HK">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>查看當日預約 | Boardgame Café</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-amber-50 font-sans">
    <div class="max-w-6xl mx-auto p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-amber-900">查看當日預約</h1>
            <div class="flex gap-4">
                <a href="/reservation" class="bg-green-600 text-white px-6 py-3 rounded-3xl font-bold">前往預約</a>
                <a href="/logout" class="bg-gray-700 text-white px-6 py-3 rounded-3xl font-bold">登出</a>
            </div>
        </div>
        <p class="text-amber-700 mb-8">今天是 {{ $today }}（點擊位置查看所有時段）</p>

        <div class="grid grid-cols-4 gap-6" id="spaceGrid">
            @foreach(['Table 1 (4人 公共)', 'Table 2 (4人 公共)', 'Table 3 (4人 公共)', 'Dragon Den (4人 包房)', 'Wizard’s Corner (4人 包房)', 'Epic Hall A (10人)', 'Epic Hall B (10人)', 'Legendary Lounge (16人)'] as $space)
                <div onclick="showBookings('{{ $space }}')" 
                     class="bg-white p-6 rounded-3xl cursor-pointer hover:shadow-xl text-center border-2 border-transparent hover:border-amber-400">
                    <div class="text-2xl mb-2">🪑</div>
                    <div class="font-bold">{{ $space }}</div>
                </div>
            @endforeach
        </div>

        <!-- 展開詳細面板 -->
        <div id="bookingDetail" class="hidden mt-12 bg-white rounded-3xl p-8 shadow-xl">
            <h2 class="text-2xl font-semibold mb-4">所有時段 - <span id="selectedSpace" class="text-amber-900"></span></h2>
            <div id="bookedTimes" class="grid grid-cols-7 gap-3"></div>
        </div>
    </div>

    <script>
        const reservations = @json($reservations);

        // 所有營業時段（08:00-22:00）
        const allTimeSlots = Array.from({length: 14}, (_, i) => {
            const start = (8 + i).toString().padStart(2, '0') + ':00';
            const end   = (9 + i).toString().padStart(2, '0') + ':00';
            return `${start}-${end}`;
        });

        function showBookings(space) {
            document.getElementById('selectedSpace').textContent = space;
            const detail = document.getElementById('bookingDetail');
            const container = document.getElementById('bookedTimes');
            container.innerHTML = '';

            const bookedTimes = (reservations[space] || []).map(r => r.time_slot);

            allTimeSlots.forEach(slot => {
                const isBooked = bookedTimes.includes(slot);
                const div = document.createElement('div');
                
                if (isBooked) {
                    div.className = 'bg-gray-200 text-gray-500 p-4 rounded-2xl text-center font-medium cursor-not-allowed';
                    div.innerHTML = `${slot}<br><span class="text-xs">已被預約</span>`;
                } else {
                    div.className = 'bg-green-100 text-green-700 p-4 rounded-2xl text-center font-medium';
                    div.textContent = slot;
                }
                
                container.appendChild(div);
            });

            detail.classList.remove('hidden');
        }

        // 頁面載入時先渲染卡片
        window.onload = () => {
            // 如果你想在載入時預先渲染卡片，可以在此加入 renderSpaceCards()，但目前保留原有結構
        };
    </script>
</body>
</html>