import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

import '../constant_helper.dart';

extension SizedBoxExtension on int {
  Widget get toHeight {
    return SizedBox(
      height: toDouble(),
    );
  }

  Widget get toWidth {
    return SizedBox(
      width: toDouble(),
    );
  }
}

extension PaddingExtension on num {
  EdgeInsetsGeometry get paddingAll => EdgeInsets.all(toDouble());

  EdgeInsetsGeometry get paddingH =>
      EdgeInsets.symmetric(horizontal: toDouble());
  EdgeInsetsGeometry get paddingV => EdgeInsets.symmetric(vertical: toDouble());
}

extension CurrencyExtension on num {
  String get cur {
    NumberFormat formatter = NumberFormat.currency(
      locale: dProvider.languageSlug.split('_').firstOrNull?.trim() ?? 'en',
      symbol: dProvider.currencySymbol,
      decimalDigits: 2,
    );
    return formatter.format(this).trim();
  }
}

extension RadiusExtension on num {
  BorderRadiusGeometry get circular => BorderRadius.circular(toDouble());
}
