import 'package:prohandy_client/views/conversation_view/conversation_view.dart';
import 'package:prohandy_client/views/intro_view/intro_view.dart';
import 'package:prohandy_client/views/offer_list_view/offer_list_view.dart';
import 'package:prohandy_client/views/service_result_view/service_result_view.dart';
import 'package:prohandy_client/views/splash_view/splash_view.dart';

import '../views/job_details_view/job_details_view.dart';
import '../views/job_hire_summary_view/job_hire_summary_view.dart';

class Routes {
  static var routes = {
    IntroView.routeName: (_) => const SplashView(),
    ServiceResultView.routeName: (_) => const ServiceResultView(),
    JobDetailsView.routeName: (_) => const JobDetailsView(),
    ConversationView.routeName: (_) => const ConversationView(),
    OfferListView.routeName: (_) => const OfferListView(),
    JobHireSummaryView.routeName: (_) => const JobHireSummaryView(),
  };
}
