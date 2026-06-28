import 'package:flutter/material.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:prohand/services/auth_services/sign_out_service.dart';
import 'package:prohand/services/profile_services/profile_info_service.dart';
import 'package:prohand/services/theme_service.dart';
import 'package:prohand/utils/components/alerts.dart';
import 'package:prohand/view_models/change_language_view_model/change_language_view_model.dart';
import 'package:prohand/view_models/delete_account_view_model/delete_account_view_model.dart';
import 'package:prohand/views/date_schedule_view/date_schedule_view.dart';
import 'package:prohand/views/delete_account_view/delete_account_view.dart';
import 'package:prohand/views/menu_view/components/menu_tile.dart';
import 'package:prohand/views/menu_view/components/theme_setting_tile.dart';
import 'package:prohand/views/notification_list_view/notification_list_view.dart';
import 'package:prohand/views/ratings_and_review_view/ratings_and_review_view.dart';
import 'package:prohand/views/sign_in_view/sign_in_view.dart';
import 'package:prohand/views/support_ticket_view/support_ticket_view.dart';
import 'package:prohand/views/tac_pp_view/tac_pp_view.dart';
import 'package:provider/provider.dart';

import '../../../view_models/sign_in_view_model/sign_in_view_model.dart';
import '../../change_language_view/change_language_view.dart';
import '../../my_staffs_list_view/my_staffs_list_view.dart';
import '../../refund_list_view/refund_list_view.dart';
import '../../withdraw_view/withdraw_view.dart';

class MenuTiles extends StatelessWidget {
  final ThemeService ts;
  final bool logedIn;
  const MenuTiles({super.key, required this.ts, required this.logedIn});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          color: context.color.accentContrastColor,
          child: Column(
            children: [
              if (logedIn) ...[
                MenuTile(
                  title: LocalKeys.withdraw,
                  svg: SvgAssets.cash,
                  onPress: () {
                    context.toPage(const WithdrawView());
                  },
                  haveDivider: true,
                ),
                MenuTile(
                  title: LocalKeys.manageStaffs,
                  svg: SvgAssets.staffs,
                  onPress: () {
                    context.toPage(const MyStaffsListView());
                  },
                  haveDivider: true,
                ),
                MenuTile(
                  title: LocalKeys.manageSchedules,
                  svg: SvgAssets.calendar,
                  onPress: () {
                    context.toPage(const DateScheduleView());
                  },
                  haveDivider: logedIn,
                ),
              ],
              if (logedIn) ...[
                MenuTile(
                  title: LocalKeys.refunds,
                  svg: SvgAssets.refund,
                  onPress: () {
                    context.toPage(const RefundListView());
                  },
                  haveDivider: true,
                ),
                MenuTile(
                  title: LocalKeys.ratingAndReviews,
                  svg: SvgAssets.star,
                  onPress: () {
                    context.toPage(const RatingsAndReviewView());
                  },
                  haveDivider: true,
                ),
                MenuTile(
                  title: LocalKeys.supportTicket,
                  svg: SvgAssets.ticket,
                  onPress: () {
                    context.toPage(const SupportTicketView());
                  },
                  haveDivider: true,
                ),
                MenuTile(
                  title: LocalKeys.notifications,
                  svg: SvgAssets.notificationBell,
                  onPress: () {
                    context.toPage(const NotificationListView());
                  },
                ),
              ]
            ],
          ),
        ),
        8.toHeight,
        Container(
          color: context.color.accentContrastColor,
          child: Column(
            children: [
              MenuTile(
                title: LocalKeys.languages,
                svg: SvgAssets.languages,
                onPress: () {
                  ChangeLanguageViewModel.dispose;
                  context.toPage(const ChangeLanguageView());
                },
                haveDivider: true,
              ),
              const ThemeSettingTile(),
              MenuTile(
                title: LocalKeys.termsAndConditions,
                svg: SvgAssets.fileText,
                onPress: () {
                  context.toPage(TacPpView(
                      title: LocalKeys.termsAndConditions,
                      url: AppUrls.termsAndConditions));
                },
                haveDivider: true,
              ),
              MenuTile(
                title: LocalKeys.privacyPolicy,
                svg: SvgAssets.fileText,
                onPress: () {
                  context.toPage(TacPpView(
                      title: LocalKeys.privacyPolicy,
                      url: AppUrls.privacyPolicy));
                },
                haveDivider: true,
              ),
              MenuTile(
                title: LocalKeys.contact,
                svg: SvgAssets.contact,
                onPress: () {
                  context.toPage(TacPpView(
                    title: LocalKeys.contact,
                    url: AppUrls.contact,
                  ));
                },
                haveDivider: false,
              ),
            ],
          ),
        ),
        8.toHeight,
        Container(
          color: context.color.accentContrastColor,
          child: Column(
            children: [
              if (logedIn) ...[
                MenuTile(
                  title: LocalKeys.deleteAccount,
                  svg: SvgAssets.trash,
                  onPress: () {
                    DeleteAccountViewModel.dispose;
                    context.toPage(const DeleteAccountView());
                  },
                  haveDivider: true,
                ),
                MenuTile(
                  title: LocalKeys.signOut,
                  svg: SvgAssets.logout,
                  onPress: () {
                    Alerts().confirmationAlert(
                      context: context,
                      title: LocalKeys.areYouSure,
                      buttonText: LocalKeys.signOut,
                      onConfirm: () async {
                        await SignOutService().trySignOut().then((result) {
                          if (result == true) {
                            SignInViewModel.instance.li.value = false;
                            Provider.of<ProfileInfoService>(context,
                                    listen: false)
                                .reset();
                            SignInViewModel.dispose;
                            SignInViewModel.instance.initSavedInfo();
                            context.toUntilPage(const SignInView());
                          }
                        });
                      },
                    );
                  },
                  haveDivider: false,
                )
              ],
              if (!logedIn)
                MenuTile(
                  title: LocalKeys.signIn,
                  svg: SvgAssets.user,
                  onPress: () {
                    SignInViewModel.dispose;
                    SignInViewModel.instance.initSavedInfo();
                    context.toPage(const SignInView(), then: (value) {});
                  },
                ),
            ],
          ),
        ),
      ],
    );
  }
}
