import 'package:flutter/material.dart';
import 'package:prohandy_client/helper/extension/context_extension.dart';
import 'package:prohandy_client/helper/extension/int_extension.dart';
import 'package:prohandy_client/helper/local_keys.g.dart';
import 'package:prohandy_client/utils/components/custom_button.dart';
import 'package:prohandy_client/utils/components/field_with_label.dart';
import 'package:prohandy_client/utils/components/navigation_pop_icon.dart';
import 'package:prohandy_client/view_models/sign_in_with_otp_view_model/sign_in_with_otp_view_model.dart';

class SignInWithOtpView extends StatelessWidget {
  const SignInWithOtpView({super.key});

  @override
  Widget build(BuildContext context) {
    final sio = SignInWithOtpViewModel.instance;
    return Scaffold(
      backgroundColor: context.color.accentContrastColor,
      appBar: AppBar(
        leading: const NavigationPopIcon(),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        child: Form(
          key: sio.formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                LocalKeys.otpSignIn,
                style: context.titleLarge?.bold,
              ),
              32.toHeight,
              FieldWithLabel(
                label: LocalKeys.phone,
                hintText: "+8801938000000",
                isRequired: true,
                keyboardType: TextInputType.number,
                controller: sio.phoneController,
                validator: (value) {
                  if (value.toString().length < 5) {
                    return LocalKeys.enterAValidPhoneNumber;
                  }
                  return null;
                },
              ),
              12.toHeight,
              ValueListenableBuilder(
                valueListenable: sio.isLoading,
                builder: (context, value, child) => CustomButton(
                  onPressed: () {
                    sio.trySignIn(context);
                  },
                  btText: LocalKeys.continueO,
                  isLoading: value,
                ),
              )
            ],
          ),
        ),
      ),
    );
  }
}
