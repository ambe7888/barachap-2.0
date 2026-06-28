import '/customization.dart';

class AppUrls {
  static String get statesUrl => '$baseEndPoint/states';
  static String get areaUrl => '$baseEndPoint/areas';
  static String get translationUrl => '$baseEndPoint/translate-string-slug';
  static String get cityUrl => '$baseEndPoint/cities';
  static String get fcmTokenUrl => '$baseEndPoint/user/profile/firebase-token';
  static String get currencyLanguageUrl => '$baseEndPoint/currency';
  static String get deleteServiceUrl =>
      '$baseEndPoint/provider/service/delete-service';
  static String get languageListUrl => '$baseEndPoint/language';
  static String get sendOtpUrl => '$baseEndPoint/country/all';
  static String get resetPasswordUrl => '$baseEndPoint/reset-password';
  static String get categoryListUrl => '$baseEndPoint/categories';
  static String get termsAndConditions => '$baseEndPoint/terms-and-conditions';
  static String get defaultTranslationUrl => '$baseEndPoint/translate-string';
  static String get privacyPolicy => '$baseEndPoint/privacy-policy';
  static String get profileInfoUrl => '$baseEndPoint/user/profile';
  static String get paymentGatewayUrl => '$baseEndPoint/gateway/list';
  static String get conversationUrl =>
      '$baseEndPoint/provider/chat/fetch-record';
  static String get myRefundListUrl =>
      '$baseEndPoint/provider/refund/refund-list';
  static String get sendMessage => '$baseEndPoint/privacy-policy';
  static String get submitReviewsUrl => '$baseEndPoint/user/review-add';
  static String get myRefundDetailsUrl =>
      '$baseEndPoint/provider/refund/refund-details';
  static String get messageSendUrl =>
      '$baseEndPoint/provider/chat/message-send';
  static String get withdrawSettingsUrl =>
      '$baseEndPoint/provider/withdraw/settings';
  static String get withdrawRequestUrl =>
      '$baseEndPoint/provider/withdraw/request';
  static String get signInUrl => '$baseEndPoint/login';
  static String get contact => '$baseEndPoint/contact';
  static String get emailSignUpUrl => '$baseEndPoint/register';
  static String get socialSignInUrl => '$baseEndPoint/social/login';
  static String get changePasswordUrl => '$baseEndPoint/user/change-password';
  static String get signOutUrl => '$baseEndPoint/user/logout';
  static String get ratingAndReviewsUrl => '$baseEndPoint/user/asdf';
  static String get serviceDetailsUrl =>
      '$baseEndPoint/provider/service/details';
  static String get serviceListUrl =>
      '$baseEndPoint/provider/service/all-services';

  static String get createServiceUrl =>
      '$baseEndPoint/provider/service/add-service';
  static String get editServiceUrl =>
      '$baseEndPoint/provider/service/edit-service';
  static String get jobListUrl => '$baseEndPoint/job/lists';
  static String get sendOfferUrl => '$baseEndPoint/job/offer-create';
  static String get jobDetailsUrl => '$baseEndPoint/job/details';
  static String get staffListUrl => '$baseEndPoint/provider/staff/all-list';
  static String get addStaffUrl => '$baseEndPoint/provider/staff/create';
  static String get editStaffUrl => '$baseEndPoint/provider/staff/edit';
  static String get removeStaffUrl =>
      '$baseEndPoint/provider/staff/delete/?id=';
  static String get scheduleListUrl =>
      '$baseEndPoint/provider/schedule/list?day=';
  static String get addScheduleUrl => '$baseEndPoint/provider/schedule/create';
  static String get editScheduleUrl => '$baseEndPoint/provider/schedule/edit';
  static String get profileInfoUpdateUrl => '$baseEndPoint/user/profile/update';
  static String deleteScheduleUrl =
      '$baseEndPoint/provider/schedule/delete?id=';
  static String get notificationsListUrl =>
      '$baseEndPoint/user/notifications/all';
  static String get notificationReadUrl =>
      '$baseEndPoint/user/notifications/clear';
  static String get ticketListListUrl =>
      '$baseEndPoint/user/support-ticket/all';
  static String get stDepartmentsUrl => '$baseEndPoint/user/all-departments';
  static String get createTicketUrl =>
      '$baseEndPoint/user/support-ticket/create';
  static String get fetchTicketConversationUrl =>
      '$baseEndPoint/user/support-ticket/view-ticket';
  static String get sendTicketMessageUrl =>
      '$baseEndPoint/user/support-ticket/message-send';
  static String get ordersListUrl => '$baseEndPoint/provider/orders/all';
  static String get orderDetailsUrl => '$baseEndPoint/provider/orders/details';
  static String get orderAcceptUrl =>
      '$baseEndPoint/provider/orders/order-accept-decline';
  static String get orderCancelUrl =>
      '$baseEndPoint/provider/orders/change-status-cancel';
  static String get orderDeclineUrl =>
      '$baseEndPoint/provider/orders/order-accept-decline';
  static String get refundDeclineUrl =>
      '$baseEndPoint/provider/refund/refund-request-decline';
  static String get refundAcceptUrl =>
      '$baseEndPoint/provider/refund/refund-request-accept';
  static String get completeRequestUrl =>
      '$baseEndPoint/provider/orders/complete/request';
  static String get sentOtpToMailUrl => '$baseEndPoint/send-otp-in-mail';
  static String get otpSignInUrl => '$baseEndPoint/login-otp/verification';
  static String get verifyEmailUrl =>
      '$baseEndPoint/user/send-otp-in-mail/success';
  static String get dashboardInfoUrl => '$baseEndPoint/provider/dashboard/info';
  static String get serviceAreaUpdateUrl =>
      '$baseEndPoint/provider/service/location/update';
  static String get ivInfoUrl =>
      '$baseEndPoint/provider/settings/identity-verification/info';
  static String get idVerifySubmitUrl =>
      '$baseEndPoint/provider/settings/identity-verification';
  static String get reviewListUrl => '$baseEndPoint/provider/reviews/all';
  static String get reasonListUrl => '$baseEndPoint/reasons';
  static String get deleteAccountUrl =>
      '$baseEndPoint/user/settings/account-delete';
  static String get changeEmailUrl => '$baseEndPoint/user/change-email';
  static String get sentOtpToPhoneUrl => '$baseEndPoint/login-otp/send';
  static String get verifyPhoneUrl => '$baseEndPoint/login-otp/verification';
  static String get changePhoneUrl => '$baseEndPoint/user/change-phone-number';
  static String get completeRequestHistoryUrl =>
      '$baseEndPoint/provider/orders/complete-request/history?';
  static String get todaysOrdersUrl =>
      '$baseEndPoint/provider/orders/today-lists';
  static String get chatCredentialUrl => '$baseEndPoint/live-chat/credentials';
  static String get chatListUrl => '$baseEndPoint/provider/chat/client-list';
  static String get unreadCountUrl =>
      '$baseEndPoint/provider/chat/unseen-message/count';
  static String get withdrawHistoryUrl =>
      '$baseEndPoint/provider/withdraw/history';
  static String get changeServicePublishStatusUrl =>
      '$baseEndPoint/provider/service/published-status';
  static String get revenueInfoUrl =>
      '$baseEndPoint/provider/dashboard/get-total-income-data';
}
