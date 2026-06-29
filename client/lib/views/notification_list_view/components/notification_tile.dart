import 'package:badges/badges.dart' as badges;
import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/helper/svg_assets.dart';
import 'package:prohandy_client/models/notification_models/notification_list_model.dart';
import 'package:prohandy_client/views/service_by_offer_view/service_by_offer_view.dart';
import 'package:provider/provider.dart';
import 'package:timeago/timeago.dart' as timeago;

import '../../../services/support_services/ticket_conversation_service.dart';
import '../../job_details_view/job_details_view.dart';
import '../../order_details_view/order_details_view.dart';
import '../../ticket_conversation_view/ticket_conversation_view.dart';

class NotificationTile extends StatelessWidget {
  final NotificationModel notification;
  const NotificationTile({super.key, required this.notification});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        debugPrint(notification.type.toString());
        switch (notification.type) {
          case "job":
            context.toNamed(
              JobDetailsView.routeName,
              arguments: notification.identity,
            );
            break;
          case "order":
            context.toPage(OrderDetailsView(orderId: notification.identity));
            break;
          case "offer":
            context.toPage(ServiceByOfferView(offerId: notification.identity));
            break;
          case "ticket":
            Provider.of<TicketConversationService>(
              context,
              listen: false,
            ).fetchSingleTickets(context, notification.identity);
            context.toPage(
              TicketConversationView(id: notification.identity, title: ""),
            );
            break;
          default:
        }
      },
      child: Container(
        color: context.color.accentContrastColor,
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              badges.Badge(
                position: badges.BadgePosition.custom(end: 2),
                showBadge: notification.isRead,
                badgeStyle: badges.BadgeStyle(
                  badgeColor: context.color.primaryWarningColor,
                ),
                child: Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: context.color.mutedContrastColor,
                    shape: BoxShape.circle,
                  ),
                  child: Icon(
                    notification.type == "job"
                        ? Icons.work_outline
                        : (notification.type == "order"
                            ? Icons.receipt_long_outlined
                            : (notification.type == "offer"
                                ? Icons.local_offer_outlined
                                : (notification.type == "ticket"
                                    ? Icons.support_agent_outlined
                                    : Icons.notifications_none_outlined))),
                    size: 20,
                    color: context.color.primaryContrastColor,
                  ),
                ),
              ),
              16.toWidth,
              Expanded(
                flex: 1,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      translateNotification(notification.message),
                      style: context.titleMedium?.bold.copyWith(),
                    ),
                    4.toHeight,
                    Text(
                      () {
                        timeago.setLocaleMessages('fr', timeago.FrMessages());
                        String slug = context.dProvider.languageSlug ?? 'fr';
                        if (slug.startsWith('fr')) {
                          slug = 'fr';
                        }
                        return timeago.format(
                          notification.createdAt ?? DateTime.now(),
                          locale: slug,
                        );
                      }(),
                      style: context.titleSmall?.copyWith(),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  String translateNotification(String? msg) {
    if (msg == null) return "N/A";
    
    String text = msg.toLowerCase();
    
    if (text.contains("new support ticket")) {
      return "Nouveau ticket de support";
    }
    if (text.contains("ticket updates available")) {
      return "Mises à jour de ticket disponibles";
    }
    if (text.contains("job activated")) {
      return "Demande activée" + (msg.contains("ID#") ? " ID# ${msg.split("ID#").last}" : "");
    }
    if (text.contains("job inactive")) {
      return "Demande désactivée" + (msg.contains("ID#") ? " ID# ${msg.split("ID#").last}" : "");
    }
    if (text.contains("job published")) {
      return "Demande publiée" + (msg.contains("ID#") ? " ID# ${msg.split("ID#").last}" : "");
    }
    if (text.contains("job unpublished")) {
      return "Demande dépubliée" + (msg.contains("ID#") ? " ID# ${msg.split("ID#").last}" : "");
    }
    if (text.contains("service activated")) {
      return "Service activé";
    }
    if (text.contains("service inactive")) {
      return "Service désactivé";
    }
    if (text.contains("account unsuspended")) {
      return "Compte réactivé";
    }
    if (text.contains("account suspended")) {
      return "Compte suspendu";
    }
    if (text.contains("a new order has been placed")) {
      return "Une nouvelle commande a été passée";
    }
    if (text.contains("order status changed")) {
      return "Le statut de la commande a changé";
    }
    if (text.contains("order payment status complete")) {
      return "Statut du paiement de la commande : Complété";
    }
    if (text.contains("new offer")) {
      return "Nouvelle offre reçue";
    }
    
    return msg.tr();
  }
}
