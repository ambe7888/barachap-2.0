import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/app_urls.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/data/network/network_api_services.dart';
import 'package:prohandy_client/models/home_models/services_list_model.dart';
import 'package:prohandy_client/utils/service_card/service_card.dart';
import 'package:prohandy_client/views/home_view/components/services_horizontal_skeleton.dart';
import '../../../models/category_model.dart';

class HomeCategoryServicesList extends StatefulWidget {
  final Category category;
  const HomeCategoryServicesList({super.key, required this.category});

  @override
  State<HomeCategoryServicesList> createState() => _HomeCategoryServicesListState();
}

class _HomeCategoryServicesListState extends State<HomeCategoryServicesList> {
  ServiceListModel? _services;
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _fetchCategoryServices();
  }

  _fetchCategoryServices() async {
    try {
      final url = "${AppUrls.serviceListUrl}?cat_id=${widget.category.id}";
      final responseData = await NetworkApiServices().getApi(url, widget.category.name ?? "");
      if (responseData != null) {
        if (mounted) {
          setState(() {
            _services = ServiceListModel.fromJson(responseData);
            _loading = false;
          });
        }
      } else {
        if (mounted) {
          setState(() {
            _loading = false;
          });
        }
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _loading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_loading) {
      return const ServicesHorizontalSkeleton();
    }
    if (_services == null || _services!.allServices.isEmpty) {
      return const SizedBox();
    }
    return Container(
      color: context.color.accentContrastColor,
      margin: const EdgeInsets.only(top: 8),
      padding: const EdgeInsets.symmetric(vertical: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 24),
            child: Text(
              widget.category.name ?? "",
              style: context.titleMedium?.bold,
            ),
          ),
          8.toHeight,
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 24),
            child: Wrap(
              spacing: 16,
              children: _services!.allServices
                  .map((e) => ServiceCard(service: e))
                  .toList(),
            ),
          ),
        ],
      ),
    );
  }
}
