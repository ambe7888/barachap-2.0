import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/utils/components/field_label.dart';
import 'package:prohand/utils/components/info_tile.dart';

class ServiceProviderAbout extends StatelessWidget {
  const ServiceProviderAbout({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          color: context.color.accentContrastColor,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          child:
              Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            InfoTile(title: LocalKeys.jobCompleted, value: "23"),
            12.toHeight,
            InfoTile(
                title: LocalKeys.memberSince,
                value: DateFormat(
                  "MMM dd, yyyy",
                  dProvider.languageSlug,
                ).format(DateTime.now())),
            Divider(
              color: context.color.primaryBorderColor,
              height: 32,
            ),
            InfoTile(title: LocalKeys.serviceArea, value: "2B Baker street"),
            12.toHeight,
            InfoTile(title: LocalKeys.totalStaff, value: "28"),
          ]),
        ),
        8.toHeight,
        Container(
          color: context.color.accentContrastColor,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              FieldLabel(label: LocalKeys.availableSlots),
              SizedBox(
                height: 36,
                child: ListView.separated(
                    scrollDirection: Axis.horizontal,
                    itemBuilder: (context, index) {
                      final isSelected = index == 3;
                      return Container(
                          padding:
                              const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                          decoration: BoxDecoration(
                              color: isSelected ? primaryColor : null,
                              border: Border(
                                bottom: BorderSide(
                                  color: context.color.primaryBorderColor,
                                ),
                              )),
                          child: Text(
                            "Sunday",
                            style: context.titleSmall?.bold.copyWith(
                                color: isSelected
                                    ? context.color.accentContrastColor
                                    : null),
                          ));
                    },
                    separatorBuilder: (context, index) => 0.toWidth,
                    itemCount: 6),
              ),
              12.toHeight,
              SizedBox(
                width: double.infinity,
                child: Wrap(
                  spacing: 16,
                  runSpacing: 16,
                  alignment: WrapAlignment.center,
                  children: List.generate(12, (i) {
                    return _button(
                      title: "10:00-13:00",
                      onPressed: () {},
                      isSelected: i == 5,
                    );
                  }),
                ),
              ),
              const Row(),
            ],
          ),
        )
      ],
    );
  }

  _button(
      {required String title,
      bool isSelected = false,
      required void Function()? onPressed}) {
    return isSelected
        ? ElevatedButton.icon(
            onPressed: () {},
            label: Text(title),
          )
        : OutlinedButton.icon(
            onPressed: onPressed,
            label: Text(title),
          );
  }
}
