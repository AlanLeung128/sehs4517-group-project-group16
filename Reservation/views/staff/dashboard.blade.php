<!DOCTYPE html>
<html lang="zh-HK">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>員工後台 | Boardgame Café</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-900 text-white font-sans">
    <div class="max-w-7xl mx-auto p-8">

        <!-- 標題 -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold flex items-center gap-3">
                <i class="fas fa-coffee"></i> 員工後台
            </h1>
            <a href="/logout" class="bg-red-600 hover:bg-red-500 px-8 py-3 rounded-3xl font-bold">登出</a>
        </div>

        <!-- 1. 當日預約及售賣記錄 -->
        <div class="mb-12">
            <h2 class="text-2xl mb-4 flex items-center gap-2">
                <i class="fas fa-history"></i> 當日預約及售賣記錄
            </h2>
            <div class="max-h-80 overflow-y-auto bg-gray-800 rounded-3xl p-4">
                <table class="w-full text-sm" id="recordTable">
                    <thead class="sticky top-0 bg-gray-700">
                        <tr>
                            <th class="p-3 text-left">時間</th>
                            <th class="p-3 text-left">類型</th>
                            <th class="p-3 text-left">項目</th>
                            <th class="p-3 text-left">顧客</th>
                            <th class="p-3 text-right">金額</th>
                        </tr>
                    </thead>
                    <tbody id="recordBody" class="divide-y divide-gray-700"></tbody>
                </table>
            </div>
        </div>

        <!-- 2. 今日預約一覽 -->
        <h2 class="text-2xl mb-6 flex items-center gap-2">
            <i class="fas fa-calendar"></i> 今日預約一覽（{{ date('Y-m-d') }}）
        </h2>
        <div class="grid grid-cols-4 gap-6" id="spaceCards"></div>

        <!-- 展開詳細面板 -->
        <div id="detailPanel" class="hidden mt-12 bg-gray-800 rounded-3xl p-8">
            <div class="flex justify-between mb-6">
                <h3 id="detailTitle" class="text-2xl font-semibold"></h3>
                <button onclick="hideDetail()" class="text-3xl text-gray-400 hover:text-white">×</button>
            </div>
            <div id="detailContent"></div>
        </div>

        <!-- 3. 即時賣咖啡 -->
        <div class="grid grid-cols-2 gap-8 mt-16">
            <div class="bg-gray-800 p-8 rounded-3xl">
                <h3 class="text-xl mb-6 flex items-center gap-2"><i class="fas fa-coffee"></i> 即時賣咖啡</h3>
                <input type="text" id="coffeeCustomerId" placeholder="顧客 ID（可留空）" class="w-full p-4 rounded-2xl bg-gray-700 mb-6">

                <div class="flex justify-end mb-4">
                    <button onclick="addSellCoffeeRow()" class="bg-amber-600 text-white px-6 py-2 rounded-3xl font-bold flex items-center gap-2">
                        <span class="text-2xl">+</span> 新增咖啡
                    </button>
                </div>
                <div id="sellCoffeeContainer" class="space-y-4"></div>

                <div class="mt-6 bg-gray-700 p-4 rounded-2xl text-right text-lg">
                    總金額：<span id="coffeeTotal" class="font-bold text-amber-400">HK$ 0</span>
                </div>
                <button onclick="sellCoffee()" class="mt-4 w-full bg-amber-600 py-4 rounded-3xl font-bold">確認售賣</button>
            </div>

            <!-- 4. 租 / 賣桌遊 -->
            <div class="bg-gray-800 p-8 rounded-3xl">
                <h3 class="text-xl mb-6 flex items-center gap-2"><i class="fas fa-gamepad"></i> 租 / 賣桌遊</h3>
                <input type="text" id="gameCustomerId" placeholder="顧客 ID（可留空）" class="w-full p-4 rounded-2xl bg-gray-700 mb-6">

                <div class="flex justify-end mb-4">
                    <button onclick="addSellGameRow()" class="bg-purple-600 text-white px-6 py-2 rounded-3xl font-bold flex items-center gap-2">
                        <span class="text-2xl">+</span> 新增桌遊
                    </button>
                </div>
                <div id="sellGameContainer" class="space-y-4"></div>

                <div class="mt-6 bg-gray-700 p-4 rounded-2xl text-right text-lg">
                    總金額：<span id="gameTotal" class="font-bold text-purple-400">HK$ 0</span>
                </div>
                <button onclick="sellGame()" class="mt-4 w-full bg-purple-600 py-4 rounded-3xl font-bold">確認售賣</button>
            </div>
        </div>
    </div>

    <script>
        const spaces = ["Table 1 (4人 公共)", "Table 2 (4人 公共)", "Table 3 (4人 公共)", "Dragon Den (4人 包房)", "Wizard’s Corner (4人 包房)", "Epic Hall A (10人)", "Epic Hall B (10人)", "Legendary Lounge (16人)"];
        let reservations = {};
        let records = [];

        function getFutureTimeSlots() {
            const now = new Date();
            const currentHour = now.getHours();
            return Array.from({length: 14}, (_, i) => {
                const h = 8 + i;
                if (h <= currentHour) return null;
                return `${h.toString().padStart(2,'0')}:00-${(h+1).toString().padStart(2,'0')}:00`;
            }).filter(Boolean);
        }

        function renderSpaceCards() {
            const container = document.getElementById('spaceCards');
            container.innerHTML = spaces.map(space => {
                const booked = reservations[space] || [];
                const futureCount = getFutureTimeSlots().length - booked.length;
                return `
                <div onclick="showDetail('${space}')" class="bg-gray-800 hover:bg-gray-700 p-6 rounded-3xl cursor-pointer transition-all">
                    <div class="text-3xl mb-3">🪑</div>
                    <div class="font-bold text-lg mb-2">${space}</div>
                    <div class="text-red-400 text-sm">已預約 ${booked.length} 個時段</div>
                    <div class="text-green-400 text-sm">${futureCount} 個時段可即時接待</div>
                </div>`;
            }).join('');
        }

        function showDetail(space) {
            const panel = document.getElementById('detailPanel');
            const title = document.getElementById('detailTitle');
            const content = document.getElementById('detailContent');

            title.textContent = `${space} - 今日時段`;
            const bookedList = reservations[space] || [];
            const futureSlots = getFutureTimeSlots();

            let html = `<div class="mb-8"><p class="text-red-400 mb-3">已預約時段</p>`;
            if (bookedList.length === 0) {
                html += `<p class="text-green-400">目前沒有預約</p>`;
            } else {
                html += `<div class="grid grid-cols-2 gap-4">`;
                bookedList.forEach((b, i) => {
                    const customer = b.customerId ? `${b.customerId} (${b.name || '未知客人'})` : '未知客人';
                    html += `
                    <div class="bg-gray-700 p-4 rounded-2xl flex justify-between items-center">
                        <div><div class="font-medium">${b.time}</div><div class="text-xs text-gray-400">${customer}</div></div>
                        <button onclick="cancelBooking('${space}', ${i}); event.stopImmediatePropagation();" class="bg-red-600 text-white px-4 py-1 text-xs rounded-2xl">取消</button>
                    </div>`;
                });
                html += `</div>`;
            }
            html += `</div>`;

            html += `<div><p class="text-green-400 mb-3">空閒時段（點擊新增線下預約）</p><div class="grid grid-cols-4 gap-3">`;
            futureSlots.forEach(slot => {
                const isBooked = bookedList.some(b => b.time === slot);
                html += `<button onclick="${isBooked ? '' : `quickAddWalkin('${space}', '${slot}'); event.stopImmediatePropagation();`}" 
                                 class="${isBooked ? 'bg-gray-600 text-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-500'} p-3 rounded-2xl text-sm">${slot}</button>`;
            });
            html += `</div></div>`;

            content.innerHTML = html;
            panel.classList.remove('hidden');
            panel.scrollIntoView({ behavior: 'smooth' });
        }

        function hideDetail() {
            document.getElementById('detailPanel').classList.add('hidden');
        }

        function cancelBooking(space, index) {
            if (confirm('確定取消此預約？')) {
                reservations[space].splice(index, 1);
                hideDetail();
                renderSpaceCards();
            }
        }

        function quickAddWalkin(space, time) {
            const inputHTML = `
                <div class="mt-6 bg-gray-700 p-6 rounded-3xl">
                    <p class="mb-3">新增線下預約 - ${space} ${time}</p>
                    <input type="text" id="quickCustomerId" placeholder="顧客 ID（可留空）" class="w-full p-4 rounded-2xl bg-gray-800 mb-4">
                    <button onclick="confirmQuickAdd('${space}', '${time}')" class="w-full bg-green-600 py-4 rounded-3xl font-bold">確認新增</button>
                </div>`;
            document.getElementById('detailContent').innerHTML += inputHTML;
        }

        function confirmQuickAdd(space, time) {
            const customerId = document.getElementById('quickCustomerId').value || '未知';
            if (!reservations[space]) reservations[space] = [];
            reservations[space].push({time, customerId, name: ''});
            hideDetail();
            renderSpaceCards();
            addRecordRow('線下', `${space} ${time}`, customerId, 'HK$ 0');
        }

        function renderRecords() {
            const tbody = document.getElementById('recordBody');
            tbody.innerHTML = records.map(r => `
                <tr class="hover:bg-gray-700">
                    <td class="p-3">${r.time}</td>
                    <td class="p-3">${r.type}</td>
                    <td class="p-3">${r.item}</td>
                    <td class="p-3">${r.customer}</td>
                    <td class="p-3 text-right font-bold">${r.amount}</td>
                </tr>
            `).join('');
        }

        function addRecordRow(type, item, customer = '未知', amount = 'HK$ 0') {
            const now = new Date();
            const time = `${now.getHours().toString().padStart(2,'0')}:${now.getMinutes().toString().padStart(2,'0')}`;
            records.unshift({ time, type, item, customer, amount });
            renderRecords();
        }

        /* ==================== 賣咖啡 ==================== */
        function addSellCoffeeRow() {
            const container = document.getElementById('sellCoffeeContainer');
            const row = document.createElement('div');
            row.className = 'flex gap-3 bg-gray-700 p-4 rounded-3xl items-center';
            row.innerHTML = `
                <select class="flex-1 bg-gray-800 rounded-2xl p-4" onchange="if(this.value==='其他') this.nextElementSibling.style.display='block'; else this.nextElementSibling.style.display='none'; calcCoffeeTotal()">
                    <option value="">選擇咖啡</option>
                    <option value="Espresso">Espresso</option>
                    <option value="Cappuccino">Cappuccino</option>
                    <option value="Matcha Latte">Matcha Latte</option>
                    <option value="Dragon’s Brew">Dragon’s Brew</option>
                    <option value="其他">其他</option>
                </select>
                <input type="text" placeholder="輸入咖啡名稱" class="hidden flex-1 bg-gray-800 rounded-2xl p-4" style="display:none;" onchange="calcCoffeeTotal()">
                <input type="number" placeholder="單價" min="1" value="38" class="w-28 bg-gray-800 rounded-2xl p-4 text-white" onchange="calcCoffeeTotal()">
                <input type="number" placeholder="數量" min="1" value="1" class="w-24 bg-gray-800 rounded-2xl p-4 text-white" onchange="calcCoffeeTotal()">
                <button onclick="this.parentElement.remove();calcCoffeeTotal()" class="text-red-400 text-3xl px-3">×</button>
            `;
            container.appendChild(row);
        }

        function calcCoffeeTotal() {
            let total = 0;
            const rows = document.querySelectorAll('#sellCoffeeContainer > div');
            rows.forEach(row => {
                const price = parseFloat(row.querySelectorAll('input')[1].value) || 0;
                const qty = parseFloat(row.querySelectorAll('input')[2].value) || 0;
                total += price * qty;
            });
            document.getElementById('coffeeTotal').textContent = `HK$ ${total}`;
        }

        function sellCoffee() {
            const customerId = document.getElementById('coffeeCustomerId').value || '未知';
            let detail = '';
            let total = 0;
            const rows = document.querySelectorAll('#sellCoffeeContainer > div');
            rows.forEach(row => {
                let name = row.querySelector('select').value;
                if (name === '其他') name = row.querySelector('input').value || '自訂咖啡';
                const price = parseFloat(row.querySelectorAll('input')[1].value) || 0;
                const qty = parseFloat(row.querySelectorAll('input')[2].value) || 0;
                if (qty > 0) {
                    detail += `咖啡 - ${name} × ${qty} ($${price})<br>`;
                    total += price * qty;
                }
            });
            if (!detail) return alert('請至少新增一項咖啡');
            addRecordRow('線上', detail, customerId, `HK$ ${total}`);
            alert(`☕ 咖啡售賣成功！\n總金額 HK$ ${total}`);
            document.getElementById('sellCoffeeContainer').innerHTML = '';
            document.getElementById('coffeeTotal').textContent = 'HK$ 0';
        }

        /* ==================== 租 / 賣桌遊 ==================== */
        function addSellGameRow() {
            const container = document.getElementById('sellGameContainer');
            const row = document.createElement('div');
            row.className = 'flex gap-3 bg-gray-700 p-4 rounded-3xl items-center';
            row.innerHTML = `
                <select class="flex-1 bg-gray-800 rounded-2xl p-4" onchange="if(this.value==='其他') this.nextElementSibling.style.display='block'; else this.nextElementSibling.style.display='none'; calcGameTotal()">
                    <option value="">選擇桌遊</option>
                    <option value="Catan">Catan</option>
                    <option value="Ticket to Ride">Ticket to Ride</option>
                    <option value="Wingspan">Wingspan</option>
                    <option value="Pandemic">Pandemic</option>
                    <option value="Exploding Kittens">Exploding Kittens</option>
                    <option value="D&amp;D Starter Set">D&amp;D Starter Set</option>
                    <option value="其他">其他</option>
                </select>
                <input type="text" placeholder="輸入桌遊名稱" class="hidden flex-1 bg-gray-800 rounded-2xl p-4" style="display:none;" onchange="calcGameTotal()">
                <select class="w-24 bg-gray-800 rounded-2xl p-4">
                    <option value="租">租</option>
                    <option value="賣">賣</option>
                </select>
                <input type="number" placeholder="單價" min="1" value="120" class="w-28 bg-gray-800 rounded-2xl p-4 text-white" onchange="calcGameTotal()">
                <input type="number" placeholder="數量" min="1" value="1" class="w-24 bg-gray-800 rounded-2xl p-4 text-white" onchange="calcGameTotal()">
                <button onclick="this.parentElement.remove();calcGameTotal()" class="text-red-400 text-3xl px-3">×</button>
            `;
            container.appendChild(row);
        }

        function calcGameTotal() {
            let total = 0;
            const rows = document.querySelectorAll('#sellGameContainer > div');
            rows.forEach(row => {
                const price = parseFloat(row.querySelectorAll('input')[2].value) || 0;
                const qty = parseFloat(row.querySelectorAll('input')[3].value) || 0;
                total += price * qty;
            });
            document.getElementById('gameTotal').textContent = `HK$ ${total}`;
        }

        function sellGame() {
            const customerId = document.getElementById('gameCustomerId').value || '未知';
            let detail = '';
            let total = 0;
            const rows = document.querySelectorAll('#sellGameContainer > div');
            rows.forEach(row => {
                let name = row.querySelector('select').value;
                if (name === '其他') name = row.querySelector('input').value || '自訂桌遊';
                const action = row.querySelector('select:nth-child(3)').value;
                const price = parseFloat(row.querySelectorAll('input')[2].value) || 0;
                const qty = parseFloat(row.querySelectorAll('input')[3].value) || 0;
                if (qty > 0) {
                    detail += `桌遊 - ${name} (${action}) × ${qty} ($${price})<br>`;
                    total += price * qty;
                }
            });
            if (!detail) return alert('請至少新增一項桌遊');
            addRecordRow('線上', detail, customerId, `HK$ ${total}`);
            alert(`🎲 桌遊售賣成功！\n總金額 HK$ ${total}`);
            document.getElementById('sellGameContainer').innerHTML = '';
            document.getElementById('gameTotal').textContent = 'HK$ 0';
        }

        window.onload = () => {
            renderSpaceCards();
            renderRecords();
        };
    </script>
</body>
</html>