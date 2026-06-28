<?php
$file = "C:/xampp/htdocs/barachap/client/lib/helper/local_keys.g.dart";
$content = file_get_contents($file);

// Find all keys like static const String _keyName = "...";
preg_match_all("/static const String _([a-zA-Z0-9_]+)\s*=\s*/", $content, $matches);

$keys = array_unique($matches[1]);
$mapLines = [];
foreach ($keys as $key) {
    $mapLines[] = "    _$key: _$key,";
}

$newMap = "  static final stringsMap = {\n" . implode("\n", $mapLines) . "\n  };";

// Replace the existing stringsMap
$content = preg_replace("/static final stringsMap = \{.*?\};/s", $newMap, $content);

file_put_contents($file, $content);
echo "Fixed local_keys.g.dart with " . count($keys) . " keys.";
?>
