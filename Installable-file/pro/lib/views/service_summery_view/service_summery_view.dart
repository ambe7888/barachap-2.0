// import 'package:flutter/material.dart';
// import 'package:prohand/helper/local_keys.g.dart';
// import 'package:prohand/utils/components/navigation_pop_icon.dart';

// class ServiceSummeryView extends StatelessWidget {
//   const ServiceSummeryView({super.key});

//   @override
//   Widget build(BuildContext context) {
//     return Scaffold(
//       appBar: AppBar(
//         leading: const NavigationPopIcon(),
//         title: Text(LocalKeys.summary),
//       ),
//       body: CustomScrollView(
//         slivers: [
//           SliverAppBar(
//
//             backgroundColor: Colors.transparent,
//             surfaceTintColor: Colors.transparent,
//             pinned: true,
//             titleSpacing: 0,

//             leading: const NavigationPopIcon(),
//             actions: [
//               Padding(
//                 padding: const EdgeInsets.all(8.0),
//                 child: CircleAvatar(
//                   radius: 16,
//                   backgroundColor: context.color.accentContrastColor,
//                   child: SvgAssets.heart.toSVG,
//                 ),
//               )
//             ],
//             expandedHeight: 250,
//             flexibleSpace: const ServiceDetailsImages(images: [
//               "https://i.postimg.cc/wxhKk2g9/3d-rendering-loft-luxury-living-room-with-bookshelf-near-bookshelf.jpg",
//               "https://i.postimg.cc/yNk4XzYG/3d-rendering-loft-luxury-living-room-with-shelf-near-dining-table.jpg",
//               "https://i.postimg.cc/0yt1wNxm/3d-rendering-white-wood-living-room-near-bedroom-upstair.jpg",
//               "https://i.postimg.cc/Lswdx0J8/3d-visualization-house-house-nature-with-beautiful-garden.jpg",
//               "https://i.postimg.cc/02fLTNz3/gray-sofa-white-living-room-with-copy-space.jpg",
//               "https://i.postimg.cc/hPgkZmtT/house-isolated-field.jpg",
//             ]),
//           ),
//           const ServiceDetailsBasics().toSliver,
//           8.toHeight.toSliver,
//           const SliverAppBar(
//             titleSpacing: 0,
//             pinned: true,
//             primary: false,
//             leadingWidth: 0,
//             leading: SizedBox(),
//             title: ServiceDetailsTabsTitles(),
//             flexibleSpace: SizedBox(),
//           ),
//           const ServiceDetailsTabs().toSliver,
//           8.toHeight.toSliver,
//           const ServiceDetailsOffers().toSliver,
//           8.toHeight.toSliver,
//           const ServiceDetailsExcludes().toSliver,
//           8.toHeight.toSliver,
//           const ServiceDetailsGallery(
//             gallery: [
//               "https://i.postimg.cc/wxhKk2g9/3d-rendering-loft-luxury-living-room-with-bookshelf-near-bookshelf.jpg",
//               "https://i.postimg.cc/yNk4XzYG/3d-rendering-loft-luxury-living-room-with-shelf-near-dining-table.jpg",
//               "https://i.postimg.cc/0yt1wNxm/3d-rendering-white-wood-living-room-near-bedroom-upstair.jpg",
//               "https://i.postimg.cc/Lswdx0J8/3d-visualization-house-house-nature-with-beautiful-garden.jpg",
//               "https://i.postimg.cc/02fLTNz3/gray-sofa-white-living-room-with-copy-space.jpg",
//               "https://i.postimg.cc/hPgkZmtT/house-isolated-field.jpg",
//             ],
//           ).toSliver,
//           8.toHeight.toSliver,
//           const ServiceDetailsSecurity().toSliver,
//           8.toHeight.toSliver,
//           const ServiceDetailsCancellationPolicy().toSliver,
//         ],
//       ),
//     );
//   }
// }
