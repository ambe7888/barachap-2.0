import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:provider/provider.dart';

import '../../../services/job_services/job_list_service.dart';
import '../../../view_models/job_list_view_model/job_list_view_model.dart';

class JobListSearchBar extends StatelessWidget {
  const JobListSearchBar({super.key});

  @override
  Widget build(BuildContext context) {
    final jlm = JobListViewModel.instance;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            Expanded(
              flex: 1,
              child: TextFormField(
                controller: jlm.titleController,
                textInputAction: TextInputAction.search,
                decoration: InputDecoration(
                  hintText: LocalKeys.search,
                  prefixIcon: Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 12),
                    child: SvgAssets.search.toSVGSized(20,
                        color: context.color.tertiaryContrastColo),
                  ),
                  suffixIcon:
                      Consumer<JobListService>(builder: (context, jl, child) {
                    return jl.title.isEmpty
                        ? const SizedBox()
                        : GestureDetector(
                            onTap: () {
                              jlm.titleController.clear();
                              context.unFocus;
                              Provider.of<JobListService>(context,
                                      listen: false)
                                  .fetchJobList();
                            },
                            child: Padding(
                              padding:
                                  const EdgeInsets.symmetric(horizontal: 12),
                              child: Icon(Icons.close_rounded,
                                  size: 20,
                                  color: context.color.tertiaryContrastColo),
                            ),
                          );
                  }),
                ),
                onFieldSubmitted: (value) {
                  if (value.isNotEmpty) {
                    Provider.of<JobListService>(context, listen: false)
                        .fetchJobList();
                  }
                },
              ),
            ),
          ],
        ).hp20,
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 6),
          child: Wrap(
            spacing: 6,
            children: jobListFilterValues.map.keys.map((type) {
              return ValueListenableBuilder(
                valueListenable: jlm.selectedType,
                builder: (context, value, child) {
                  return _button(
                      title: type,
                      onPressed: () {
                        jlm.selectedType.value = jobListFilterValues.map[type];
                        if (jlm.selectedType.value ==
                            JobListFilterTypes.saved) {
                          return;
                        }
                        Provider.of<JobListService>(context, listen: false)
                            .fetchJobList();
                      },
                      isSelected: type == jobListFilterValues.reverse[value]);
                },
              );
            }).toList(),
          ),
        ),
      ],
    );
  }

  Widget _button(
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
