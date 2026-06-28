<?php
$file = "C:/xampp/htdocs/barachap/core/resources/lang/fr_FR.json";
$content = file_get_contents($file);
$json = json_decode($content, true);

$appKeys = [
    // Intro Page
    "Welcome to Prohandy" => "Bienvenue sur Prohandy",
    "Book expert handymen for any task—repairs, shifting, plumbing, and laundry. Experience reliable service and peace of mind." => "Réservez des bricoleurs experts pour toute tâche — réparations, déménagement, plomberie et blanchisserie. Profitez d'un service fiable et d'une tranquillité d'esprit.",
    "Book from Services" => "Réservez à partir des services",
    "Book yours from a wide range of services listed by the professional handyman service providers & get your things done!" => "Réservez le vôtre parmi une large gamme de services proposés par des prestataires professionnels et accomplissez vos tâches !",
    "Post Jobs" => "Publier des demandes",
    "Didn’t find what you’re looking for? Have no worries! Post a job and hire best candidate from hundreds of handyman." => "Vous n'avez pas trouvé ce que vous cherchez ? Ne vous inquiétez pas ! Publiez une demande et embauchez le meilleur candidat parmi des centaines de professionnels.",

    // Job -> Demande updates
    "Post A Job" => "Publier une demande",
    "Post Job" => "Publier une demande",
    "Jobs" => "Demandes",
    "Job" => "Demande",
    "Job Details" => "Détails de la demande",
    "Job Completed" => "Demande terminée",
    "Enter a title & choose job category you need" => "Entrez un titre et choisissez la catégorie de demande dont vous avez besoin",
    "Job Title" => "Titre de la demande",
    "Enter job title" => "Entrez le titre de la demande",
    "Job Description" => "Description de la demande",
    "Write about your job" => "Écrivez sur votre demande",
    "Your job has been posted. You will get application's after it gets approved by an admin" => "Votre demande a été publiée. Vous recevrez des candidatures après son approbation par un administrateur.",
    "Job List" => "Liste des demandes",
    "No jobs found" => "Aucune demande trouvée",
    "Job publish status changed successfully" => "Le statut de publication de la demande a été modifié avec succès",
    "Job Published" => "Demande publiée",
    "Edit Job" => "Modifier la demande",
    "Job edited successfully" => "Demande modifiée avec succès",
    "Confirm Job Deletion?" => "Confirmer la suppression de la demande ?",
    "Are you sure you want to delete this job? This action cannot be undone, and all associated data will be permanently removed." => "Êtes-vous sûr de vouloir supprimer cette demande ? Cette action est irréversible et toutes les données associées seront définitivement supprimées.",
    "Job deleted successfully" => "Demande supprimée avec succès",
];

foreach ($appKeys as $key => $value) {
    $json[$key] = $value;
}

file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Successfully added missing App keys to fr_FR.json!\n";
?>
