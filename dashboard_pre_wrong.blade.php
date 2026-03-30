<!DOCTYPE html>
<html lang="zh-HK">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>員工後台 | Cerebral Corner</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
<body class="bg-brand-bg text-brand-text font-sans">

    <!-- Header - 員工介面（已移除登出按鈕） -->
    <header class="bg-brand-header text-brand-header-text py-6">
        <div class="max-w-7xl mx-auto px-8 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <img 
                    src="{{ asset('images/logo.png') }}" 
                    alt="Cerebral Corner Logo" 
                    class="w-14 h-14 rounded-full border-4 border-white shadow-md">
                <div>
                    <h1 class="font-slackey text-4xl tracking-wide">Cerebral Corner</h1>
                    <p class="text-sm opacity-80">員工後台 • Brain Playground</p>
                </div>
            </div>
            
            <!-- 保留「查看當日預約」按鈕 -->
            <a href="/view-today" 
               class="bg-white text-brand-header px-6 py-3 rounded-3xl font-medium hover:bg-amber-100 transition-all flex items-center gap-2">
                <i class="fas fa-calendar"></i> 查看當日預約
            </a>
        </div>
    </header>

    <div class="max-w-7xl mx-auto p-8">

        <!-- 1. 當日預約及售賣記錄 -->
        <div class="mb-12">
            <h2 class="text-3xl font-bold mb-6 flex items-center gap-3 text-[#4a3f35]">
                <i class="fas fa-history"></i> 當日預約及售賣記錄
            </h2>
            <div class="card-bg rounded-3xl p-6 shadow-sm border border-[#d4b89e] max-h-96 overflow-y-auto">
                <table class="w-full text-sm" id="recordTable">
                    <thead class="sticky top-0 bg-white border-b border-[#d4b89e]">
                        <tr>
                            <th class="p-4 text-left font-semibold">項目ID</th>
                            <th class="p-4 text-left font-semibold">顧客ID</th>
                            <th class="p-4 text-left font-semibold">時間</th>
                            <th class="p-4 text-left font-semibold">名稱</th>
                            <th class="p-4 text-left font-semibold">電話號碼</th>
                            <th class="p-4 text-left font-semibold">類型</th>
                            <th class="p-4 text-left font-semibold">項目</th>
                            <th class="p-4 text-right font-semibold">金額</th>
                        </tr>
                    </thead>
                    <tbody id="recordBody" class="divide-y divide-[#e8d9c0]"></tbody>
                </table>
            </div>
        </div>

        <!-- 2. 今日預約一覽 -->
        <h2 class="text-3xl font-bold mb-6 flex items-center gap-3 text-[#4a3f35]">
            <i class="fas fa-calendar"></i> 今日預約一覽（{{ date('Y-m-d') }}）
        </h2>
        <div class="grid grid-cols-4 gap-6" id="spaceCards"></div>

        <!-- 展開詳細面板 -->
        <div id="detailPanel" class="hidden mt-12 card-bg rounded-3xl p-8 shadow-xl border border-[#d4b89e]">
            <div class="flex justify-between items-center mb-6">
                <h3 id="detailTitle" class="text-3xl font-semibold text-[#4a3f35]"></h3>
                <button onclick="hideDetail()" 
                        class="text-4xl text-gray-400 hover:text-red-600 transition-colors">×</button>
            </div>
            <div id="detailContent"></div>
        </div>

        <!-- 3. 即時賣咖啡 + 租/賣桌遊 -->
        <div class="grid grid-cols-2 gap-8 mt-16">

            <!-- 即時賣咖啡 -->
            <div class="card-bg p-8 rounded-3xl shadow-sm border border-[#d4b89e]">
                <h3 class="text-2xl mb-6 flex items-center gap-3 text-[#4a3f35]">
                    <i class="fas fa-coffee"></i> 即時賣咖啡
                </h3>
                <input type="text" id="coffeeCustomerPhone" 
                       placeholder="顧客電話號碼（可留空）" 
                       class="w-full p-4 rounded-2xl border border-[#d4b89e] focus:outline-none focus:border-[#8c6f4f] mb-6">

                <div class="flex justify-end mb-4">
                    <button onclick="addSellCoffeeRow()" 
                            class="bg-[#8c6f4f] text-white px-6 py-3 rounded-3xl font-bold flex items-center gap-2 hover:bg-[#4a3f35]">
                        <span class="text-2xl">+</span> 新增咖啡
                    </button>
                </div>
                <div id="sellCoffeeContainer" class="space-y-4"></div>

                <div class="mt-8 bg-white p-5 rounded-2xl text-right text-xl border border-[#d4b89e]">
                    總金額：<span id="coffeeTotal" class="font-bold accent">HK$ 0</span>
                </div>
                <button onclick="sellCoffee()" 
                        class="mt-6 w-full bg-[#8c6f4f] hover:bg-[#4a3f35] py-5 rounded-3xl font-bold text-white text-lg transition-all">
                    確認售賣
                </button>
            </div>

            <!-- 租 / 賣桌遊 -->
            <div class="card-bg p-8 rounded-3xl shadow-sm border border-[#d4b89e]">
                <h3 class="text-2xl mb-6 flex items-center gap-3 text-[#4a3f35]">
                    <i class="fas fa-gamepad"></i> 租 / 賣桌遊
                </h3>
                <input type="text" id="gameCustomerPhone" 
                       placeholder="顧客電話號碼（可留空）" 
                       class="w-full p-4 rounded-2xl border border-[#d4b89e] focus:outline-none focus:border-[#8c6f4f] mb-6">

                <div class="flex justify-end mb-4">
                    <button onclick="addSellGameRow()" 
                            class="bg-[#8c6f4f] text-white px-6 py-3 rounded-3xl font-bold flex items-center gap-2 hover:bg-[#4a3f35]">
                        <span class="text-2xl">+</span> 新增桌遊
                    </button>
                </div>
                <div id="sellGameContainer" class="space-y-4"></div>

                <div class="mt-8 bg-white p-5 rounded-2xl text-right text-xl border border-[#d4b89e]">
                    總金額：<span id="gameTotal" class="font-bold accent">HK$ 0</span>
                </div>
                <button onclick="sellGame()" 
                        class="mt-6 w-full bg-[#8c6f4f] hover:bg-[#4a3f35] py-5 rounded-3xl font-bold text-white text-lg transition-all">
                    確認售賣
                </button>
            </div>
        </div>
    </div>

    <script>
        const spaces = [
            "Table 1 (4人 公開)", "Table 2 (4人 公開)", "Table 3 (4人 公開)",
            "Dragon Den (4人 包房)", "Wizard’s Corner (4人 包房)",
            "Epic Hall A (10人 包房)", "Epic Hall B (10人 包房)",
            "Legendary Lounge (16人 包房)"
        ];

        let reservations = {};
        let records = [];
        let recordCounter = 0;

        // 簡單顧客資料庫（電話 → 顧客ID + 姓名）
        const customerDB = {
            "91234567": {id: "CUST001", name: "陳大文"},
            "98765432": {id: "CUST002", name: "林小美"},
            "55556666": {id: "CUST003", name: "王小強"}
        };

        function lookupCustomer(phone) {
            if (!phone) return {id: "未知", name: "未知客人"};
            return customerDB[phone] || {id: "未知", name: "未知客人"};
        }

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
                <div onclick="showDetail('${space}')" 
                     class="card-bg hover:shadow-md p-6 rounded-3xl cursor-pointer transition-all border border-[#d4b89e]">
                    <div class="text-4xl mb-4">🪑</div>
                    <div class="font-bold text-xl mb-2">${space}</div>
                    <div class="text-red-600 text-sm">已預約 ${booked.length} 個時段</div>
                    <div class="text-green-600 text-sm">${futureCount} 個時段可即時接待</div>
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

            let html = `<div class="mb-8"><p class="text-red-600 mb-3 font-medium">已預約時段</p>`;
            if (bookedList.length === 0) {
                html += `<p class="text-green-600">目前沒有預約</p>`;
            } else {
                html += `<div class="grid grid-cols-2 gap-4">`;
                bookedList.forEach((b, i) => {
                    const customer = b.customerId ? `${b.customerId} (${b.name || '未知客人'})` : '未知客人';
                    html += `
                    <div class="bg-white p-5 rounded-2xl flex justify-between items-center border border-[#d4b89e]">
                        <div>
                            <div class="font-medium">${b.time}</div>
                            <div class="text-xs text-gray-500">${customer}</div>
                        </div>
                        <button onclick="cancelBooking('${space}', ${i}); event.stopImmediatePropagation();" 
                                class="bg-red-600 text-white px-5 py-2 text-xs rounded-2xl hover:bg-red-700">取消</button>
                    </div>`;
                });
                html += `</div>`;
            }
            html += `</div>`;

            html += `<div><p class="text-green-600 mb-3 font-medium">空閒時段（點擊新增線下預約）</p><div class="grid grid-cols-4 gap-3">`;
            futureSlots.forEach(slot => {
                const isBooked = bookedList.some(b => b.time === slot);
                html += `<button onclick="${isBooked ? '' : `quickAddWalkin('${space}', '${slot}'); event.stopImmediatePropagation();`}" 
                                 class="${isBooked ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-green-100 hover:bg-green-200 text-green-700'} p-4 rounded-2xl text-sm font-medium">${slot}</button>`;
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
            // 防止重複出現
            if (document.getElementById('quickAddForm')) return;

            const inputHTML = `
                <div id="quickAddForm" class="mt-8 bg-white p-6 rounded-3xl border border-[#d4b89e]">
                    <p class="mb-4 font-medium">新增線下預約 - ${space} ${time}</p>
                    <input type="text" id="quickCustomerPhone" placeholder="顧客電話號碼（可留空）" 
                           class="w-full p-4 rounded-2xl border border-[#d4b89e] mb-4">
                    <button onclick="confirmQuickAdd('${space}', '${time}')" 
                            class="w-full bg-green-600 text-white py-4 rounded-3xl font-bold">確認新增</button>
                </div>`;
            document.getElementById('detailContent').innerHTML += inputHTML;
        }

        function confirmQuickAdd(space, time) {
            const phone = document.getElementById('quickCustomerPhone').value.trim() || '';
            const lookup = lookupCustomer(phone);
            if (!reservations[space]) reservations[space] = [];
            reservations[space].push({time, customerId: lookup.id, name: lookup.name, phone: phone});
            hideDetail();
            renderSpaceCards();
            addRecordRow('線下預約', `${space} ${time}`, phone, lookup.name, lookup.id, 'HK$ 0');
        }

        function addRecordRow(type, item, phone = '', name = '未知', customerId = '未知', amount = 'HK$ 0') {
            const now = new Date();
            const time = `${now.getHours().toString().padStart(2,'0')}:${now.getMinutes().toString().padStart(2,'0')}`;
            recordCounter++;
            const itemId = `REC-${recordCounter.toString().padStart(4, '0')}`;
            records.unshift({itemId, customerId, time, name, phone, type, item, amount});
            renderRecords();
        }

        function renderRecords() {
            const tbody = document.getElementById('recordBody');
            tbody.innerHTML = records.map(r => `
                <tr class="hover:bg-[#f8f1e3]">
                    <td class="p-4">${r.itemId}</td>
                    <td class="p-4">${r.customerId}</td>
                    <td class="p-4">${r.time}</td>
                    <td class="p-4">${r.name}</td>
                    <td class="p-4">${r.phone}</td>
                    <td class="p-4">${r.type}</td>
                    <td class="p-4">${r.item}</td>
                    <td class="p-4 text-right font-bold text-[#8c6f4f]">${r.amount}</td>
                </tr>
            `).join('');
        }

        /* ==================== 賣咖啡 ==================== */
        function addSellCoffeeRow() {
            const container = document.getElementById('sellCoffeeContainer');
            const row = document.createElement('div');
            row.className = 'flex gap-3 bg-white p-5 rounded-3xl border border-[#d4b89e] items-center';
            row.innerHTML = `
                <select class="flex-1 border border-[#d4b89e] rounded-2xl p-4" onchange="if(this.value==='其他') this.nextElementSibling.style.display='block'; else this.nextElementSibling.style.display='none'; calcCoffeeTotal()">
                    <option value="">選擇咖啡</option>
                    <option value="Espresso">Espresso</option>
                    <option value="Cappuccino">Cappuccino</option>
                    <option value="Matcha Latte">Matcha Latte</option>
                    <option value="Dragon’s Brew">Dragon’s Brew</option>
                    <option value="其他">其他</option>
                </select>
                <input type="text" placeholder="輸入咖啡名稱" class="hidden flex-1 border border-[#d4b89e] rounded-2xl p-4" style="display:none;" onchange="calcCoffeeTotal()">
                <input type="number" placeholder="單價" min="1" value="38" class="w-28 border border-[#d4b89e] rounded-2xl p-4" onchange="calcCoffeeTotal()">
                <input type="number" placeholder="數量" min="1" value="1" class="w-24 border border-[#d4b89e] rounded-2xl p-4" onchange="calcCoffeeTotal()">
                <button onclick="this.parentElement.remove();calcCoffeeTotal()" class="text-red-500 text-3xl px-4">×</button>
            `;
            container.appendChild(row);
        }

        function calcCoffeeTotal() {
            let total = 0;
            const rows = document.querySelectorAll('#sellCoffeeContainer > div');
            rows.forEach(row => {
                const numInputs = row.querySelectorAll('input[type="number"]');
                const price = parseFloat(numInputs[0]?.value) || 0;
                const qty = parseFloat(numInputs[1]?.value) || 0;
                total += price * qty;
            });
            document.getElementById('coffeeTotal').textContent = `HK$ ${total}`;
        }

        function sellCoffee() {
            const phone = document.getElementById('coffeeCustomerPhone').value.trim() || '';
            const lookup = lookupCustomer(phone);
            let hasItem = false;
            const rows = document.querySelectorAll('#sellCoffeeContainer > div');
            rows.forEach(row => {
                let name = row.querySelector('select').value;
                if (name === '其他') name = row.querySelector('input').value || '自訂咖啡';
                const numInputs = row.querySelectorAll('input[type="number"]');
                const price = parseFloat(numInputs[0].value) || 0;
                const qty = parseFloat(numInputs[1].value) || 0;
                if (qty > 0) {
                    hasItem = true;
                    const detail = `咖啡 - ${name} × ${qty} ($${price})`;
                    addRecordRow('咖啡售賣', detail, phone, lookup.name, lookup.id, `HK$ ${price * qty}`);
                }
            });
            if (!hasItem) return alert('請至少新增一項咖啡');
            alert(`☕ 咖啡售賣成功！`);
            document.getElementById('sellCoffeeContainer').innerHTML = '';
            document.getElementById('coffeeTotal').textContent = 'HK$ 0';
        }

        /* ==================== 租 / 賣桌遊 ==================== */
        function addSellGameRow() {
            const container = document.getElementById('sellGameContainer');
            const row = document.createElement('div');
            row.className = 'flex gap-3 bg-white p-5 rounded-3xl border border-[#d4b89e] items-center';
            row.innerHTML = `
                <select class="flex-1 border border-[#d4b89e] rounded-2xl p-4" onchange="if(this.value==='其他') this.nextElementSibling.style.display='block'; else this.nextElementSibling.style.display='none'; calcGameTotal()">
                    <option value="">選擇桌遊</option>
                    <option value="Catan">Catan</option>
                    <option value="Ticket to Ride">Ticket to Ride</option>
                    <option value="Wingspan">Wingspan</option>
                    <option value="Pandemic">Pandemic</option>
                    <option value="Exploding Kittens">Exploding Kittens</option>
                    <option value="D&amp;D Starter Set">D&amp;D Starter Set</option>
                    <option value="其他">其他</option>
                </select>
                <input type="text" placeholder="輸入桌遊名稱" class="hidden flex-1 border border-[#d4b89e] rounded-2xl p-4" style="display:none;" onchange="calcGameTotal()">
                <select class="w-24 border border-[#d4b89e] rounded-2xl p-4">
                    <option value="租">租</option>
                    <option value="賣">賣</option>
                </select>
                <input type="number" placeholder="單價" min="1" value="120" class="w-28 border border-[#d4b89e] rounded-2xl p-4" onchange="calcGameTotal()">
                <input type="number" placeholder="數量" min="1" value="1" class="w-24 border border-[#d4b89e] rounded-2xl p-4" onchange="calcGameTotal()">
                <button onclick="this.parentElement.remove();calcGameTotal()" class="text-red-500 text-3xl px-4">×</button>
            `;
            container.appendChild(row);
        }

        function calcGameTotal() {
            let total = 0;
            const rows = document.querySelectorAll('#sellGameContainer > div');
            rows.forEach(row => {
                const numInputs = row.querySelectorAll('input[type="number"]');
                const price = parseFloat(numInputs[0]?.value) || 0;
                const qty = parseFloat(numInputs[1]?.value) || 0;
                total += price * qty;
            });
            document.getElementById('gameTotal').textContent = `HK$ ${total}`;
        }

        function sellGame() {
            const phone = document.getElementById('gameCustomerPhone').value.trim() || '';
            const lookup = lookupCustomer(phone);
            let hasItem = false;
            const rows = document.querySelectorAll('#sellGameContainer > div');
            rows.forEach(row => {
                let name = row.querySelector('select').value;
                if (name === '其他') name = row.querySelector('input').value || '自訂桌遊';
                const action = row.querySelectorAll('select')[1].value;
                const numInputs = row.querySelectorAll('input[type="number"]');
                const price = parseFloat(numInputs[0].value) || 0;
                const qty = parseFloat(numInputs[1].value) || 0;
                if (qty > 0) {
                    hasItem = true;
                    const detail = `桌遊 - ${name} (${action}) × ${qty} ($${price})`;
                    addRecordRow('桌遊售賣', detail, phone, lookup.name, lookup.id, `HK$ ${price * qty}`);
                }
            });
            if (!hasItem) return alert('請至少新增一項桌遊');
            alert(`🎲 桌遊售賣成功！`);
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
</DOCUMENT>