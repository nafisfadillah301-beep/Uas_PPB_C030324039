import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'register_page.dart';
import 'dashboard_page.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _formKey = GlobalKey<FormState>();
  final Dio _dio = Dio();
  
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();

  // URL khusus untuk Google Chrome / Web localhost
  final String _baseUrl = 'http://localhost:8000/api'; 

  void _prosesLogin() async {
    if (_formKey.currentState!.validate()) {
      try {
        Response response = await _dio.post(
          '$_baseUrl/login',
          data: {
            'email': _emailController.text,
            'password': _passwordController.text,
          },
        );

        if (response.statusCode == 200) {
          // 1. Ambil data role dari response user milik Laravel
          String role = response.data['user']['role'].toString().toLowerCase().trim();

          // 2. CEK MULTI-ROLE: Jika dia admin, tolak akses di mobile
          if (role == 'admin') {
            showDialog(
              context: context,
              barrierDismissible: false, // User wajib klik tombol OK untuk menutup
              builder: (BuildContext context) {
                return AlertDialog(
                  title: const Text('Anda adalah Admin', style: TextStyle(fontWeight: FontWeight.bold)),
                  content: const Text('Dashboard admin hanya bisa di web.'),
                  actions: [
                    TextButton(
                      onPressed: () => Navigator.pop(context), // Menutup dialog
                      child: const Text('OK'),
                    ),
                  ],
                );
              },
            );
          } else {
            // 3. JIKA USER BIASA: Berhasil masuk ke halaman dashboard flutter
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('Login Berhasil!')),
            );
            
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(
                builder: (context) => DashboardPage(userData: response.data['user']),
              ),
            );
          }
        }
      } on DioException catch (e) {
        String pesanError = e.response?.data['message'] ?? 'Email atau password salah';
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(pesanError)),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Login Sistem Pendaftaran Ekskul Olahraga')),
      body: Center(
        child: Container(
          constraints: const BoxConstraints(maxWidth: 400), 
          padding: const EdgeInsets.all(16.0),
          child: Card(
            elevation: 4,
            child: Padding(
              padding: const EdgeInsets.all(24.0),
              child: Form(
                key: _formKey,
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Text('Silakan Login', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 20),
                    TextFormField(
                      controller: _emailController,
                      decoration: const InputDecoration(labelText: 'Email Address'),
                      validator: (value) => value!.isEmpty ? 'Email tidak boleh kosong' : null,
                    ),
                    const SizedBox(height: 10),
                    TextFormField(
                      controller: _passwordController,
                      decoration: const InputDecoration(labelText: 'Password'),
                      obscureText: true,
                      validator: (value) => value!.isEmpty ? 'Password tidak boleh kosong' : null,
                    ),
                    const SizedBox(height: 25),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: _prosesLogin,
                        style: ElevatedButton.styleFrom(backgroundColor: Colors.blue),
                        child: const Text('MASUK', style: TextStyle(color: Colors.white)),
                      ),
                    ),
                    const SizedBox(height: 15),
                    TextButton(
                      onPressed: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (context) => const RegisterPage()),
                        );
                      },
                      child: const Text('Belum punya akun? Daftar di sini'),
                    )
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}