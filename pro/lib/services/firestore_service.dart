import 'package:cloud_firestore/cloud_firestore.dart';
import 'package:flutter/material.dart';

class FirestoreService {
  final FirebaseFirestore _db = FirebaseFirestore.instance;

  FirestoreService._init();
  static FirestoreService? _instance;
  static FirestoreService get instance {
    _instance ??= FirestoreService._init();
    return _instance!;
  }

  // Get collection reference
  CollectionReference getCollection(String collectionName) {
    return _db.collection(collectionName);
  }

  // Save document
  Future<void> saveDocument(String collectionName, String docId, Map<String, dynamic> data) async {
    try {
      await _db.collection(collectionName).doc(docId).set(data, SetOptions(merge: true));
    } catch (e) {
      debugPrint("Firestore saveDocument error: $e");
    }
  }

  // Get document stream
  Stream<DocumentSnapshot> getDocumentStream(String collectionName, String docId) {
    return _db.collection(collectionName).doc(docId).snapshots();
  }

  // Get collection stream
  Stream<QuerySnapshot> getCollectionStream(String collectionName) {
    return _db.collection(collectionName).snapshots();
  }

  // Delete document
  Future<void> deleteDocument(String collectionName, String docId) async {
    try {
      await _db.collection(collectionName).doc(docId).delete();
    } catch (e) {
      debugPrint("Firestore deleteDocument error: $e");
    }
  }
}
