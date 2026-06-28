// import 'dart:async';

// import 'package:flutter/material.dart';
// import 'package:prohand/helper/extension/context_extension.dart';
// import 'package:prohand/helper/extension/string_extension.dart';
// import 'package:prohand/services/category_dropdown_service.dart';
// import 'package:prohand/services/skill_dropdown_service.dart';
// import 'package:prohand/utils/components/custom_future_widget.dart';
// import 'package:provider/provider.dart';

// import '../../helper/local_keys.g.dart';
// import '../../helper/svg_assets.dart';
// import '../../models/category_model.dart';
// import '../../services/addrtess/states_service.dart';
// import 'custom_preloader.dart';
// import 'scrolling_preloader.dart';

// class SkillSheet extends StatelessWidget {
//   final textFieldHint;
//   final onChanged;
//   final textStyle;

//   SkillSheet({
//     super.key,
//     this.textFieldHint,
//     this.onChanged,
//     this.textStyle,
//   });

//   ValueNotifier<String> subCatString = ValueNotifier('');
//   Timer? scheduleTimeout;
//   ScrollController scrollController = ScrollController();
//   @override
//   Widget build(BuildContext context) {
//     scrollController.addListener(() {
//       tryToLoadMore(context);
//     });
//     return Consumer<SkillDropdownService>(builder: (context, sd, child) {
//       return Container(
//         decoration: BoxDecoration(
//           borderRadius: const BorderRadius.only(
//               topLeft: Radius.circular(20), topRight: Radius.circular(20)),
//           color: context.color.accentContrastColor,
//         ),
//         constraints: BoxConstraints(
//             maxHeight: context.height / 2 +
//                 (MediaQuery.of(context).viewInsets.bottom / 2)),
//         child: Column(
//           children: [
//             Align(
//               alignment: Alignment.center,
//               child: Container(
//                 height: 4,
//                 width: 48,
//                 margin: const EdgeInsets.all(8),
//                 decoration: BoxDecoration(
//                   borderRadius: BorderRadius.circular(12),
//                   color: context.dProvider.black7,
//                 ),
//               ),
//             ),
//             Padding(
//               padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
//               child: TextFormField(
//                   decoration: InputDecoration(
//                       hintText: textFieldHint ?? LocalKeys.searchSkill,
//                       prefixIcon: Padding(
//                         padding: const EdgeInsets.symmetric(horizontal: 12),
//                         child: SvgAssets.search.toSVG,
//                       )),
//                   onChanged: (value) async {
//                     scheduleTimeout?.cancel();
//                     scheduleTimeout = Timer(const Duration(seconds: 1), () {
//                       sd.setSkillSearchValue(value);
//                     });
//                   }),
//             ),
//             ValueListenableBuilder(
//                 valueListenable: subCatString,
//                 builder: (context, searchText, child) {
//                   return CustomFutureWidget(
//                     isLoading: sd.skillLoading,
//                     shimmer: const CustomPreloader(),
//                     child: Expanded(
//                       child: ListView.separated(
//                           shrinkWrap: true,
//                           controller: scrollController,
//                           padding: const EdgeInsets.only(
//                               right: 20, left: 20, bottom: 20),
//                           itemBuilder: (context, index) {
//                             if ((sd.skillDropdownList.length == index &&
//                                 sd.nextPage != null)) {
//                               return ScrollPreloader(
//                                   loading: sd.nextPageLoading);
//                             }
//                             if (sd.skillDropdownList.isEmpty) {
//                               return SizedBox(
//                                 width: context.width - 60,
//                                 height: 64,
//                                 child: Center(
//                                   child: Text(
//                                     LocalKeys.noResultFound,
//                                     style: textStyle,
//                                   ),
//                                 ),
//                               );
//                             }
//                             if (sd.skillDropdownList.length == index) {
//                               return SizedBox(
//                                 width: context.width - 60,
//                                 height: 64,
//                                 child: Center(
//                                   child: Text(
//                                     LocalKeys.noResultFound,
//                                     style: textStyle,
//                                   ),
//                                 ),
//                               );
//                             }
//                             final element = sd.skillDropdownList[index];

//                             return InkWell(
//                               onTap: () {
//                                 Navigator.pop(context);
//                                 onChanged(element);
//                               },
//                               child: Padding(
//                                 padding: const EdgeInsets.symmetric(
//                                     horizontal: 8, vertical: 14),
//                                 child: Text(
//                                   element.skill ?? "--",
//                                   style: textStyle,
//                                 ),
//                               ),
//                             );
//                           },
//                           separatorBuilder: (context, index) => const SizedBox(
//                                 height: 8,
//                                 child: Center(child: Divider()),
//                               ),
//                           itemCount: sd.skillDropdownList.length +
//                               (sd.nextPage != null && !sd.nexLoadingFailed
//                                   ? 1
//                                   : 0)),
//                     ),
//                   );
//                 })
//           ],
//         ),
//       );
//     });
//   }

//   tryToLoadMore(BuildContext context) {
//     try {
//       final sdProvider =
//           Provider.of<SkillDropdownService>(context, listen: false);
//       final nextPage = sdProvider.nextPage;
//       final nextPageLoading = sdProvider.nextPageLoading;

//       if (scrollController.offset >=
//               scrollController.position.maxScrollExtent &&
//           !scrollController.position.outOfRange) {
//         if (nextPage != null && !nextPageLoading) {
//           sdProvider.fetchNextPage();
//           return;
//         }
//       }
//     } catch (e) {}
//   }
// }
