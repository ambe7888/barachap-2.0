import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohandy_client/helper/extension/string_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/models/home_models/services_list_model.dart';

import '../../helper/db_helper.dart';

class CartService with ChangeNotifier {
  final Map _cartItem = {};

  Map get cartList {
    final tempList = jsonEncode(_cartItem);
    return jsonDecode(tempList);
  }

  num get subTotal {
    num amount = 0;

    cartList.values.toList().forEach((item) {
      amount += item["total"].toString().tryToParse;
    });

    return amount;
  }

  num get taxTotal {
    num amount = 0;

    cartList.values.toList().forEach((item) {
      amount += item["tax"].toString().tryToParse;
    });

    return amount;
  }

  get servicesForOrder {
    final services = [];
    cartList.values.toList().forEach((item) {
      try {
        final date = DateTime.tryParse(item["date"].toString());
        services.add({
          "service_id": item["service"]?["id"],
          "staff_id": item["staff"]?["id"],
          "location_id": item["address"]?["id"],
          "date": DateFormat("yyyy-MM-dd").format(date!),
          "schedule": item["schedule"]?["schedule"],
          "order_note": item["note"],
        });
      } catch (e) {
        debugPrint(item.toString());
      }
    });
    return services;
  }

  get addonsForOrder {
    final addons = [];
    final serviceAddons = [];
    cartList.values.toList().forEach((item) {
      serviceAddons.add(item["addons"]);
    });
    for (var add in serviceAddons) {
      try {
        add.values.toList().forEach((a) {
          addons.add({
            "addon_service_id": a["addon_service_id"],
            "service_id": a["service_id"],
            "quantity": a["quantity"]
          });
        });
      } catch (e) {}
    }
    return addons;
  }

  num get totalTax {
    num amount = 0;

    cartList.values.toList().forEach((item) {
      amount += item["tax"].toString().tryToParse;
    });

    return amount;
  }

  List<ServiceModel> get jobs => List<ServiceModel>.from(
      cartList.values.toList().map((x) => ServiceModel.fromJson(x)));

  addToCart(
    String id,
    service,
    address,
    date,
    schedule,
    staff,
    newAddons,
    note,
    tax,
    total,
  ) async {
    final cartId = DateTime.now().millisecondsSinceEpoch.toString();
    final item = {
      'serviceId': cartId,
      "service": jsonEncode(service),
      "address": jsonEncode(address),
      "addons": jsonEncode(newAddons),
      "date": date ?? "",
      "schedule": jsonEncode(schedule),
      "staff": jsonEncode(staff),
      "note": note ?? "",
      "tax": tax.toString(),
      "total": total.toString(),
    };
    await DbHelper.insert('cart', item);
    final cartI = {
      'serviceId': cartId,
      "service": service,
      "address": address,
      "addons": newAddons,
      "date": date,
      "schedule": schedule,
      "staff": staff,
      "note": note,
      "tax": tax,
      "total": total,
    };
    _cartItem.putIfAbsent(cartId, () => cartI);
    LocalKeys.addedToCartSuccessfully.showToast();
    notifyListeners();
  }

  updateToCart(
    String id,
    service,
    address,
    date,
    schedule,
    staff,
    newAddons,
    note,
    tax,
    total,
  ) async {
    final item = {
      'serviceId': id.toString(),
      "service": jsonEncode(service),
      "address": jsonEncode(address),
      "addons": jsonEncode(newAddons),
      "date": date ?? "",
      "schedule": jsonEncode(schedule),
      "staff": jsonEncode(staff),
      "note": note ?? "",
      "tax": tax.toString(),
      "total": total.toString(),
    };
    await DbHelper.updatedb('cart', id.toString(), item);
    final cartI = {
      'serviceId': id.toString(),
      "service": service,
      "address": address,
      "addons": newAddons,
      "date": date,
      "schedule": schedule,
      "staff": staff,
      "note": note,
      "tax": tax,
      "total": total,
    };
    _cartItem.update(id, (_) => cartI);
    LocalKeys.updatedSuccessfully.showToast();
    notifyListeners();
  }

  deleteFromCart(String id) async {
    await DbHelper.deleteDbSI('cart', id);
    _cartItem.remove(id);
    LocalKeys.itemRemovedSuccessfully.showToast();
    notifyListeners();
  }

  fetchCarts() async {
    final dbData = await DbHelper.fetchDb('cart');

    if (dbData.isEmpty) {
      return;
    }
    for (var element in dbData) {
      try {
        final data = {
          'serviceId': element["serviceId"].toString(),
          "service": jsonDecode(element["service"]),
          "address": jsonDecode(element["address"]),
          "addons": jsonDecode(element["addons"]),
          "date": element["date"] ?? "",
          "staff": jsonDecode(element["staff"]) ?? "",
          "schedule": jsonDecode(element["schedule"]),
          "note": element["note"] ?? "",
          "tax": element["tax"].toString().tryToParse,
          "total": element["total"].toString().tryToParse,
        };
        _cartItem.putIfAbsent(element['serviceId'], () => data);
      } catch (e) {}
    }

    notifyListeners();
  }

  void clearCart() async {
    final cartLength = cartList.length - 1;

    for (var i = cartLength; i >= 0; i--) {
      final id = cartList.keys.toList()[i];
      await DbHelper.deleteDbSI('cart', id);
      _cartItem.remove(id);
      LocalKeys.itemRemovedSuccessfully.showToast();
    }
    notifyListeners();
  }
}


/*Cart Item Dummy

{
  "service":serviceDetails,
  "address":address,
  "addons":addons,
  "date":date,
  "schedule":schedule,
  "schedule":staff,
  "note":note,
  "tax": tax,
  "total":total,

}

 */