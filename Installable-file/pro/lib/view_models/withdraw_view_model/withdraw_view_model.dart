import 'package:flutter/material.dart';
import 'package:prohand/services/withdraw_services/withdraw_history_service.dart';
import 'package:provider/provider.dart';

class WithdrawViewModel {
  final ScrollController scrollController = ScrollController();

  WithdrawViewModel._init();
  static WithdrawViewModel? _instance;
  static WithdrawViewModel get instance {
    _instance ??= WithdrawViewModel._init();
    return _instance!;
  }

  WithdrawViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }

  tryToLoadMore(BuildContext context) {
    try {
      final wh = Provider.of<WithdrawHistoryService>(context, listen: false);
      final nextPage = wh.nextPage;
      final nextPageLoading = wh.nextPageLoading;

      if (scrollController.offset >=
              scrollController.position.maxScrollExtent &&
          !scrollController.position.outOfRange) {
        if (nextPage != null && !nextPageLoading) {
          wh.fetchNextPage();
          return;
        }
      }
    } catch (e) {}
  }
}
