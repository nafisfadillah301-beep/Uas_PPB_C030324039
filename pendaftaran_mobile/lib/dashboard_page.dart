import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'login_page.dart'; 
import 'profile_page.dart'; // 👈 Memastikan file profil di-import agar bisa dipanggil

class DashboardPage extends StatefulWidget {
  final Map<String, dynamic> userData; // Menerima lembaran data user dari LoginPage

  const DashboardPage({super.key, required this.userData});

  @override
  State<DashboardPage> createState() => _DashboardPageState();
}

class _DashboardPageState extends State<DashboardPage> {
  final Dio _dio = Dio();
  bool _isLoading = false;
  
  // Sesuaikan URL ini dengan milik server lokal kamu
  final String _baseUrl = 'http://localhost:8000/api'; 

  // Fungsi memproses penghapusan akun ke Laravel API
  void _prosesHapusAkun() async {
    setState(() {
      _isLoading = true; 
    });

    try {
      Response response = await _dio.delete(
        '$_baseUrl/profile/delete',
        data: {
          'id': widget.userData['id'], 
        },
      );

      if (response.statusCode == 200) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Akun Anda berhasil dihapus secara permanen.')),
        );

        Navigator.pushAndRemoveUntil(
          context,
          MaterialPageRoute(builder: (context) => const LoginPage()),
          (route) => false,
        );
      }
    } on DioException catch (e) {
      String pesanError = e.response?.data['message'] ?? 'Gagal menghapus akun pendaftaran';
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(pesanError)),
      );
    } finally { 
      setState(() {
        _isLoading = false; 
      });
    }
  }

  // Pop-up dialog peringatan sebelum eksekusi hapus
  void _tampilkanDialogKonfirmasi() {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('⚠️ Hapus Akun Permanen?'),
          content: const Text(
            'Tindakan ini tidak bisa dibatalkan. Seluruh data pendaftaran ekskul Anda akan terhapus secara permanen dari server.',
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Batal', style: TextStyle(color: Colors.grey)),
            ),
            TextButton(
              onPressed: () {
                Navigator.pop(context); 
                _prosesHapusAkun(); 
              },
              child: const Text('Ya, Hapus', style: TextStyle(color: Colors.red, fontWeight: FontWeight.bold)),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    // Parser string/array hobi agar tidak memicu error data type json_decode
    String hobiTeks = '-';
    if (widget.userData['hobi'] != null) {
      if (widget.userData['hobi'] is List) {
        hobiTeks = (widget.userData['hobi'] as List).join(', ');
      } else {
        hobiTeks = widget.userData['hobi'].toString();
      }
    }

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: const Text('Dashboard Pendaftaran'),
        backgroundColor: Colors.blue,
        foregroundColor: Colors.white,
        actions: [
          // ➕ TOMBOL MENU PROFIL (Sekarang sudah aktif dan bisa diklik)
          IconButton(
            icon: const Icon(Icons.account_circle),
            tooltip: 'Profil Saya',
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => ProfilePage(userData: widget.userData),
                ),
              );
            },
          ),
          IconButton(
            icon: const Icon(Icons.logout),
            tooltip: 'Keluar',
            onPressed: () {
              Navigator.pushAndRemoveUntil(
                context,
                MaterialPageRoute(builder: (context) => const LoginPage()),
                (route) => false,
              );
            },
          )
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator(color: Colors.blue))
          : Center(
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(16.0),
                child: Container(
                  constraints: const BoxConstraints(maxWidth: 450),
                  child: Column(
                    children: [
                      Card(
                        elevation: 4,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                        child: Padding(
                          padding: const EdgeInsets.all(24.0),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Center(
                                child: CircleAvatar(
                                    radius: 35,
                                    backgroundColor: Colors.blue.shade50,
                                    child: Text(
                                      widget.userData['name']?[0].toUpperCase() ?? 'U',
                                      style: const TextStyle(fontSize: 28, fontWeight: FontWeight.bold, color: Colors.blue),
                                    )),
                              ),
                              const SizedBox(height: 15),
                              const Center(
                                child: Text(
                                  'Selamat! Pendaftaran Berhasil',
                                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                                ),
                              ),
                              const Center(
                                child: Text(
                                  'Berikut adalah ringkasan kartu pendaftaran Anda',
                                  style: TextStyle(fontSize: 12, color: Colors.grey),
                                ),
                              ),
                              const Divider(height: 30),
                              _buildDetailRow('Nama Lengkap', widget.userData['name']),
                              _buildDetailRow('Email', widget.userData['email']),
                              _buildDetailRow('Jenis Kelamin', widget.userData['jenis_kelamin']),
                              _buildDetailRow('Agama', widget.userData['agama']),
                              _buildDetailRow('Hobi', hobiTeks),
                              const SizedBox(height: 15),
                              const Text('Komentar / Pesan Anda:', style: TextStyle(color: Colors.grey, fontSize: 13)),
                              const SizedBox(height: 5),
                              Container(
                                width: double.infinity,
                                padding: const EdgeInsets.all(12),
                                decoration: BoxDecoration(
                                  color: Colors.grey.shade50,
                                  borderRadius: BorderRadius.circular(8),
                                  border: Border.all(color: Colors.grey.shade200),
                                ),
                                child: Text(
                                  widget.userData['komentar'] ?? 'Tidak ada komentar.',
                                  style: const TextStyle(fontStyle: FontStyle.italic, color: Colors.black87),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 30),
                      
                      SizedBox(
                        width: double.infinity,
                        height: 48,
                        child: OutlinedButton.icon(
                          icon: const Icon(Icons.delete_forever, color: Colors.red),
                          label: const Text(
                            'Hapus Akun Pendaftaran Saya',
                            style: TextStyle(color: Colors.red, fontWeight: FontWeight.bold),
                          ),
                          style: OutlinedButton.styleFrom(
                            side: BorderSide(color: Colors.red.shade200),
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                            backgroundColor: Colors.red.shade50,
                          ),
                          onPressed: _tampilkanDialogKonfirmasi,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
    );
  }

  Widget _buildDetailRow(String label, String? value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(color: Colors.grey, fontSize: 14)),
          Text(value ?? '-', style: const TextStyle(fontWeight: FontWeight.w600, color: Colors.black87, fontSize: 14)),
        ],
      ),
    );
  }
}