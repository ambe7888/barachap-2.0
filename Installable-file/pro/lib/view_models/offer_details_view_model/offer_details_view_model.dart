class OfferDetailsViewModel {
  OfferDetailsViewModel._init();
  static OfferDetailsViewModel? _instance;
  static OfferDetailsViewModel get instance {
    _instance ??= OfferDetailsViewModel._init();
    return _instance!;
  }

  OfferDetailsViewModel._dispose();
  static bool get dispose {
    _instance = null;
    return true;
  }
}
