import 'package:flutter/material.dart';
import 'package:prohand/helper/local_keys.g.dart';

enum Status {
  LOADING,
  NOT_INITIATED,
  NOT_AVAILABLE,
  INVALID,
  AVAILABLE,
}

final chatAvatarBGColors = [
  const Color(0xff0087BF),
  const Color(0xff5A8770),
  const Color(0xff9A89B5),
  const Color(0xffF5888D),
  const Color(0xff98A2B3),
  const Color(0xffF18636),
];

final List<String> supportedFileTypesInC = ["png"];

// support ticket priority options
final priorityList = ["low", "normal", "high", "urgent"];

int profileImageMaxSize = 2; //mb

//Identity verify types
final idVTypes = {
  "nid": LocalKeys.nationalId,
  "driving license": LocalKeys.drivingLicense,
  "passport": LocalKeys.passport,
};
