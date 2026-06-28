<?php
$file = "C:/xampp/htdocs/barachap/core/resources/lang/fr_FR.json";
$content = file_get_contents($file);
$json = json_decode($content, true);

// Remove the old key with em-dash
$oldKey = "Book expert handymen for any task—repairs, shifting, plumbing, and laundry. Experience reliable service and peace of mind.";
unset($json[$oldKey]);

// Add the new key with regular hyphen
$newKey = "Book expert handymen for any task - repairs, shifting, plumbing, and laundry. Experience reliable service and peace of mind.";
$json[$newKey] = "Réservez des bricoleurs experts pour toute tâche - réparations, déménagement, plomberie et blanchisserie. Profitez d'un service fiable et d'une tranquillité d'esprit.";

file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Successfully replaced em-dash in fr_FR.json!\n";
?>
