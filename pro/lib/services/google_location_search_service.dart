import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:http/http.dart' as http;

import '../customization.dart';
import '../models/google_place_details_model.dart';
import '../models/google_places_model.dart';

class GoogleLocationSearch with ChangeNotifier {
  List<Prediction> locations = [];
  bool isLoading = false;
  Prediction? geoLoc;
  String? dark;

  setIsLoading(value) {
    if (value == isLoading) {
      return;
    }
    isLoading = value;
    notifyListeners();
  }

  resetLocations() {
    locations = [];
  }

  fetchLocations({location, region}) async {
    try {
      locations = [];

      debugPrint(
          "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=$location&language=en&region=$region&key=$mapApiKey"
              .toString());
      var headers = {'Accept': 'application/json'};
      var request = http.Request(
          'GET',
          Uri.parse(
              'https://maps.googleapis.com/maps/api/place/autocomplete/json?input=$location&language=en&region=$region&key=$mapApiKey'));

      request.headers.addAll(headers);

      http.StreamedResponse response = await request.send();

      var responseString = await response.stream.bytesToString();
      print(responseString);
      if (response.statusCode == 200) {
        GooglePlacesModel responseData =
            googlePlacesModelFromJson(responseString);
        locations = responseData.predictions ?? [];
        print("location length is ${locations.length}");
      } else {
        print(response.reasonPhrase);
      }
    } catch (e) {
      debugPrint(e.toString());
    } finally {}
    notifyListeners();
  }

  fetchGEOLocations({lat, lng}) async {
    try {
      locations = [];
      setIsLoading(true);
      debugPrint(
          'https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat, $lng&key=$mapApiKey'
              .toString());
      var headers = {'Accept': 'application/json'};
      var request = http.Request(
          'GET',
          Uri.parse(
              'https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat, $lng&key=$mapApiKey'));

      request.headers.addAll(headers);

      http.StreamedResponse response = await request.send();

      var responseString = await response.stream.bytesToString();
      if (response.statusCode == 200) {
        var responseData = jsonDecode(responseString);
        debugPrint(responseData.toString());
        bool breakLoop = false;
        var postCode;
        var city;
        for (var i = 0; i < responseData['results'].length; i++) {
          var element = responseData["results"][i];
          element["address_components"].forEach((e) {
            if (e["types"].contains("postal_code")) {
              breakLoop = true;
              postCode = e["long_name"];
            }
            if (e["types"].contains("sublocality")) {
              breakLoop = true;
              city = e["long_name"];
            }
          });
          if (breakLoop) break;
        }
        geoLoc = Prediction(
          description: responseData["results"]?[0]?['formatted_address'],
          postCode: postCode,
          city: city,
          lat: lat,
          lng: lng,
        );
      } else {
        print(response.reasonPhrase);
      }
    } finally {
      setIsLoading(false);
    }
  }

  Future<GooglePlaceDetailsModel?> fetchPlaceDetails(id) async {
    try {
      debugPrint("fetching place details $id".toString());
      locations = [];
      var headers = {'Accept': 'application/json'};

      var response = await http.get(
        Uri.parse(
            'https://maps.googleapis.com/maps/api/place/details/json?place_id=$id&key=$mapApiKey'),
        headers: headers,
      );

      debugPrint(
          'https://maps.googleapis.com/maps/api/place/details/json?place_id=$id&key=$mapApiKey'
              .toString());
      if (response.statusCode == 200) {
        GooglePlaceDetailsModel responseData =
            GooglePlaceDetailsModel.fromJson(jsonDecode(response.body));
        setIsLoading(false);
        geoLoc = Prediction(
          description: responseData.result?.formattedAddress,
          lat: responseData.result?.geometry?.location?.lat,
          lng: responseData.result?.geometry?.location?.lng,
        );
        debugPrint(responseData.toString());
        return responseData;
      } else {
        print(response.reasonPhrase);
      }
    } finally {
      setIsLoading(false);
    }
    error(e) {
      debugPrint(e.toString());
    }

    return null;
  }

  setDark() async {
    dark = await rootBundle.loadString("assets/files/dark-map.json");
  }

  void setFromCurrentLoc(LatLng? value) {
    if (value == null) return;
    geoLoc = Prediction(lat: value.latitude, lng: value.longitude);
  }
}
