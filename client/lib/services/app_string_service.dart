import 'dart:convert';
import 'dart:developer';

import 'package:flutter/material.dart';

import '../data/network/network_api_services.dart';
import '../helper/app_urls.dart';
import '../helper/constant_helper.dart';
import '../helper/local_keys.g.dart';

class AppStringService with ChangeNotifier {
  var translatedString = {};
  String getString(String s) {
    if (dProvider.languageSlug == 'fr' || dProvider.languageSlug == 'fr_FR' || sPref?.getString('lang_slug') == 'fr_FR') {
      if (s == LocalKeys.postAJob) return "Publier une demande";
      if (s == LocalKeys.jobs) return "Demandes";
      if (s == LocalKeys.job) return "Demande";
      if (s == LocalKeys.postJobs) return "Publier des demandes";
      if (s == LocalKeys.welcomeToProhandy) return "Bienvenue sur Prohandy";
      if (s == LocalKeys.bookExpertHandymen) return "Réservez des bricoleurs experts pour toute tâche — réparations, déménagement, plomberie et blanchisserie. Profitez d'un service fiable et d'une tranquillité d'esprit.";
      if (s == LocalKeys.bookFromServices) return "Réserver parmi les services";
      if (s == LocalKeys.bookYoursFromAWideRange) return "Réservez le vôtre parmi une large gamme de services proposés par des prestataires professionnels et accomplissez vos tâches !";
      if (s == LocalKeys.didntFindWhatYoureLookingFor) return "Vous n'avez pas trouvé ce que vous cherchez ? Ne vous inquiétez pas ! Publiez une demande et embauchez le meilleur candidat parmi des centaines de professionnels.";
      if (s == LocalKeys.jobBasicInfoSuggestion) return "Entrez un titre et choisissez la catégorie de demande dont vous avez besoin";
      if (s == LocalKeys.jobTitle) return "Titre de la demande";
      if (s == LocalKeys.enterJobTitle) return "Entrez le titre de la demande";
      if (s == LocalKeys.jobDescription) return "Description de la demande";
      if (s == LocalKeys.jobDescriptionSectionSuggestion) return "Écrivez sur votre demande";
    }

    if (translatedString[s] != null && translatedString[s].isNotEmpty) {
      return translatedString[s];
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
    log(jsonEncode(LocalKeys.stringsMap));

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
    debugPrint(
        (!sPref!.containsKey('langId') && (!sPref!.containsKey('lang_slug')))
            .toString());
    if (!sPref!.containsKey('langId') && (!sPref!.containsKey('lang_slug'))) {
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
