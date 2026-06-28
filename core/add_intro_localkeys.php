<?php
$file = "C:/xampp/htdocs/barachap/client/lib/helper/local_keys.g.dart";
$content = file_get_contents($file);

$newKeys = [
    "welcomeToProhandy" => "Welcome to Prohandy",
    "bookExpertHandymen" => "Book expert handymen for any task—repairs, shifting, plumbing, and laundry. Experience reliable service and peace of mind.",
    "bookFromServices" => "Book from Services",
    "bookYoursFromAWideRange" => "Book yours from a wide range of services listed by the professional handyman service providers & get your things done!",
    "postJobs" => "Post Jobs",
    "didntFindWhatYoureLookingFor" => "Didn’t find what you’re looking for? Have no worries! Post a job and hire best candidate from hundreds of handyman."
];

$consts = "";
foreach ($newKeys as $var => $val) {
    $consts .= "  static const String _$var = \"$val\";\n";
    $consts .= "  static String get $var => _$var.tr();\n";
}

$content = str_replace("class LocalKeys {\n", "class LocalKeys {\n" . $consts, $content);

$mapEntries = "";
foreach ($newKeys as $var => $val) {
    $mapEntries .= "    _$var: _$var,\n";
}
$content = str_replace("static final stringsMap = {\n", "static final stringsMap = {\n" . $mapEntries, $content);

file_put_contents($file, $content);
echo "Added intro keys to local_keys.g.dart";
?>
