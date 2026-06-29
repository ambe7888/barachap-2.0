import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/custom_button.dart';
import 'package:prohandy_client/views/sign_in_view/sign_in_view.dart';
import 'package:prohandy_client/views/sign_up_view/sign_up_view.dart';
import 'package:prohandy_client/view_models/landding_view_model/landding_view_model.dart';
import '../../customizations/colors.dart';

class WelcomeOptionView extends StatelessWidget {
  static const routeName = "welcome_option_view";
  const WelcomeOptionView({super.key});

  @override
  Widget build(BuildContext context) {
    final bool isDark = context.isDark;

    return Scaffold(
      backgroundColor: context.color.backgroundColor,
      body: Stack(
        children: [
          // Background Gradient Circles for Depth / Glassmorphic look
          Positioned(
            top: -100,
            right: -100,
            child: Container(
              width: 300,
              height: 300,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: primaryColor.withOpacity(isDark ? 0.08 : 0.15),
              ),
            ).animate().fade(duration: 800.milliseconds).scale(duration: 800.milliseconds),
          ),
          Positioned(
            bottom: -50,
            left: -100,
            child: Container(
              width: 250,
              height: 250,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: primaryColor.withOpacity(isDark ? 0.05 : 0.1),
              ),
            ).animate().fade(duration: 1000.milliseconds).scale(duration: 1000.milliseconds),
          ),

          // Main Content
          SafeArea(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Spacer(),

                  Container(
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      boxShadow: [
                        BoxShadow(
                          color: primaryColor.withOpacity(0.2),
                          blurRadius: 30,
                          spreadRadius: 2,
                          offset: const Offset(0, 10),
                        ),
                      ],
                    ),
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(100),
                      child: Image.asset(
                        "assets/images/logo.png",
                        height: 140,
                        width: 140,
                        fit: BoxFit.contain,
                      ),
                    ),
                  )
                      .animate()
                      .fade(duration: 600.milliseconds)
                      .scale(duration: 600.milliseconds, curve: Curves.easeOutBack),

                  40.toHeight,

                  // App Title & Subtitle
                  Text(
                    "BaraChap",
                    style: context.titleLarge?.copyWith(
                      fontSize: 32,
                      fontWeight: FontWeight.w800,
                      color: context.color.primaryContrastColor,
                      letterSpacing: 0.5,
                    ),
                  ).animate().fade(delay: 200.milliseconds, duration: 500.milliseconds).slideY(begin: 0.2, end: 0),

                  12.toHeight,

                  Text(
                    "Votre partenaire de confiance pour tous vos besoins de bricolage, réparations et services à domicile.",
                    textAlign: TextAlign.center,
                    style: context.titleSmall?.copyWith(
                      fontSize: 15,
                      height: 1.5,
                      color: context.color.secondaryContrastColor,
                    ),
                  ).animate().fade(delay: 400.milliseconds, duration: 500.milliseconds).slideY(begin: 0.2, end: 0),

                  const Spacer(),

                  // Buttons Section
                  Column(
                    children: [
                      // Log In Button
                      CustomButton(
                        height: 52,
                        btText: LocalKeys.signIn,
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(builder: (context) => const SignInView()),
                          );
                        },
                      ).animate().fade(delay: 600.milliseconds, duration: 500.milliseconds).slideY(begin: 0.3, end: 0),

                      16.toHeight,

                      // Sign Up Button (Outlined with brand accent)
                      SizedBox(
                        height: 52,
                        width: double.infinity,
                        child: OutlinedButton(
                          onPressed: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(builder: (context) => const SignUpView()),
                            );
                          },
                          style: OutlinedButton.styleFrom(
                            side: BorderSide(color: primaryColor, width: 1.8),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(10),
                            ),
                          ),
                          child: Text(
                            LocalKeys.signUp,
                            style: context.titleMedium?.copyWith(
                              color: primaryColor,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ),
                      ).animate().fade(delay: 800.milliseconds, duration: 500.milliseconds).slideY(begin: 0.3, end: 0),

                      24.toHeight,

                      // Skip / Guest Button
                      TextButton(
                        onPressed: () {
                          LandingViewModel.instance.navigateToLanding(context);
                        },
                        style: TextButton.styleFrom(
                          foregroundColor: context.color.secondaryContrastColor,
                          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Text(
                              "Continuer sans se connecter",
                              style: context.titleSmall?.copyWith(
                                fontWeight: FontWeight.w600,
                                decoration: TextDecoration.underline,
                              ),
                            ),
                            const Icon(Icons.arrow_forward_rounded, size: 16),
                          ],
                        ),
                      ).animate().fade(delay: 1000.milliseconds, duration: 500.milliseconds),
                    ],
                  ),

                  20.toHeight,
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
