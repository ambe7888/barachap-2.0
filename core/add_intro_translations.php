<?php
$file = "C:/xampp/htdocs/barachap/core/resources/lang/fr_FR.json";
$content = file_get_contents($file);
$json = json_decode($content, true);
$json["Welcome to Prohandy"] = "Bienvenue sur Prohandy";
$json["Book expert handymen for any task—repairs, shifting, plumbing, and laundry. Experience reliable service and peace of mind."] = "Réservez des bricoleurs experts pour toute tâche — réparations, déménagement, plomberie et blanchisserie. Profitez d'un service fiable et d'une tranquillité d'esprit.";
$json["Book from Services"] = "Réserver parmi les services";
$json["Book yours from a wide range of services listed by the professional handyman service providers & get your things done!"] = "Réservez le vôtre parmi une large gamme de services proposés par des prestataires professionnels et accomplissez vos tâches !";
$json["Post Jobs"] = "Publier des demandes";
$json["Didn’t find what you’re looking for? Have no worries! Post a job and hire best candidate from hundreds of handyman."] = "Vous n'avez pas trouvé ce que vous cherchez ? Ne vous inquiétez pas ! Publiez une demande et embauchez le meilleur candidat parmi des centaines de professionnels.";
file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "fr_FR.json updated with intro translations.";
?>
