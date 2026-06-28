<?php
$file = "C:/xampp/htdocs/barachap/core/resources/lang/fr_FR.json";
$content = file_get_contents($file);
$json = json_decode($content, true);

$replacements = [
    "Job" => "Demande",
    "Jobs" => "Demandes",
    "Post A Job" => "Publier une demande",
    "Post Job" => "Publier une demande",
    "Job Details" => "Détails de la demande",
    "Job Title" => "Titre de la demande",
    "Enter job title" => "Entrez le titre de la demande",
    "Job Description" => "Description de la demande",
    "Write about your job" => "Écrivez sur votre demande",
    "Job List" => "Liste des demandes",
    "No jobs found" => "Aucune demande trouvée",
    "Job Published" => "Demande publiée",
    "Job edited successfully" => "Demande modifiée avec succès",
    "Confirm Job Deletion?" => "Confirmer la suppression de la demande ?",
    "Job deleted successfully" => "Demande supprimée avec succès",
    "Are you sure you want to delete this job? This action cannot be undone, and all associated data will be permanently removed." => "Êtes-vous sûr de vouloir supprimer cette demande ? Cette action est irréversible et toutes les données associées seront définitivement supprimées.",
    "Your job has been posted. You will get application's after it gets approved by an admin" => "Votre demande a été publiée. Vous recevrez des candidatures après son approbation par un administrateur.",
    "Job publish status changed successfully" => "Le statut de publication de la demande a été modifié avec succès",
    "All Jobs" => "Toutes les demandes",
    "Search job" => "Rechercher une demande",
    "Total Jobs" => "Demandes totales",
    "Job Id Not Found" => "Identifiant de la demande introuvable",
    "Job Successfully Deleted Permanently." => "Demande supprimée définitivement avec succès.",
    "Saved Jobs" => "Demandes enregistrées",
    "Applied Jobs" => "Demandes appliquées",
    "Add New Job" => "Ajouter une nouvelle demande",
    "Job Title:" => "Titre de la demande :",
    "Job offer not found" => "Offre de demande introuvable",
    "Job Completed" => "Demande terminée",
    "Enter a title & choose job category you need" => "Entrez un titre et choisissez la catégorie de demande dont vous avez besoin"
];

foreach ($replacements as $key => $value) {
    if (isset($json[$key])) {
        $json[$key] = $value;
    }
}

file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "fr_FR.json updated with correct Demande terminology.";
?>
