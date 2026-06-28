import 'dart:convert';

PaymentGatewayModel paymentGatewayModelFromJson(String str) =>
    PaymentGatewayModel.fromJson(json.decode(str));

class PaymentGatewayModel {
  PaymentGatewayModel({
    required this.data,
  });

  List<Gateway>? data;

  factory PaymentGatewayModel.fromJson(json) => PaymentGatewayModel(
        data: json["data"] == null
            ? null
            : List<Gateway>.from(json["data"].map((x) => Gateway.fromJson(x))),
      );
}

class Gateway {
  String? previewLogo;
  String? sandboxClientId;
  String? sandboxClientSecret;
  String? sandboxAppId;
  String? liveAppId;
  String? paymentAction;
  String? currency;
  String? notifyUrl;
  String? locale;
  String? validateSsl;
  String? liveClientId;
  String? clientId;
  String? liveClientSecret;
  String? gateway;
  bool testMode;
  String name;
  String? merchantKey;
  String? merchantMid;
  String? merchantWebsite;
  String? channel;
  String? industryType;
  String? key;
  String? secret;
  String? apiKey;
  String? apiSecret;
  String? publishableKey;
  String? publicKey;
  String? secretKey;
  String? publicKeyTest;
  String? secretKeyTest;
  String? merchantEmail;
  String? merchantId;
  String? serverKey;
  String? clientKey;
  String? environment;
  String? passphrase;
  String? merchantEnv;
  String? itnUrl;
  String? appId;
  String? secretHash;
  String? clientSecret;
  String? username;
  String? password;
  String? region;
  String? profileId;
  String? siteId;
  String? appKey;
  String? collectionName;
  String? xsignature;
  String? locationId;
  String? accessToken;
  String? secrectKey;
  String? categoryCode;
  String? pageId;
  String? entityId;
  String? loginId;
  String? transactionId;
  String? brandId;
  String? apiKeySitesway;
  String? loginIdAuthorizeDotNet;
  String? transactionIdAuthorizeDotNet;
  String? apiKeyIyzipay;
  String? secretKeyIyzipay;
  String? manualPaymentGateway;
  String? manualPaymentTestMode;
  String? manualPaymentGatewayName;
  String? siteManualPaymentDescription;

  String? authToken;

  Gateway({
    this.previewLogo,
    this.sandboxClientId,
    this.sandboxClientSecret,
    this.sandboxAppId,
    this.liveAppId,
    this.paymentAction,
    this.currency,
    this.notifyUrl,
    this.locale,
    this.validateSsl,
    this.liveClientId,
    this.clientId,
    this.liveClientSecret,
    this.gateway,
    required this.testMode,
    required this.name,
    this.merchantKey,
    this.merchantMid,
    this.merchantWebsite,
    this.channel,
    this.industryType,
    this.key,
    this.secret,
    this.apiKey,
    this.apiSecret,
    this.publishableKey,
    this.publicKey,
    this.secretKey,
    this.publicKeyTest,
    this.secretKeyTest,
    this.merchantEmail,
    this.merchantId,
    this.serverKey,
    this.clientKey,
    this.environment,
    this.passphrase,
    this.merchantEnv,
    this.itnUrl,
    this.appId,
    this.secretHash,
    this.clientSecret,
    this.username,
    this.password,
    this.region,
    this.profileId,
    this.siteId,
    this.appKey,
    this.collectionName,
    this.xsignature,
    this.locationId,
    this.accessToken,
    this.secrectKey,
    this.categoryCode,
    this.pageId,
    this.authToken,
    this.entityId,
    this.loginId,
    this.transactionId,
    this.brandId,
    this.apiKeySitesway,
    this.loginIdAuthorizeDotNet,
    this.transactionIdAuthorizeDotNet,
    this.apiKeyIyzipay,
    this.secretKeyIyzipay,
    this.manualPaymentGateway,
    this.manualPaymentTestMode,
    this.manualPaymentGatewayName,
    this.siteManualPaymentDescription,
  });

  factory Gateway.fromJson(Map<String, dynamic> json) {
    return Gateway(
      previewLogo: json['preview_logo'],
      sandboxClientId: json['sandbox_client_id'],
      sandboxClientSecret: json['sandbox_client_secret'],
      sandboxAppId: json['sandbox_app_id'],
      liveAppId: json['live_app_id'],
      paymentAction: json['payment_action'],
      currency: json['currency'],
      notifyUrl: json['notify_url'],
      locale: json['locale'],
      validateSsl: json['validate_ssl'],
      liveClientId: json['live_client_id'],
      clientId: json['client_id'],
      liveClientSecret: json['live_client_secret'],
      gateway: json['gateway'],
      testMode: json['test_mode'].toString() == "on",
      name: json['name']?.toString() ?? "",
      merchantKey: json['merchant_key'],
      merchantMid: json['merchant_mid'],
      merchantWebsite: json['merchant_website'],
      channel: json['channel'],
      industryType: json['industry_type'],
      key: json['key'],
      secret: json['secret'],
      apiKey: json['api_key'],
      apiSecret: json['api_secret'],
      publishableKey: json['publishable_key'],
      publicKey: json['_public_key'] ?? json['public_key'],
      secretKey: json['secret_key'],
      publicKeyTest: json['_public_key'],
      secretKeyTest: json['_secret_key'],
      merchantEmail: json['merchant_email'],
      merchantId: json['merchant_id'],
      serverKey: json['server_key'],
      clientKey: json['client_key'],
      environment: json['environment'],
      passphrase: json['passphrase'],
      authToken: json['auth_token'],
      merchantEnv: json['merchant_env'],
      itnUrl: json['itn_url'],
      appId: json['app_id'],
      secretHash: json['secret_hash'],
      clientSecret: json['client_secret'],
      username: json['username'],
      password: json['password'],
      region: json['region'],
      profileId: json['profile_id'],
      siteId: json['site_id'],
      appKey: json['app_key'],
      collectionName: json['collection_name'],
      xsignature: json['xsignature'],
      locationId: json['location_id'],
      accessToken: json['access_token'],
      secrectKey: json['secrect_key'],
      categoryCode: json['category_code'],
      pageId: json['page_id'],
      entityId: json['entity_id'],
      loginId: json['login_id'],
      transactionId: json['transaction_id'],
      brandId: json['brand_id'],
      apiKeySitesway: json['api_key_sitesway'],
      loginIdAuthorizeDotNet: json['login_id_authorize_dot_net'],
      transactionIdAuthorizeDotNet: json['transaction_id_authorize_dot_net'],
      apiKeyIyzipay: json['api_key_iyzipay'],
      secretKeyIyzipay: json['secret_key_iyzipay'],
      manualPaymentGateway: json['manual_payment_gateway'],
      manualPaymentTestMode: json['manual_payment_test_mode'],
      manualPaymentGatewayName: json['manual_payment_gateway_name'],
      siteManualPaymentDescription: json['site_manual_payment_description'],
    );
  }
}
