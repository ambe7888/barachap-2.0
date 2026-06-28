// ignore_for_file: use_build_context_synchronously

import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:prohandy_client/services/dynamics/cancellation_policy_service.dart';
import 'package:prohandy_client/services/dynamics/dynamics_service.dart';
import 'package:prohandy_client/services/home_services/home_category_service.dart';
import 'package:prohandy_client/services/home_services/home_featured_services_service.dart';
import 'package:prohandy_client/services/home_services/home_popular_services_service.dart';
import 'package:prohandy_client/services/home_services/home_providers_service.dart';
import 'package:prohandy_client/services/home_services/home_slider_service.dart';
import 'package:prohandy_client/services/service/cart_service.dart';
import 'package:prohandy_client/services/service/favorite_services_service.dart';
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
import '../landding_view_model/landding_view_model.dart';

class SplashViewModel {
  dbInit(BuildContext context) {
    List databases = ['favorite', "cart"];
    databases.map((e) => DbHelper.database(e));
    Provider.of<FavoriteServicesService>(
      context,
      listen: false,
    ).fetchFavorites();
    Provider.of<CartService>(context, listen: false).fetchCarts();
  }

  initiateStartingSequence(BuildContext context) async {
    await Provider.of<DynamicsService>(context, listen: false).getColors();
    await coreInit(context);
    dbInit(context);
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
        statusBarIconBrightness: Brightness.dark,
      ),
    );
    await Provider.of<AppStringService>(
      context,
      listen: false,
    ).defaultTranslate(context);
    final gotoIntro =
        await Provider.of<IntroService>(context, listen: false).checkIntro();
    debugPrint("intro is $gotoIntro".toString());
    CancellationPolicyService.instance.fetchCancellationPolicy();
    if (gotoIntro) {
      context.toUntilPage(const IntroView());
    } else {
      initLocalData(context);
      Provider.of<ProfileInfoService>(
        context,
        listen: false,
      ).fetchProfileInfo();
      debugPrint(getToken.toString());
      LandingViewModel.instance.navigateToLanding(context);
    }
    try {
      await NotificationHelper().notificationsSetup();
      await NotificationHelper().initiateNotification(context);
      NotificationHelper().streamListener(context);
      NotificationHelper().notificationAppLaunchChecker(context);
    } catch (e) {
      debugPrint(e.toString());
      log(e.toString());
    }
  }

  initLocalData(BuildContext context) {
    PushNotificationService().updateDeviceToken();
    CancellationPolicyService.instance.fetchCancellationPolicy();
    Provider.of<HomeProvidersService>(context, listen: false).initLocal();
    Provider.of<HomeFeaturedServicesService>(
      context,
      listen: false,
    ).initLocal();
    Provider.of<HomePopularServicesService>(context, listen: false).initLocal();
    Provider.of<HomeSliderService>(context, listen: false).initLocal();
    Provider.of<HomeCategoryService>(context, listen: false).initLocal();
    Provider.of<ChatCredentialService>(context, listen: false).initLocal();
  }
}
