<?php
$id = $_GET['id'] ?? 0;
$jsonData = file_get_contents('Quran.json');
$reciters = json_decode($jsonData, true)['reciters'];
$surasArabic = explode("\n", file_get_contents('suras.txt')); // ملف اسماء السور بالعربية
$surasEnglish = explode("\n", file_get_contents('suras_english.txt')); // ملف اسماء السور بالإنجليزية
$selectedReciter = null;

foreach ($reciters as $reciter) {
    if ($reciter['id'] == $id) {
        $selectedReciter = $reciter;
        break;
    }
}

if (!$selectedReciter) {
    echo "Reciter not found.";
    exit();
}

echo '<ul>';
foreach (explode(',', $selectedReciter['suras']) as $sura) {
    $suraNumber = str_pad($sura, 3, '0', STR_PAD_LEFT);
    echo '<li>';
    echo '<a href="#" onclick="playSura(\'' . $selectedReciter['Server'] . '/' . $suraNumber . '.mp3\')">';
    echo $surasEnglish[$sura - 1] . ' - ' . $surasArabic[$sura - 1];
    echo '</a>';
    echo '</li>';
}
echo '</ul>';
?>
