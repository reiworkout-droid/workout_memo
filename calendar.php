<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>筋トレメモカレンダー</title>
<style>
  body { font-family: sans-serif; padding: 20px; }
  table { border-collapse: collapse; width: 100%; max-width: 100%; }
  th, td { border: 1px solid #ccc; text-align: center; padding: 8px; cursor: pointer; }
  @media (max-width: 480px) { th, td { padding: 4px; font-size: 14px; } }
  th { background-color: oklch(0.9 0.15 500);}
  td.selected { background-color: #87CEFA; }
  #calendarHeader, #prevMonth, #nextMonth {font-size: 20px; margin: 8px 0px;}
</style>
</head>
<body>
<h2>日付を選択してください</h2>

<div id="calendarContainer">
    <div id="calendarHeader">
        <button id="prevMonth" onclick="changeMonth(-1)">‹</button>
        <span id="monthYear"></span>
        <button id="nextMonth" onclick="changeMonth(1)">›</button>
    </div>
    <div id="calendar"></div>
</div>

<script>
let today = new Date();
let currentYear = today.getFullYear();
let currentMonth = today.getMonth();

function drawCalendar(year, month) {
    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    // 年月表示
    document.getElementById('monthYear').textContent = `${year}年 ${month + 1}月`;

    let html = `<table>
        <tr><th>日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th></tr>
        <tr>`;

    for (let i = 0; i < firstDay; i++) html += '<td></td>';

    for (let day = 1; day <= lastDate; day++) {
        const isToday = day === today.getDate() && month === today.getMonth() && year === today.getFullYear();
        html += `<td class="${isToday ? 'selected' : ''}" onclick="selectDate(${year}, ${month}, ${day})">${day}</td>`;
        if ((firstDay + day) % 7 === 0) html += '</tr><tr>';
    }

    html += '</tr></table>';
    document.getElementById('calendar').innerHTML = html;
}

function changeMonth(diff) {
    currentMonth += diff;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    drawCalendar(currentYear, currentMonth);
}

function selectDate(year, month, day) {
    const date = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    window.location.href = `index.php?date=${date}`;
}

// 初期描画
drawCalendar(currentYear, currentMonth);
</script>
</body>
</html>
