<!DOCTYPE html>
<html lang="zh-HK">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>預約你的桌遊時光 | Boardgame Café</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-amber-50 font-sans">
    <div class="max-w-6xl mx-auto p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-amber-900">預約你的桌遊時光</h1>
            <div class="flex gap-4">
                <a href="/view-today" class="bg-amber-700 text-white px-6 py-3 rounded-3xl font-bold">查看當日預約</a>
                <a href="/logout" class="bg-gray-700 text-white px-6 py-3 rounded-3xl font-bold">登出</a>
            </div>
        </div>

        <form action="{{ route('reservation.store') }}" method="POST" id="reservationForm">
            @csrf

            <!-- 日期 -->
            <div class="mb-8">
                <label class="block text-sm font-medium mb-3">預約日期</label>
                <div class="flex gap-4">
                    <button type="button" onclick="setDate(1)" class="flex-1 py-4 text-lg font-bold rounded-3xl border-2 border-amber-400 hover:bg-amber-100 date-btn">明天</button>
                    <button type="button" onclick="setDate(2)" class="flex-1 py-4 text-lg font-bold rounded-3xl border-2 border-amber-400 hover:bg-amber-100 date-btn">後天</button>
                </div>
                <input type="hidden" name="date" id="selectedDate">
            </div>

            <!-- 位置 -->
            <div class="mt-8">
                <h2 class="text-2xl font-semibold mb-4">選擇位置</h2>
                <div class="grid grid-cols-4 gap-4" id="spaceGrid"></div>
            </div>

            <!-- 時間（顯示 08:00-09:00） -->
            <div class="mt-8">
                <h2 class="text-2xl font-semibold mb-4">選擇時間（可多選）</h2>
                <div id="timeSlotGrid" class="grid grid-cols-8 gap-3"></div>
            </div>

            <!-- 桌遊（新增「其他」） -->
            <div class="mt-8">
                <h2 class="text-2xl font-semibold mb-4">選擇桌遊（至少一款）</h2>
                <div class="grid grid-cols-4 gap-4" id="gameGrid"></div>
                <div id="otherGameContainer" class="hidden mt-4">
                    <input type="text" name="other_game" placeholder="請輸入其他桌遊名稱" 
                           class="w-full p-4 border rounded-3xl text-lg">
                </div>
            </div>

            <!-- 預點咖啡 -->
            <div class="mt-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-semibold">預點咖啡</h2>
                    <button type="button" onclick="addCoffeeRow()" 
                            class="bg-green-600 text-white px-6 py-2 rounded-3xl font-bold flex items-center gap-2 hover:bg-green-500">
                        <span class="text-2xl leading-none">+</span> 新增咖啡
                    </button>
                </div>
                <div id="coffeeContainer" class="space-y-4"></div>
                <div class="mt-6 bg-white p-6 rounded-3xl text-2xl font-bold text-right border-2 border-green-300">
                    總金額：<span id="totalPrice" class="text-green-600">HK$ 0</span>
                </div>
            </div>

            <div class="mt-10 flex gap-4">
                <button type="submit" class="flex-1 bg-green-600 text-white py-5 text-xl rounded-3xl font-bold">確認預約並付款</button>
                <button type="button" onclick="clearAll()" class="flex-1 border-2 border-red-500 text-red-600 py-5 text-xl rounded-3xl font-bold">🗑️ 清除所有</button>
                <a href="/" class="flex-1 border-2 border-amber-800 text-amber-800 py-5 text-xl rounded-3xl font-bold text-center">取消</a>
            </div>
        </form>
    </div>

    <script>
        let selectedSpace = '';
        const timeSlots = Array.from({length: 14}, (_, i) => {
            const start = (8 + i).toString().padStart(2, '0') + ':00';
            const end = (9 + i).toString().padStart(2, '0') + ':00';
            return {display: `${start}-${end}`, value: start};
        });
        const spaces = ["Table 1 (4人 公共)", "Table 2 (4人 公共)", "Table 3 (4人 公共)", "Dragon Den (4人 包房)", "Wizard’s Corner (4人 包房)", "Epic Hall A (10人)", "Epic Hall B (10人)", "Legendary Lounge (16人)"];
        const games = ["Catan", "Ticket to Ride", "Wingspan", "Pandemic", "Exploding Kittens", "D&D Starter Set"];
        const coffeeOptions = [
            {name: "Espresso", price: 38},
            {name: "Cappuccino", price: 45},
            {name: "Matcha Latte", price: 48},
            {name: "Dragon’s Brew", price: 55}
        ];

        function setDate(days) {
            document.getElementById('selectedDate').value = new Date(Date.now() + days * 86400000).toISOString().split('T')[0];
            document.querySelectorAll('.date-btn').forEach(btn => btn.classList.remove('bg-amber-400', 'text-white'));
            event.target.classList.add('bg-amber-400', 'text-white');
        }

        function renderSpaces() {
            const grid = document.getElementById('spaceGrid');
            grid.innerHTML = spaces.map(s => `
                <label onclick="selectSpace('${s}', this)" class="p-6 border-2 border-transparent hover:border-amber-400 rounded-3xl text-center cursor-pointer bg-white transition-all">
                    🪑<br><span class="font-bold">${s}</span>
                </label>
            `).join('');
        }

        function selectSpace(space, el) {
            selectedSpace = space;
            document.querySelectorAll('#spaceGrid label').forEach(l => l.classList.remove('border-amber-400', 'bg-amber-100'));
            el.classList.add('border-amber-400', 'bg-amber-100');
            renderTimeSlots();
        }

        function renderTimeSlots() {
            const grid = document.getElementById('timeSlotGrid');
            grid.innerHTML = timeSlots.map(t => `
                <label class="flex items-center justify-center p-3 border rounded-2xl hover:bg-amber-100 cursor-pointer">
                    <input type="checkbox" name="time_slots[]" value="${t.value}" class="mr-2"> ${t.display}
                </label>
            `).join('');
        }

        function renderGames() {
            const grid = document.getElementById('gameGrid');
            grid.innerHTML = games.map(g => `
                <label class="p-4 border rounded-2xl text-center hover:bg-amber-100 cursor-pointer">
                    <input type="checkbox" name="games[]" value="${g}"> 🎲 ${g}
                </label>
            `).join('') + `
                <label onclick="toggleOtherGame(this)" class="p-4 border rounded-2xl text-center hover:bg-amber-100 cursor-pointer flex items-center justify-center gap-2">
                    <input type="checkbox" id="otherCheckbox"> 🎲 其他
                </label>
            `;
        }

        function toggleOtherGame(el) {
            const container = document.getElementById('otherGameContainer');
            container.classList.toggle('hidden');
            if (!container.classList.contains('hidden')) {
                container.querySelector('input').focus();
            }
        }

        function addCoffeeRow() {
            const container = document.getElementById('coffeeContainer');
            const index = container.children.length;
            const row = document.createElement('div');
            row.className = 'flex items-center gap-4 bg-white p-6 rounded-3xl border';
            row.innerHTML = `
                <select name="coffees[]" onchange="calcTotal()" class="flex-1 border rounded-2xl px-4 py-3 text-lg">
                    <option value="">選擇咖啡...</option>
                    ${coffeeOptions.map(c => `<option value="${c.name}">${c.name} (HK$${c.price})</option>`).join('')}
                </select>
                <div class="flex items-center">
                    <span class="mr-3 text-sm font-medium">數量</span>
                    <select name="quantities[]" onchange="calcTotal()" class="border rounded-2xl px-5 py-3 text-lg">
                        ${Array.from({length:9}, (_,k) => `<option value="${k+1}">${k+1}</option>`).join('')}
                    </select>
                </div>
                <button type="button" onclick="this.parentElement.remove();calcTotal()" class="text-red-500 text-2xl px-3">×</button>
            `;
            container.appendChild(row);
        }

        function calcTotal() {
            let total = 0;
            const rows = document.querySelectorAll('#coffeeContainer > div');
            rows.forEach(row => {
                const coffeeSelect = row.querySelector('select[name="coffees[]"]');
                const qtySelect = row.querySelector('select[name="quantities[]"]');
                if (coffeeSelect && coffeeSelect.value) {
                    const coffee = coffeeOptions.find(c => c.name === coffeeSelect.value);
                    total += coffee.price * parseInt(qtySelect.value || 1);
                }
            });
            document.getElementById('totalPrice').textContent = `HK$ ${total}`;
        }

        function clearAll() {
            if (confirm('確定清除所有選擇？')) {
                document.getElementById('reservationForm').reset();
                document.getElementById('selectedDate').value = '';
                document.getElementById('coffeeContainer').innerHTML = '';
                document.getElementById('otherGameContainer').classList.add('hidden');
                document.getElementById('totalPrice').textContent = 'HK$ 0';
                renderSpaces();
                renderTimeSlots();
                renderGames();
            }
        }

        window.onload = () => {
            renderSpaces();
            renderGames();
            setDate(1);   // 預設明天
        };
    </script>
</body>
</html>