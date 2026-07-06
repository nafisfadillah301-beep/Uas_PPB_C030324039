import 'package:flutter/material.dart';
import 'login_page.dart'; // Mengimport halaman login yang baru dibuat

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Aplikasi Pendaftaran Ekskul Olahraga',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.blue),
        useMaterial3: true,
      ),
      // Halaman pertama yang akan muncul saat aplikasi dibuka
      home: const LoginPage(), 
    );
  }
}