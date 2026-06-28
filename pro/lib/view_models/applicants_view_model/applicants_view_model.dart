import 'package:flutter/material.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../order_list_view_model/order_status_enums.dart';

class ApplicantsViewModel {
  ValueNotifier<ApplicationType> selectedType =
      ValueNotifier(ApplicationType.all);
  ApplicantsViewModel._init();
  static ApplicantsViewModel? _instance;
  static ApplicantsViewModel get instance {
    _instance ??= ApplicantsViewModel._init();
    return _instance!;
  }

  ApplicantsViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }
}

enum ApplicationType {
  all,
  pending,
  hired,
  rejected,
  shortlisted,
}

final applicationTypeValues = EnumValues({
  LocalKeys.allApplications: ApplicationType.all,
  LocalKeys.pending: ApplicationType.pending,
  LocalKeys.hired: ApplicationType.hired,
  LocalKeys.rejected: ApplicationType.rejected,
  LocalKeys.shortlisted: ApplicationType.shortlisted,
});
