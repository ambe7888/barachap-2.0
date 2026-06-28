import 'package:prohandy_client/models/home_providers_model.dart';

class OfferListModel {
  final dynamic id;
  final String matching;
  final DateTime createdAt;
  final HomeProvidersModel provider;

  final num budget;
  OfferListModel({
    this.id,
    required this.budget,
    required this.matching,
    required this.createdAt,
    required this.provider,
  });
}
