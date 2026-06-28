import 'package:prohand/views/conversation_view/conversation_view.dart';
import 'package:prohand/views/intro_view/intro_view.dart';
import 'package:prohand/views/service_result_view/service_result_view.dart';
import 'package:prohand/views/splash_view/splash_view.dart';

import '../views/job_details_view/job_details_view.dart';
import '../views/order_details_view/order_details_view.dart';

class Routes {
  static var routes = {
    IntroView.routeName: (_) => const SplashView(),
    ServiceResultView.routeName: (_) => const ServiceResultView(),
    JobDetailsView.routeName: (_) => const JobDetailsView(),
    ConversationView.routeName: (_) => const ConversationView(),
    OrderDetailsView.routeName: (_) => const OrderDetailsView(),
  };
}
