import '../provider_model.dart';

class ApplicationDetailsModel {
  final dynamic id;
  final num budget;
  final String matching;
  final DateTime? createdAt;
  final ProviderModel provider;
  final String coverLetter;
  ApplicationDetailsModel({
    this.id,
    required this.budget,
    required this.matching,
    this.createdAt,
    required this.provider,
    required this.coverLetter,
  });
}
