import 'package:prohand/models/home_providers_model.dart';

class ApplicationModel {
  final dynamic id;
  final String matching;
  final DateTime createdAt;
  final HomeProvidersModel provider;

  final num budget;
  ApplicationModel({
    this.id,
    required this.budget,
    required this.matching,
    required this.createdAt,
    required this.provider,
  });
}
