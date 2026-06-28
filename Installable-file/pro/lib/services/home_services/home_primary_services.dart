import 'package:flutter/material.dart';
import 'package:prohand/helper/app_urls.dart';

import '../../data/network/network_api_services.dart';
import '../../models/service_models/service_model.dart';

class HomePrimaryService with ChangeNotifier {
  List<Service> primaryServices = [];
  Service get service => Service(
      id: 3,
      title: "Home Cleaning Services at Miami, FL ",
      price: 266,
      image: "https://i.postimg.cc/tCY4JbQq/order-attachment-1716468898.jpg",
      discountPrice: 199,
      providerName: "John Doe",
      providerImage: "https://i.postimg.cc/y8nKyrzQ/ML1.png",
      category: "Painting",
      isFavorite: false,
      avgRating: 4.5,
      unit: "1 hr");
  fetchHomePrimaryServices() async {
    var url = AppUrls.areaUrl;

    final responseData = await NetworkApiServices().getApi(url, null);

    if (responseData != null) {
      return true;
    }
  }
}
