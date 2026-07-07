import 'package:flutter/material.dart';
import 'package:prohand/helper/extension/context_extension.dart';

import '../../views/home_view/home_view.dart';
import '../../views/landing_view/landing_view.dart';

class OnboardingWizardViewModel extends ChangeNotifier {
  final PageController pageController = PageController();
  int _currentIndex = 0;
  int get currentIndex => _currentIndex;

  final int totalSteps = 4;

  void onPageChanged(int index) {
    _currentIndex = index;
    notifyListeners();
  }

  void nextStep(BuildContext context) {
    if (_currentIndex < totalSteps - 1) {
      pageController.nextPage(
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeInOut,
      );
    } else {
      _finishOnboarding(context);
    }
  }

  void previousStep() {
    if (_currentIndex > 0) {
      pageController.previousPage(
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeInOut,
      );
    }
  }

  void skipSetup(BuildContext context) {
    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(builder: (context) => const LandingView()),
      (route) => false,
    );
  }

  void completeSetup(BuildContext context) {
    // Navigate to landing view
    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(builder: (context) => const LandingView()),
      (route) => false,
    );
  }

  void _finishOnboarding(BuildContext context) {
    completeSetup(context);
  }

  @override
  void dispose() {
    pageController.dispose();
    super.dispose();
  }
}
