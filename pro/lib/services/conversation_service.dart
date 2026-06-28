import 'dart:io';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:just_audio/just_audio.dart';
import 'package:path/path.dart';
import 'package:prohand/helper/app_urls.dart';
import 'package:prohand/helper/constant_helper.dart';
import 'package:prohand/helper/extension/string_extension.dart';
import 'package:prohand/helper/local_keys.g.dart';

import '../data/network/network_api_services.dart';
import '../models/conversation_model.dart';

class ConversationService with ChangeNotifier {
  ConversationModel? _conversationModel;
  ConversationModel get conversationModel =>
      _conversationModel ?? ConversationModel();

  var token = "";

  var nextPage;

  bool nextPageLoading = false;

  bool nexLoadingFailed = false;

  bool get shouldAutoFetch => _conversationModel == null || token.isInvalid;

  fetchConversationMessages(conversationId) async {
    token = getToken;
    _conversationModel = null;
    var url = "${AppUrls.conversationUrl}/$conversationId";

    final responseData = await NetworkApiServices().getApi(
      url,
      null,
      headers: acceptJsonAuthHeader,
      timeoutSeconds: 30,
    );

    if (responseData != null) {
      final tempData = ConversationModel.fromJson(responseData);
      _conversationModel = tempData;
      nextPage = tempData.pagination?.nextPageUrl;
      notifyListeners();
      return true;
    }
  }

  trySendingMessage(message, File? file, clientId) async {
    final url = AppUrls.messageSendUrl;
    var request = http.MultipartRequest('POST', Uri.parse(url));
    request.fields
        .addAll({"client_id": clientId.toString(), "message": message ?? ""});
    debugPrint({'client_id': clientId.toString(), 'message': message ?? ""}
        .toString());
    if (file != null) {
      request.files.add(await http.MultipartFile.fromPath("file", file.path,
          filename: basename(file.path)));
    }
    request.headers.addAll(acceptJsonAuthHeader);

    final responseData = await NetworkApiServices()
        .postWithFileApi(request, LocalKeys.sendMessage);

    if (responseData != null) {
      _conversationModel?.allMessage?.insert(
          0,
          MessageModel(
            fromUser: "2",
            message: Message(message: message.isEmpty ? null : message),
            file: responseData["message"]?["file"],
            createdAt: DateTime.now(),
          ));
      try {
        final player = AudioPlayer(); // Create a player
        final duration = await player.setAsset(// Load a URL
            'assets/audios/chat1.mp3'); // Schemes: (https: | file: | asset: )
        player.play();
      } catch (e) {}
      notifyListeners();
      return true;
    }
  }

  void addNewMessage(messageReceived) async {
    debugPrint("trying to add new message".toString());
    try {
      final player = AudioPlayer(); // Create a player
      final duration = await player.setAsset(// Load a URL
          'assets/audios/chat.mp3'); // Schemes: (https: | file: | asset: )
      player.play();
    } catch (e) {}
    _conversationModel?.allMessage
        ?.insert(0, MessageModel.fromJson(messageReceived));
    notifyListeners();
  }

  void fetchNextPage() async {
    token = getToken;
    if (nextPageLoading) return;
    nextPageLoading = true;
    notifyListeners();
    final responseData = await NetworkApiServices()
        .getApi(nextPage, LocalKeys.messages, headers: commonAuthHeader);

    if (responseData != null) {
      final tempData = ConversationModel.fromJson(responseData);
      tempData.allMessage?.forEach((element) {
        _conversationModel?.allMessage?.add(element);
      });
      nextPage = tempData.pagination?.nextPageUrl;
    } else {
      nexLoadingFailed = true;
      Future.delayed(const Duration(seconds: 1)).then((value) {
        nexLoadingFailed = false;
        notifyListeners();
      });
    }
    nextPageLoading = false;
    notifyListeners();
  }
}
