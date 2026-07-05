import 'package:flutter/material.dart';

class ChatListViewModel {
  ScrollController scrollController = ScrollController();
  final ValueNotifier<String> searchQuery = ValueNotifier("");

  ChatListViewModel._init();
  static ChatListViewModel? _instance;
  static ChatListViewModel get instance {
    _instance ??= ChatListViewModel._init();
    return _instance!;
  }

  ChatListViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }
}
