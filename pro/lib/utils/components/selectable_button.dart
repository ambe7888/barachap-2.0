import 'package:flutter/material.dart';

class SelectableButton extends StatelessWidget {
  final String title;
  final ValueNotifier notifier;
  final void Function()? onPressed;
  final bool isSelected;
  const SelectableButton({
    super.key,
    required this.title,
    required this.notifier,
    this.onPressed,
    required this.isSelected,
  });

  @override
  Widget build(BuildContext context) {
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
