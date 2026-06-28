import 'package:prohand/services/address_services/area_service.dart';
import 'package:prohand/services/address_services/city_service.dart';
import 'package:prohand/services/address_services/states_service.dart';
import 'package:prohand/services/theme_service.dart';
import 'package:provider/provider.dart';
import 'package:provider/single_child_widget.dart';

import '../models/job_models/favorite_jobs_service.dart';
import '../services/app_string_service.dart';
import '../services/auth_services/email_otp_service.dart';
import '../services/auth_services/phone_manage_service.dart';
import '../services/auth_services/sign_in_service.dart';
import '../services/auth_services/sign_up_service.dart';
import '../services/category_service.dart';
import '../services/chat_services/chat_credential_service.dart';
import '../services/chat_services/chat_list_service.dart';
import '../services/conversation_service.dart';
import '../services/dynamics_services/dynamics_service.dart';
import '../services/google_location_search_service.dart';
import '../services/home_services/home_category_service.dart';
import '../services/internet_checker_service.dart';
import '../services/intro_service.dart';
import '../services/job_services/job_details_service.dart';
import '../services/job_services/job_list_service.dart';
import '../services/notification_services/notification_list_service.dart';
import '../services/order_services/order_complete_request_history_service.dart';
import '../services/order_services/order_details_service.dart';
import '../services/order_services/order_list_service.dart';
import '../services/order_services/refund_list_service.dart';
import '../services/order_services/refund_manage_service.dart';
import '../services/order_services/todays_order_service.dart';
import '../services/profile_services/dashboard_info_service.dart';
import '../services/profile_services/delete_account_service.dart';
import '../services/profile_services/iv_manage_service.dart';
import '../services/profile_services/profile_info_service.dart';
import '../services/profile_services/revenue_info_service.dart';
import '../services/profile_services/review_list_service.dart';
import '../services/rating_and_reviews_service.dart';
import '../services/reset_password_service.dart';
import '../services/schedule_services/schedule_list_service.dart';
import '../services/service_search_service.dart';
import '../services/service_services/add_edit_service_service.dart';
import '../services/service_services/service_details_service.dart';
import '../services/service_services/service_list_service.dart';
import '../services/staff_services/staff_list_service.dart';
import '../services/support_services/ticket_conversation_service.dart';
import '../services/support_services/ticket_list_service.dart';
import '../services/withdraw_services/withdraw_history_service.dart';
import '../services/withdraw_services/withdraw_info_service.dart';

class Providers {
  static List<SingleChildWidget> providers = [
    ChangeNotifierProvider(create: (context) => DynamicsService()),
    ChangeNotifierProvider(create: (context) => AppStringService()),
    ChangeNotifierProvider(create: (context) => ThemeService()),
    ChangeNotifierProvider(create: (context) => IntroService()),
    ChangeNotifierProvider(create: (context) => StatesService()),
    ChangeNotifierProvider(create: (context) => CityService()),
    ChangeNotifierProvider(create: (context) => AreaService()),
    ChangeNotifierProvider(create: (context) => ServiceSearchService()),
    ChangeNotifierProvider(create: (context) => ConversationService()),
    ChangeNotifierProvider(create: (context) => SignInService()),
    ChangeNotifierProvider(create: (context) => SignUpService()),
    ChangeNotifierProvider(create: (context) => RatingAndReviewsService()),
    ChangeNotifierProvider(create: (context) => AddEditServiceService()),
    ChangeNotifierProvider(create: (context) => ServiceDetailsService()),
    ChangeNotifierProvider(create: (context) => ServiceListService()),
    ChangeNotifierProvider(create: (context) => JobDetailsService()),
    ChangeNotifierProvider(create: (context) => JobListService()),
    ChangeNotifierProvider(create: (context) => StaffListService()),
    ChangeNotifierProvider(create: (context) => ScheduleListService()),
    ChangeNotifierProvider(create: (context) => FavoriteJobsService()),
    ChangeNotifierProvider(create: (context) => ProfileInfoService()),
    ChangeNotifierProvider(create: (context) => NotificationListService()),
    ChangeNotifierProvider(create: (context) => TicketListService()),
    ChangeNotifierProvider(create: (context) => TicketConversationService()),
    ChangeNotifierProvider(create: (context) => OrderListService()),
    ChangeNotifierProvider(create: (context) => OrderDetailsService()),
    ChangeNotifierProvider(create: (context) => EmailManageService()),
    ChangeNotifierProvider(create: (context) => HomeCategoryService()),
    ChangeNotifierProvider(create: (context) => DashboardInfoService()),
    ChangeNotifierProvider(create: (context) => IvManageService()),
    ChangeNotifierProvider(create: (context) => ReviewListService()),
    ChangeNotifierProvider(create: (context) => WithdrawInfoService()),
    ChangeNotifierProvider(create: (context) => CategoryService()),
    ChangeNotifierProvider(create: (context) => DeleteAccountService()),
    ChangeNotifierProvider(create: (context) => PhoneManageService()),
    ChangeNotifierProvider(create: (context) => ResetPasswordService()),
    ChangeNotifierProvider(
        create: (context) => OrderCompleteRequestHistoryService()),
    ChangeNotifierProvider(create: (context) => TodaysOrdersService()),
    ChangeNotifierProvider(create: (context) => ChatCredentialService()),
    ChangeNotifierProvider(create: (context) => ChatListService()),
    ChangeNotifierProvider(create: (context) => WithdrawHistoryService()),
    ChangeNotifierProvider(create: (context) => InternetCheckerService()),
    ChangeNotifierProvider(create: (context) => GoogleLocationSearch()),
    ChangeNotifierProvider(create: (context) => RefundManageService()),
    ChangeNotifierProvider(create: (context) => RefundListService()),
    ChangeNotifierProvider(create: (context) => RevenueInfoService()),
  ];
}
