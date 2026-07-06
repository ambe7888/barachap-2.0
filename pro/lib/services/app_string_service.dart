import 'dart:convert';

import 'package:flutter/material.dart';

import '../data/network/network_api_services.dart';
import '../helper/app_urls.dart';
import '../helper/constant_helper.dart';
import '../helper/local_keys.g.dart';

class AppStringService with ChangeNotifier {
  var translatedString = {};
  static const Map<String, String> _frenchFallback = {
    "Enter a title & choose job category you need": "Saisir un titre et choisir la catégorie",
    "Enter job title": "Saisir le titre",
    "Enter Email": "Saisir l'adresse e-mail",
    "Enter phone": "Saisir le numéro de téléphone",
    "Enter the verification that was sent to the phone": "Saisir le code de vérification envoyé au téléphone",
    "Enter your current password": "Saisir votre mot de passe actuel",
    "Enter new password": "Saisir le nouveau mot de passe",
    "Re-enter new password": "Saisir à nouveau le mot de passe",
    "Enter title": "Saisir le titre",
    "Enter zip code": "Saisir le code postal",
    "Enter username": "Saisir le nom d'utilisateur",
    "Enter password": "Saisir le mot de passe",
    "Enter valid email address": "Saisir une adresse e-mail valide",
    "Enter the OTP": "Saisir le code OTP",
    "Enter the price of service you're offering": "Saisir le prix du service que vous proposez",
    "Enter basic price": "Saisir le prix de base",
    "Enter discounted price": "Saisir le prix remisé",
    "Enter addon title": "Saisir le titre du supplément",
    "Enter a question": "Saisir une question",
    "Enter answer": "Saisir la réponse",
    "Enter amount": "Saisir le montant",
    "Enter your email or username": "Saisir votre adresse e-mail ou nom d'utilisateur",
    "Enter a valid question": "Saisir une question valide",
    "Enter a valid answer": "Saisir une réponse valide",
    "Enter a valid name": "Saisir un nom valide",
    "Enter address": "Saisir l'adresse",
    "Enter country": "Saisir le pays",
    "Enter state": "Saisir la région",
    "Enter city": "Saisir la ville",
    "Enter id number": "Saisir le numéro d'identité",
    "Enter a comment about your work experience.": "Saisir un commentaire sur votre expérience de travail.",
    "Enter a valid phone number": "Saisir un numéro de téléphone valide",
    "Enter something about you or your company.": "Saisir quelques mots sur vous ou votre entreprise.",
    "Enter youtube video url.": "Saisir l'URL de la vidéo YouTube.",
    "Country": "Pays",
    "State": "Région",
    "City": "Ville",
    "Zip Code": "Code postal",
    "Id Number": "Numéro d'identité",
    "Address": "Adresse",
    "You'll Receive": "Vous recevrez",
    "Breakdown": "Détail",
    "Request history": "Historique des demandes",
    "Cash on delivery": "Payer à la fin du service",
    "cash on delivery": "Payer à la fin du service",
    "Verify Your Identity": "Vérifier votre identité",
    "In order to keep our platform safe for everyone you must verify your identity": "Afin de garder notre plateforme sécurisée pour tous, vous devez vérifier votre identité",
    "Verify Identity": "Vérifier l'identité",
    "Choose type of your national ID Card": "Choisissez le type de votre carte d'identité",
    "National ID": "Carte d'identité nationale",
    "Driving License": "Permis de conduire",
    "Passport": "Passeport",
    "Upload Front": "Télécharger le recto",
    "Upload ID back": "Télécharger le verso",
    "Submit ID": "Soumettre la pièce d'identité",
    "Your identity verify documents info must be similar your personal info.": "Vos documents de vérification d'identité doivent correspondre à vos informations personnelles.",
    "Withdraw": "Retrait",
    "Withdraw methods": "Méthodes de retrait",
    "Withdraw To": "Retirer vers",
    "Withdraw Method": "Méthode de retrait",
    "Select withdraw method": "Choisir la méthode de retrait",
    "Withdraw history": "Historique des retraits",
    "Refunds": "Remboursements",
    "Refund Request": "Demande de remboursement",
    "Refund Amount": "Montant du remboursement",
    "Refunded": "Remboursé",
    "No refunds found.": "Aucun remboursement trouvé.",
    "No refunds found": "Aucun remboursement trouvé",
    "Refund Details": "Détails du remboursement",
    "Refund details": "Détails du remboursement",
    "Request Withdraw": "Demander un retrait",
    "Create Service": "Créer un service",
    "Create service": "Créer un service",
    "create service": "Créer un service",
    "Jobs I Applied": "Missions postulées",
    "Jobs i applied": "Missions postulées",
    "Customer Rate": "Évaluation client",
    "Customer rate": "Évaluation client",
    "Staff": "Personnel",
    "Submit Completion": "Soumettre la fin",
    "Submit completion": "Soumettre la fin",
    "Submit Compeltion": "Soumettre la fin",
    "Submit compeltion": "Soumettre la fin",
    "something went wrong": "Une erreur est survenue",
    "Something went wrong": "Une erreur est survenue",
    "low": "Basse",
    "normal": "Normale",
    "high": "Haute",
    "urgent": "Urgente",
    "Bank Transfer": "Virement bancaire",
    "Paypal": "Paypal",
    "Stripe": "Stripe",
    "Account Number": "Numéro de compte",
    "Bank Name": "Nom de la banque",
    "Account Holder Name": "Nom du titulaire du compte",
    "Routing Number": "Numéro de routage",
    "IBAN": "IBAN",
    "BIC": "BIC",
    "Swift Code": "Code SWIFT",
    "Paypal Email": "E-mail Paypal",
    "Stripe Email": "E-mail Stripe",
    "Select Department": "Choisir le département",
    "Select Priority": "Choisir la priorité",
    "Select A Reason": "Choisir un motif",
    "Select Reason": "Choisir un motif",
    "Select state": "Choisir un état",
    "Select State": "Choisir un état",
    "Select city": "Choisir une ville",
    "Select City": "Choisir une ville",
    "Select category": "Choisir une catégorie",
    "Select Category": "Choisir une catégorie",
    "Select area": "Choisir une zone",
    "Select Area": "Choisir une zone",
    "Select subcategory": "Choisir la sous-catégorie",
    "Select Subcategory": "Choisir la sous-catégorie",
    "Edit Service": "Modifier le service",
    "Add Staffs": "Ajouter du personnel",
    "Manage Staffs": "Gérer le personnel",
    "Manage Schedules": "Gérer les horaires",
    "Rating & Reviews": "Évaluations et avis",
    "Contact": "Contact",
    "Terms and Conditions": "Conditions générales",
    "Privacy Policy": "Politique de confidentialité",
    "Rating & Review": "Évaluations et avis",
    "Rating and Reviews": "Évaluations et avis",
    "Rating and Review": "Évaluations et avis",
    "Home": "Accueil",
    "Orders": "Commandes",
    "Message": "Message",
    "Messages": "Messages",
    "Jobs": "Missions",
    "Profile": "Profil",
    "Category": "Catégorie",
    "Pending": "En attente",
    "accepted": "accepté",
    "Accepted": "Accepté",
    "In-Progress": "En cours",
    "Complete": "Terminé",
    "Canceled": "Annulé",
    "Area": "Zone",
    "No results found": "Aucun résultat trouvé",
    "Select File": "Choisir un fichier",
    "Cancel": "Annuler",
    "Okay!": "D'accord !",
    "Order Id": "ID de commande",
    "Skip": "Passer",
    "Continue": "Continuer",
    "Job Details": "Détails de la mission",
    "Phone": "Téléphone",
    "Total": "Total",
    "Sign Out": "Se déconnecter",
    "Delete Account": "Supprimer le compte",
    "Save Changes": "Enregistrer",
    "Email": "E-mail",
    "Password": "Mot de passe",
    "Search": "Rechercher",
    "Languages": "Langues",
    "Dark Mode": "Mode sombre",
    "Support Ticket": "Support technique",
    "Personal Information": "Informations personnelles",
    "Change Email": "Modifier l'e-mail",
    "Change Phone": "Modifier le téléphone",
    "Change Password": "Modifier le mot de passe",
    "All Jobs": "Toutes les missions",
    "My Services": "Mes services",
    "Add Staff": "Ajouter un employé",
    "Earnings": "Gains",
    "Pending orders": "Commandes en attente",
    "Completed orders": "Commandes terminées",
    "Total orders": "Total des commandes",
    "Orders Completed": "Commandes terminées",
    "Order's scheduled for Today": "Commandes prévues aujourd'hui",
    "new orders are waiting for you.": "nouvelles commandes vous attendent.",
    "Order Completion Rate": "Taux d'accomplissement",
    "Completion Rate": "Taux d'accomplissement",
    "Customer Satisfaction Rate": "Satisfaction client",
    "Monthly": "Mensuel",
    "Weekly": "Hebdomadaire",
    "Choose Location": "Choisir un emplacement",
    "Use This Location": "Utiliser cet emplacement",
    "Choose Payment Method": "Choisir le mode de paiement",
    "Apply Coupon": "Appliquer le coupon",
    "Enter code": "Entrer le code",
    "Pay & Confirm Order": "Payer et confirmer la commande",
    "Change Language": "Changer de langue",
    "Language": "Langue",
    "Select language": "Choisir la langue",
    "Language changed successfully.": "Langue modifiée avec succès.",
    "No orders found": "Aucune commande trouvée",
    "No notifications got yet": "Aucune notification reçue",
    "No staff found": "Aucun employé trouvé",
    "No schedule found": "Aucun planning trouvé",
    "No ratings found": "Aucun avis trouvé",
    "Search area": "Rechercher une zone",
    "Search state": "Rechercher un état",
    "Search city": "Rechercher une ville",
    "Search category": "Rechercher une catégorie",
    "By creating an account you agree to the": "En créant un compte, vous acceptez les",
    "and": "et",
    "Press again to exit.": "Appuyez à nouveau pour quitter.",
    "First Name": "Prénom",
    "Enter your first name": "Entrer votre prénom",
    "Last Name": "Nom",
    "Enter your last name": "Entrer votre nom",
    "A verification will be sent to your new email.": "Une vérification sera envoyée à votre nouvel e-mail.",
    "Enter new email": "Entrer le nouvel e-mail",
    "Send Verification Code": "Envoyer le code de vérification",
    "Sending OTP to email": "Envoi de l'OTP par e-mail",
    "Reset password": "Réinitialiser le mot de passe",
    "Password reset successfully": "Mot de passe réinitialisé avec succès",
    "Verification code": "Code de vérification",
    "Enter the verification that was sent to the email.": "Entrer le code de vérification envoyé par e-mail.",
    "Do not share your verification code with anyone.": "Ne partagez votre code de vérification avec personne.",
    "Didn't received any code?": "Vous n'avez reçu aucun code ?",
    "Resend again in": "Renvoyer dans",
    "Select Date": "Choisir la date",
    "Select Time": "Choisir l'heure",
    "Write about your job": "Décrivez votre mission",
    "Enter description": "Entrer la description",
    "Add Photo": "Ajouter une photo",
    "Upload Photos": "Télécharger des photos",
    "Upload relevant photos to help understand what you need": "Téléchargez des photos de référence pour faciliter la compréhension",
    "Are you sure?": "Êtes-vous sûr ?",
    "Congrats!": "Félicitations !",
    "Your job has been posted. You will get application's after it gets approved by an admin": "Votre mission a été publiée. Vous recevrez des candidatures après approbation par un administrateur",
    "Job Posted": "Mission publiée",
    "Last Seen": "Dernière connexion",
    "Report Job": "Signaler la mission",
    "Apply to Job": "Postuler à la mission",
    "Your Offer": "Votre offre",
    "Enter your offer amount": "Entrer le montant de votre offre",
    "Write about the service your offering": "Décrivez le service que vous proposez",
    "Send Offer": "Envoyer l'offre",
    "Send an Offer": "Faire une offre",
    "Offer Sent": "Offre envoyée",
    "Offer Sent Successfully": "Offre envoyée avec succès",
    "My Staffs": "Mon personnel",
    "Client Ratings": "Évaluations des clients",
    "Favorite Services": "Services favoris",
    "Overview": "Aperçu",
    "Service Unit": "Unité de service",
    "Video Url": "Lien vidéo",
    "Enter Title": "Entrer le titre",
    "Additional Information": "Informations complémentaires",
    "Submit Completion Request": "Envoyez une demande de fin",
    "Order Completion Request Sent Successfully": "Demande de fin de service envoyée avec succès",
    "Overall Rating For Customer": "Note globale pour le client",
    "Describe Your Experience": "Décrivez votre expérience",
    "Balance": "Solde",
    "Send Request": "Envoyer la demande",
    "Choose Your Service Area": "Choisir votre zone de service",
    "My Orders": "Mes commandes",
    "Job List": "Liste des missions",
    "Categories": "Catégories",
    "Saved Jobs": "Missions enregistrées",
    "Edit Information": "Modifier les informations",
    "Service Area": "Zone de service",
    "Service Type": "Type de service",
    "Cart": "Panier",
    "Payment": "Paiement",
    "Sign In": "Se connecter",
    "Sign Up": "S'inscrire",
    "Pending Orders": "Commandes en attente",
    "Order in Progress": "Commande en cours",
    "Total Orders": "Total des commandes",
    "Staffs": "Personnel",
    "Total Staff": "Total personnel",
    "Service": "Service",
    "Results": "Résultats",
    "All": "Tout",
    "Booking": "Réservation",
    "Applied": "Postulé",
    "Back": "Retour",
    "Create": "Créer",
    "Create your service today and start earning! Showcase your skills, reach new clients, and grow your business effortlessly.": "Créez votre service aujourd'hui et commencez à gagner ! Présentez vos compétences, touchez de nouveaux clients et développez votre activité sans effort.",
    "Overall completion rate from your orders. If you have completed 70 of 100 orders you received then your completion rate will be 70%.": "Taux d'accomplissement global de vos commandes. Si vous avez terminé 70 des 100 commandes reçues, votre taux d'accomplissement sera de 70%.",
    "Overall 5 star rating rate from your ratings. If you have got 5 star rating on 70 and 3 star rating on 30 of 100 ratings you received then your completion rate will be 88%.": "Taux de satisfaction globale calculé à partir de vos évaluations. Si vous obtenez une note de 5 étoiles sur 70 évaluations et 3 étoiles sur 30 des 100 évaluations reçues, votre taux de satisfaction sera de 88%.",
    "Identity Verification": "Vérification d'identité",
    "Schedule availability": "Disponibilité des horaires",
    "Select the days and times when you are available to provide your services": "Sélectionnez les jours et heures où vous êtes disponible",
    "New Staff": "Nouveau personnel",
  };

  String getString(String s) {
    final slug = sPref?.getString("lang_slug") ?? "fr";
    final isFrench = slug.startsWith("fr");

    if (translatedString[s] != null && translatedString[s].isNotEmpty) {
      final translation = translatedString[s];
      if (isFrench && (translation == s || _frenchFallback[s] != null)) {
        return _frenchFallback[s] ?? translation;
      }
      return translation;
    }

    if (isFrench) {
      if (_frenchFallback[s] != null) {
        return _frenchFallback[s]!;
      }
    }
    return s;
  }

  translateStrings(BuildContext context, {bool forceChange = false}) async {
    bool shouldLoad = true;
    if (!sPref!.containsKey('lang_slug')) {
      sPref!.setString('langId', dProvider.languageSlug);
    } else if (sPref!.getString('langId') != sPref?.getString("lang_slug")) {
      sPref!.setString(
          'langId', sPref?.getString("lang_slug") ?? dProvider.languageSlug);
    } else if (sPref!.getString('langId') != dProvider.languageSlug) {
      sPref!.setString('langId', dProvider.languageSlug);
    } else {
      shouldLoad = false;
    }

    if (!shouldLoad && !forceChange) {
      final strings = sPref!.getString('translated_string');
      debugPrint("skipping translation".toString());
      translatedString = jsonDecode(strings ?? '{}');
      coreInit(context);
      return;
    }
    final data = {
      "slug": sPref?.getString("lang_slug"),
      "strings": jsonEncode(LocalKeys.stringsMap),
    };
    final responseData = await NetworkApiServices()
        .postApi(data, AppUrls.translationUrl, LocalKeys.translatingText);

    if (responseData != null) {
      debugPrint((responseData["strings"] is Map).toString());
      translatedString =
          responseData["strings"] is! Map ? {} : responseData["strings"];
      sPref?.setString("translated_string", jsonEncode(translatedString));
      coreInit(context);
      return true;
    } else {}
  }

  defaultTranslate(BuildContext context, {bool forceChange = false}) async {
    bool shouldLoad = true;
    if (!sPref!.containsKey('langId') && !sPref!.containsKey('lang_slug')) {
      sPref!.setString('langId', dProvider.languageSlug);
    } else {
      shouldLoad = false;
    }

    if (!shouldLoad && !forceChange) {
      final strings = sPref!.getString('translated_string');
      debugPrint("skipping translation".toString());
      translatedString = jsonDecode(strings ?? '{}');
      coreInit(context);
      return;
    }
    final data = {
      "strings": jsonEncode(LocalKeys.stringsMap),
    };
    final responseData = await NetworkApiServices().postApi(
        data, AppUrls.defaultTranslationUrl, LocalKeys.translatingText);

    if (responseData != null) {
      debugPrint((responseData["strings"] is Map).toString());
      translatedString =
          responseData["strings"] is! Map ? {} : responseData["strings"];
      sPref?.setString("translated_string", jsonEncode(translatedString));
      coreInit(context);
      return true;
    } else {}
  }
}
