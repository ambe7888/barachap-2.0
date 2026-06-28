// ignore_for_file: use_build_context_synchronously

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:prohand/models/job_models/favorite_jobs_service.dart';
import 'package:prohand/services/dynamics_services/dynamics_service.dart';
import 'package:prohand/services/profile_services/dashboard_info_service.dart';
import 'package:prohand/view_models/sign_in_view_model/sign_in_view_model.dart';
import 'package:prohand/views/landing_view/landing_view.dart';
import 'package:prohand/views/sign_in_view/sign_in_view.dart';
import 'package:provider/provider.dart';

import '/helper/constant_helper.dart';
import '/helper/extension/context_extension.dart';
import '/helper/extension/string_extension.dart';
import '/helper/local_keys.g.dart';
import '/services/intro_service.dart';
import '../../helper/db_helper.dart';
import '../../helper/network_connectivity.dart';
import '../../helper/notification_helper.dart';
import '../../services/app_string_service.dart';
import '../../services/chat_services/chat_credential_service.dart';
import '../../services/profile_services/profile_info_service.dart';
import '../../services/push_notification_service.dart';
import '../../views/intro_view/intro_view.dart';

class SplashViewModel {
  dbInit(BuildContext context) {
    List databases = ['favorite'];
    databases.map((e) => DbHelper.database(e));
    Provider.of<FavoriteJobsService>(context, listen: false).fetchFavorites();
  }

  initiateStartingSequence(BuildContext context) async {
    await Provider.of<DynamicsService>(context, listen: false).getColors();
    await coreInit(context);
    dbInit(context);
    await NotificationHelper().notificationsSetup();
    final NetworkConnectivity networkConnectivity =
        NetworkConnectivity.instance;
    final hasConnection = await networkConnectivity.currentStatus();
    if (!hasConnection) {
      debugPrint("connection state is $hasConnection".toString());
      dProvider.setNoConnection(true);
      LocalKeys.noConnectionFound.showToast();
      return;
    }

    SystemChrome.setSystemUIOverlayStyle(
      const SystemUiOverlayStyle(
          statusBarColor: Colors.transparent,
          statusBarIconBrightness: Brightness.dark),
    );
    await Provider.of<AppStringService>(context, listen: false)
        .defaultTranslate(context);
    await NotificationHelper().initiateNotification(context);
    NotificationHelper().streamListener(context);
    final gotoIntro =
        await Provider.of<IntroService>(context, listen: false).checkIntro();
    if (gotoIntro) {
      await Provider.of<IntroService>(context, listen: false)
          .fetchIntro(context);
      context.toUntilPage(const IntroView());
      return;
    } else {
      Provider.of<ProfileInfoService>(context, listen: false)
          .fetchProfileInfo(trySkip: true)
          .then((value) async {
        if (value != true) {
          SignInViewModel.instance.initSavedInfo();
          context.toUntilPage(const SignInView());
          return;
        }
        Provider.of<DashboardInfoService>(context, listen: false)
            .fetchDashboardInfo(trySkip: true)
            .then((v) async {
          if (v == true) {
            Provider.of<ChatCredentialService>(context, listen: false)
                .initLocal();
            await PushNotificationService().updateDeviceToken();
            context.toUntilPage(const LandingView());
            NotificationHelper().notificationAppLaunchChecker(context);
          }
        });
      });
    }
  }
}
