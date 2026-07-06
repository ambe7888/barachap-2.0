import 'package:flutter/material.dart';
import 'package:prohand/models/color_model.dart';

const Color primaryColor = Color(0xFFed7901);
Color get mutedPrimaryColor => primaryColor.withOpacity(.2);

//Light theme colors
final ColorModel lightColors = ColorModel(
  backgroundColor: const Color(0xFFF5F5F5),
  primaryContrastColor: const Color(0xFF0F172A),
  secondaryContrastColor: const Color(0xFF475569),
  tertiaryContrastColo: const Color(0xFF64748B),
  accentContrastColor: const Color(0xFFFFFFFF),
  mutedContrastColor: const Color(0xFFE2E8F0),
  primarySuccessColor: const Color(0xFF22C55E),
  primaryWarningColor: const Color(0xFFEF4444),
  primaryPendingColor: const Color(0xFFF97316),
  primaryBorderColor: const Color(0xFFE4E4E7),
  cardFillColor: const Color(0xFFFFFFFF),
  inputFillColor: const Color(0xFFCBD5E1),
);

//Dark theme colors
final ColorModel darkColors = ColorModel(
  backgroundColor: const Color(0xFF0D0F11),
  primaryContrastColor: const Color(0xFFBEBEBE),
  secondaryContrastColor: const Color(0xFFE3E3E3),
  tertiaryContrastColo: const Color(0xFF75777B),
  accentContrastColor: const Color(0xFF191D23),
  mutedContrastColor: const Color(0xFF35383D),
  primarySuccessColor: const Color(0xFF22C55E),
  primaryWarningColor: const Color(0xFFEF4444),
  primaryPendingColor: const Color(0xFFF97316),
  primaryBorderColor: const Color(0xFF424E5A),
  cardFillColor: const Color(0xFF262036),
  inputFillColor: const Color(0xFF576776),
);

get gridColors => [
      const Color(0xFFEA580C),
      const Color(0xFF3B82F6),
      const Color(0xFF22C55E),
      const Color(0xFF9333EA),
    ];
