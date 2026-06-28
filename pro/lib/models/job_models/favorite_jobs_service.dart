import 'dart:convert';

import 'package:flutter/material.dart';

import '../../helper/db_helper.dart';
import 'job_list_model.dart';

class FavoriteJobsService with ChangeNotifier {
  final Map _favoriteJobs = {};

  Map get favoriteList {
    return Map.from(_favoriteJobs);
  }

  List<Job> get jobs =>
      List<Job>.from(_favoriteJobs.values.toList().map((x) => Job.fromJson(x)));

  addToFavorite(String id, data) async {
    await DbHelper.insert('favorite', {
      'jobId': id,
      'data': jsonEncode(data),
    });
    _favoriteJobs.putIfAbsent(id, () => data);
    notifyListeners();
  }

  deleteFromFavorite(String id) async {
    await DbHelper.deleteDbSI('favorite', id);
    _favoriteJobs.remove(id);
    notifyListeners();
  }

  toggleFavorite(String id, data) {
    if (_favoriteJobs.containsKey(id)) {
      deleteFromFavorite(id);
    } else {
      addToFavorite(id, data);
    }
  }

  bool isFavorite(String id) {
    return _favoriteJobs.containsKey(id);
  }

  fetchFavorites() async {
    final dbData = await DbHelper.fetchDb('favorite');

    if (dbData.isEmpty) {
      return;
    }
    for (var element in dbData) {
      final data = jsonDecode(element['data']);
      if (data != null) {
        _favoriteJobs.putIfAbsent(element['jobId'], () => data);
      }
    }

    notifyListeners();
  }
}
