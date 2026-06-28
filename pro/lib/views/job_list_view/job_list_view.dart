import 'package:flutter/material.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/extension/widget_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/helper/svg_assets.dart';
import 'package:prohand/services/job_services/job_list_service.dart';
import 'package:prohand/utils/components/custom_refresh_indicator.dart';
import 'package:prohand/views/job_list_view/components/saved_job_list.dart';
import 'package:prohand/views/job_list_view/job_list_map_view.dart';
import 'package:provider/provider.dart';

import '../../utils/components/empty_widget.dart';
import '../../utils/components/scrolling_preloader.dart';
import '../../view_models/job_list_view_model/job_list_view_model.dart';
import 'components/job_list_skeleton.dart';
import 'components/job_tile.dart';

class JobListView extends StatefulWidget {
  const JobListView({super.key});

  @override
  State<JobListView> createState() => _JobListViewState();
}

class _JobListViewState extends State<JobListView> {
  bool alreadyListening = false;
  @override
  Widget build(BuildContext context) {
    final jlm = JobListViewModel.instance;
    final jlProvider = Provider.of<JobListService>(context, listen: false);
    if (!alreadyListening) {
      alreadyListening = true;
      jlm.scrollController.addListener(() {
        jlm.tryToLoadMore(context).then((result) {
          if (result != true) return;
          setState(() {});
        });
      });
    }
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        title: Text(LocalKeys.jobList),
        actions: [
          ValueListenableBuilder(
            valueListenable: jlm.viewType,
            builder: (context, value, child) {
              return GestureDetector(
                onTap: () {
                  jlm.changeViewType();
                },
                child: SvgAssets.map.toSVGSized(24,
                    color: value == JobListViewTypes.map
                        ? primaryColor
                        : context.color.tertiaryContrastColo),
              );
            },
          ),
          20.toWidth
        ],
      ),
      body: CustomRefreshIndicator(
        onRefresh: () async {
          await jlProvider.fetchJobList().then((_) {
            setState(() {});
          });
        },
        child: Consumer<JobListService>(builder: (context, jl, child) {
          return ValueListenableBuilder(
              valueListenable: jlm.viewType,
              builder: (context, value, child) {
                return value == JobListViewTypes.map
                    ? JobListMapView(jl: jl)
                    : Column(
                        children: [
                          Column(
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
                                          padding: const EdgeInsets.symmetric(
                                              horizontal: 12),
                                          child: SvgAssets.search.toSVGSized(20,
                                              color: context
                                                  .color.tertiaryContrastColo),
                                        ),
                                        suffixIcon: Consumer<JobListService>(
                                            builder: (context, jl, child) {
                                          return jl.title.isEmpty
                                              ? const SizedBox()
                                              : GestureDetector(
                                                  onTap: () {
                                                    jlm.titleController.clear();
                                                    context.unFocus;

                                                    setState(() {
                                                      Provider.of<JobListService>(
                                                              context,
                                                              listen: false)
                                                          .fetchJobList()
                                                          .then((_) {
                                                        setState(() {});
                                                      });
                                                    });
                                                  },
                                                  child: Padding(
                                                    padding: const EdgeInsets
                                                        .symmetric(
                                                        horizontal: 12),
                                                    child: Icon(
                                                        Icons.close_rounded,
                                                        size: 20,
                                                        color: context.color
                                                            .tertiaryContrastColo),
                                                  ),
                                                );
                                        }),
                                      ),
                                      onFieldSubmitted: (value) {
                                        if (value.isNotEmpty) {
                                          setState(() {
                                            Provider.of<JobListService>(context,
                                                    listen: false)
                                                .fetchJobList()
                                                .then((_) {
                                              setState(() {});
                                            });
                                          });
                                        }
                                      },
                                    ),
                                  ),
                                ],
                              ).hp20,
                              SingleChildScrollView(
                                scrollDirection: Axis.horizontal,
                                padding: const EdgeInsets.symmetric(
                                    horizontal: 24, vertical: 6),
                                child: Wrap(
                                  spacing: 6,
                                  children:
                                      jobListFilterValues.map.keys.map((type) {
                                    return ValueListenableBuilder(
                                      valueListenable: jlm.selectedType,
                                      builder: (context, value, child) {
                                        return _button(
                                            title: type,
                                            onPressed: () {
                                              jlm.selectedType.value =
                                                  jobListFilterValues.map[type];
                                              if (jlm.selectedType.value ==
                                                  JobListFilterTypes.saved) {
                                                return;
                                              }

                                              setState(() {
                                                Provider.of<JobListService>(
                                                        context,
                                                        listen: false)
                                                    .fetchJobList()
                                                    .then((_) {
                                                  setState(() {});
                                                });
                                              });
                                            },
                                            isSelected: type ==
                                                jobListFilterValues
                                                    .reverse[value]);
                                      },
                                    );
                                  }).toList(),
                                ),
                              ),
                            ],
                          ),
                          Expanded(
                            child: FutureBuilder(
                              future: jl.shouldAutoFetch
                                  ? jlProvider.fetchJobList()
                                  : null,
                              builder: (c, snap) {
                                if (jl.isLoading) {
                                  return const JobListSkeleton();
                                }
                                return Scrollbar(
                                  controller: jlm.scrollController,
                                  child: ValueListenableBuilder(
                                      valueListenable: jlm.selectedType,
                                      builder: (context, value, child) {
                                        return RepaintBoundary(
                                          child: CustomScrollView(
                                            controller: jlm.scrollController,
                                            physics:
                                                const AlwaysScrollableScrollPhysics(),
                                            slivers: [
                                              16.toHeight.toSliver,
                                              if (jlm.selectedType.value ==
                                                  JobListFilterTypes.saved)
                                                const SavedJobList().toSliver,
                                              if (jlm.selectedType.value !=
                                                      JobListFilterTypes
                                                          .saved &&
                                                  jl.jobListModel.jobs.isEmpty)
                                                SizedBox(
                                                  height: 400,
                                                  child: EmptyWidget(
                                                    title:
                                                        LocalKeys.noJobsFound,
                                                    physics:
                                                        const NeverScrollableScrollPhysics(),
                                                  ),
                                                ).toSliver,
                                              if (jlm.selectedType.value !=
                                                  JobListFilterTypes.saved)
                                                SliverList.separated(
                                                  itemBuilder:
                                                      (context, index) {
                                                    final job = jl.jobListModel
                                                        .jobs[index];
                                                    return JobTile(
                                                      job: job,
                                                    );
                                                  },
                                                  separatorBuilder:
                                                      (context, index) =>
                                                          16.toHeight,
                                                  itemCount: jl
                                                      .jobListModel.jobs.length,
                                                ),
                                              if (jl.nextPage != null &&
                                                  !jl.nexLoadingFailed &&
                                                  jlm.selectedType.value !=
                                                      JobListFilterTypes
                                                          .saved) ...[
                                                24.toHeight.toSliver,
                                                ScrollPreloader(
                                                        loading:
                                                            jl.nextPageLoading)
                                                    .toSliver
                                              ],
                                              24.toHeight.toSliver,
                                            ],
                                          ),
                                        );
                                      }),
                                );
                              },
                            ),
                          ),
                        ],
                      );
              });
        }),
      ),
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
