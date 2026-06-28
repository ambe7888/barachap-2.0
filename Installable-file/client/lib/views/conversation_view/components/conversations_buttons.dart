// import 'package:flutter/material.dart';
// import 'package:path/path.dart';
// import 'package:prohandy_client/helper/extension/context_extension.dart';
// import 'package:prohandy_client/helper/extension/int_extension.dart';
// import 'package:prohandy_client/helper/extension/string_extension.dart';

// import '../../../helper/local_keys.g.dart';
// import '../../../helper/svg_assets.dart';
// import '../../../utils/components/custom_preloader.dart';
// import '../../../view_models/conversation_view_model/conversation_view_model.dart';

// class ConversationButtons extends StatelessWidget {
//   final clientId;
//   const ConversationButtons({
//     super.key,
//     this.clientId,
//   });

//   @override
//   Widget build(BuildContext context) {
//     final cm = ConversationViewModel.instance;
//     return Row(
//       children: [
//         Expanded(
//           flex: 16,
//           child: ValueListenableBuilder(
//               valueListenable: cm.aFile,
//               builder: (context, file, child) {
//                 return OutlinedButton(
//                   onPressed: () {
//                     cm.attachFile();
//                   },
//                   child: Row(
//                     mainAxisAlignment: MainAxisAlignment.center,
//                     children: [
//                       (file != null ? SvgAssets.trash : SvgAssets.paperclip)
//                           .toSVGSized(18),
//                       6.toWidth,
//                       Expanded(
//                         flex: 1,
//                         child: Text(
//                           file == null
//                               ? LocalKeys.attachFile
//                               : basename(file.path),
//                           maxLines: 1,
//                         ),
//                       ),
//                     ],
//                   ),
//                 );
//               }),
//         ),
//         12.toWidth,
//         Expanded(
//           flex: 16,
//           child: ValueListenableBuilder(
//               valueListenable: cm.loading,
//               builder: (context, loading, child) {
//                 return ElevatedButton(
//                   onPressed: () async {
//                     cm.trySendingMessage(context, clientId);
//                   },
//                   child: loading
//                       ? const Center(
//                           child: CustomPreloader(width: 80, whiteColor: true))
//                       : SvgAssets.send.toSVGSized(24,
//                           color: context.color.tertiaryContrastColo),
//                 );
//               }),
//         ),
//       ],
//     );
//   }
// }
