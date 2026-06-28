import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:money_formatter/money_formatter.dart';
import 'package:prohand/customizations/colors.dart';
import 'package:prohand/helper/extension/context_extension.dart';
import 'package:prohand/helper/extension/int_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';
import 'package:prohand/services/dynamics_services/dynamics_service.dart';
import 'package:prohand/view_models/home_view_model/home_view_model.dart';
import 'package:provider/provider.dart';

class HomeBarChart extends StatelessWidget {
  HomeBarChart({super.key});

  final Duration animDuration = const Duration(milliseconds: 250);

  int touchedIndex = -1;

  bool isPlaying = false;
  List<String> months = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
  ];

  List<List<double>> xyValues = [
    [0, 3.2],
    [1, 4.5],
    [2, 1.7],
    [3, 2.8],
    [4, 3.9],
    [5, 1.5],
    [6, 4.3],
  ];

  var yValues = [500, 5000, 1000, 1500, 2000, 2500];

  @override
  Widget build(BuildContext context) {
    final hvm = HomeViewModel.instance;
    return Container(
      color: context.color.accentContrastColor,
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(
                  flex: 1,
                  child: Text(LocalKeys.earnings,
                      style: context.headlineLarge?.bold)),
              // Consumer<RevenueInfoService>(builder: (context, rs, child) {
              //   return CustomDropdown(
              //     revenueInfoTypeValue.reverse[rs.revenueInfoType] ?? "",
              //     revenueInfoTypeValue.map.keys.toList(),
              //     (v) {
              //       debugPrint(
              //           revenueInfoTypeValue.map.keys.toList().toString());
              //       rs.setRevenueInfoType(
              //         revenueInfoTypeValue.map[v] ?? RevenueInfoType.thisWeek,
              //       );
              //     },
              //     value: revenueInfoTypeValue.reverse[rs.revenueInfoType],
              //   );
              // })
            ],
          ),
          12.toHeight,
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 10),
            child: Consumer<DynamicsService>(
                builder: (context, rtlProvider, child) {
              return Text(
                rtlProvider.currencyRight
                    ? '${MoneyFormatter(amount: 8465).output.withoutFractionDigits}${rtlProvider.currencyCode}'
                    : '${rtlProvider.currencyCode}${MoneyFormatter(amount: 8465).output.withoutFractionDigits}',
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
                style: Theme.of(context).textTheme.titleLarge?.copyWith(
                    fontWeight: FontWeight.bold, color: primaryColor),
              );
            }),
          ),
          8.toHeight,
          AspectRatio(
            aspectRatio: 1.5,
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: SizedBox(
                child: Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 8),
                  child: BarChart(
                    mainBarData(context),
                    swapAnimationDuration: animDuration,
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  BarChartGroupData makeGroupData(
    int x,
    double y, {
    bool isTouched = false,
    required Color barColor,
    double width = 22,
    List<int> showTooltips = const [],
  }) {
    return BarChartGroupData(
      x: x,
      barRods: [
        BarChartRodData(
          toY: y,
          color: barColor,
          width: 8,
          borderSide: isTouched
              ? BorderSide(color: barColor)
              : const BorderSide(color: Colors.white, width: 0),
        ),
      ],
      showingTooltipIndicators: showTooltips,
    );
  }

  List<BarChartGroupData> showingGroups() => List.generate(7, (i) {
        return makeGroupData(xyValues[i][0].toInt(), xyValues[i][1].toDouble(),
            isTouched: i == touchedIndex, barColor: primaryColor);
      });

  BarChartData mainBarData(BuildContext context) {
    return BarChartData(
        barTouchData: BarTouchData(
          touchTooltipData: BarTouchTooltipData(
            tooltipHorizontalAlignment: FLHorizontalAlignment.right,
            tooltipMargin: -5,
            getTooltipItem: (group, groupIndex, rod, rodIndex) {
              String? month;
              for (var i = 0; i < 7; i++) {
                if (i == group.x) {
                  month = months[i];
                  break;
                }
              }
              return BarTooltipItem(
                '$month\n',
                context.titleSmall!
                    .copyWith(color: context.color.accentContrastColor),
                children: <TextSpan>[
                  TextSpan(
                    text: (rod.toY - 1).toString(),
                    style: context.bodySmall!
                        .copyWith(color: context.color.accentContrastColor),
                  ),
                ],
              );
            },
          ),
          touchCallback: (FlTouchEvent event, barTouchResponse) {},
        ),
        titlesData: FlTitlesData(
          show: true,
          rightTitles: const AxisTitles(
            sideTitles: SideTitles(showTitles: false),
          ),
          topTitles: const AxisTitles(
            sideTitles: SideTitles(showTitles: false),
          ),
          bottomTitles: AxisTitles(
            sideTitles: SideTitles(
              showTitles: true,
              getTitlesWidget: (double value, TitleMeta meta) {
                return getTitles(value, meta, context);
              },
              reservedSize: 30,
            ),
          ),
          leftTitles: AxisTitles(
            sideTitles: SideTitles(
              showTitles: true,
              reservedSize: 30,
              getTitlesWidget: (double value, TitleMeta meta) {
                return getYTitles(value, meta, context);
              },
            ),
          ),
        ),
        borderData: FlBorderData(
          show: false,
        ),
        barGroups: showingGroups(),
        gridData: const FlGridData(show: false),
        maxY: (yValues.length - 1).toDouble());
  }

  Widget getTitles(double value, TitleMeta meta, BuildContext context) {
    final style = context.bodySmall?.bold.copyWith(
      color: context.color.tertiaryContrastColo,
    );
    Widget? text;
    for (var i = 0; i < 7; i++) {
      if (i == value) {
        text = Text(months[i].substring(0, 3), style: style);
        break;
      }
    }
    return Transform.translate(
      offset: const Offset(-10, 0),
      child: RotationTransition(
        turns: const AlwaysStoppedAnimation(315 / 360),
        child: SideTitleWidget(
          axisSide: meta.axisSide,
          space: 12,
          child: text ?? const SizedBox(),
        ),
      ),
    );
  }

  Widget getYTitles(double value, TitleMeta meta, BuildContext context) {
    const style = TextStyle(
      fontWeight: FontWeight.bold,
      fontSize: 12,
    );
    Widget? text;
    for (var i = 0; i < yValues.length; i++) {
      if (i == value) {
        text = Text(yValues[i].toString(), style: style);
        break;
      }
    }
    return text ?? const SizedBox();
  }
}
