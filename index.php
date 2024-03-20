<?php
session_start();

// إنشاء أو فتح قاعدة البيانات SQLite
$db = new SQLite3('visitors.db');
$db->exec("CREATE TABLE IF NOT EXISTS visit_count (id INTEGER PRIMARY KEY, count INTEGER DEFAULT 1)");

// تحديث عدد الزيارات
$visitResult = $db->query("SELECT count FROM visit_count WHERE id = 1");
$visitData = $visitResult->fetchArray();
$totalVisits = $visitData ? $visitData['count'] : 0;

// إذا كانت هذه أول زيارة للمستخدم الحالي
if (!isset($_SESSION['visited'])) {
    $_SESSION['visited'] = true;
    $totalVisits++;
    if ($visitData) {
        $db->exec("UPDATE visit_count SET count = $totalVisits WHERE id = 1");
    } else {
        $db->exec("INSERT INTO visit_count (id, count) VALUES (1, $totalVisits)");
    }
}

// الحصول على عدد الزوار الحاليين
$currentVisitors = count($_SESSION);

// تحميل البيانات
$jsonData = file_get_contents('Quran.json');
$reciters = json_decode($jsonData, true)['reciters'];
$suras = explode("\n", file_get_contents('suras.txt'));
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <title>موقع القران الكريم</title>
          <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:title" content="استمع للقران الكريم" />
  <meta property="og:description" content="موقع للاستماع للقران الكريم للقراء المشهورين على مدار الساعة" />
  <meta property="og:url" content="https://turki.be-eb.net" />
  <meta property="og:image" content="https://quran.be-eb.net/logo.png" />

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="استمع للقران الكريم " />
  <meta name="twitter:description" content="موقع للاستماع الى القران الكريم للقراء المشهورين على مدار الساعة" />
  <meta name="twitter:image" content="https://quran.be-eb.net/qu.png" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .audio-player { position: fixed; bottom: 10px; left: 10px; background-color: white; border: 1px solid #ddd; padding: 30px; border-radius: 5px; display: none; }
        .navbar { display: flex; align-items: center; padding: 10px; background-color: #fff; border-bottom: 1px solid #ddd; }
        .back-button { font-size: 30px; color: #555; text-decoration: none; margin-left: 10px; }
        .logo { display: block; margin: 10px auto; }
    </style>
</head>
<body>
<div class="visitor-info">
        <p>عدد الزوار الكلي: <?php echo $totalVisits; ?></p>
    </div>
<div id="audioImage" style="display: none;">
    <img src="qu.png" alt="Audio Image" />
</div>
    <style>
        .visitor-info {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f3f4f6;
            padding: 1px;
            text-align: center;
            border-top: 1px solid #ddd;
        }
    </style>
    <div class="container mx-auto px-4" style="padding-bottom: 70px;">
        <img src="logo.png" alt="Logo" class="logo" id="siteLogo" />
        <div id="searchContainer" class="flex justify-center mb-4">
            <input type="text" id="searchInput" oninput="searchReciters()" placeholder="ابحث عن شيخ" class="border p-2">
        </div>
        <div id="tabs">
            <div id="recitersTab" class="tab-content active">
                <div id="recitersList" class="grid grid-cols-2 gap-4">
                </div>
            </div>
            <div id="detailsTab" class="tab-content">
            </div>
        </div>
    </div>
    <div class="audio-player" id="audioPlayer">
        <audio id="player" controls></audio>
        <button onclick="closePlayer()">ايقاف وحذف النافذة</button>
    </div>
    <script>
const reciters = <?php echo json_encode($reciters); ?>;
const recitersListDiv = document.getElementById('recitersList');
const searchContainer = document.getElementById('searchContainer');
const siteLogo = document.getElementById('siteLogo');
let currentReciterName = '';

function populateRecitersList(recitersToShow) {
    recitersListDiv.innerHTML = '';
    recitersToShow.forEach(reciter => {
        const reciterDiv = document.createElement('div');
        reciterDiv.className = 'border px-4 py-2';
        reciterDiv.innerHTML = `<a href="#" onclick="showDetails('${reciter.id}', '${reciter.name}')">${reciter.name}</a>`;
        recitersListDiv.appendChild(reciterDiv);
    });
}

function showDetails(id, reciterName) {
    const detailsTab = document.getElementById('detailsTab');
    const recitersTab = document.getElementById('recitersTab');
    searchContainer.style.display = 'none';
    siteLogo.style.display = 'none';
    currentReciterName = reciterName;
    fetch('reciter_details.php?id=' + id)
        .then(response => response.text())
        .then(data => {
            detailsTab.innerHTML = `<div class="navbar"><a href="#" onclick="backToList()" class="back-button"> 🔙</a></div>` + data;
            detailsTab.classList.add('active');
            recitersTab.classList.remove('active');
        });
}

function backToList() {
    const detailsTab = document.getElementById('detailsTab');
    const recitersTab = document.getElementById('recitersTab');
    searchContainer.style.display = 'block';
    siteLogo.style.display = 'block';
    detailsTab.classList.remove('active');
    recitersTab.classList.add('active');
}

function playSura(url) {
    const audio = document.getElementById('player');
    const audioPlayer = document.getElementById('audioPlayer');

    audio.src = url;
    audio.play();
    audioPlayer.style.display = 'block';

    if ('mediaSession' in navigator) {
        navigator.mediaSession.metadata = new MediaMetadata({
            title: "تلاوة قرآنية",
            artist: currentReciterName,
            album: "القرآن الكريم",
            artwork: [
                { src: "qu.png", sizes: "96x96", type: "image/png" },
                { src: "qu.png", sizes: "128x128", type: "image/png" },
                { src: "qu.png", sizes: "192x192", type: "image/png" },
                { src: "qu.png", sizes: "256x256", type: "image/png" },
                { src: "qu.png", sizes: "384x384", type: "image/png" },
                { src: "qu.png", sizes: "512x512", type: "image/png" }
            ]
        });
    }
}

function closePlayer() {
    const audioPlayer = document.getElementById('audioPlayer');
    const audio = document.getElementById('player');
    audio.pause();
    audioPlayer.style.display = 'none';
}

function searchReciters() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const filteredReciters = reciters.filter(reciter => reciter.name.toLowerCase().includes(searchTerm));
    populateRecitersList(filteredReciters);
}

populateRecitersList(reciters);
    </script>
</body>
</html>
