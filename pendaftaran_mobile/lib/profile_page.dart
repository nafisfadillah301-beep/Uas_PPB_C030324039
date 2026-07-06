import 'package:flutter/material.dart';

class ProfilePage extends StatelessWidget {
  final Map<String, dynamic> userData; // Menerima lembaran data user

  const ProfilePage({super.key, required this.userData});

  @override
  Widget build(BuildContext context) {
    // Parser pengaman format data Hobi agar tidak crash
    String hobiTeks = '-';
    if (userData['hobi'] != null) {
      if (userData['hobi'] is List) {
        hobiTeks = (userData['hobi'] as List).join(', ');
      } else {
        hobiTeks = userData['hobi'].toString();
      }
    }

    bool isAdmin = userData['role']?.toString().toLowerCase() == 'admin';

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: const Text('Profil Saya'),
        backgroundColor: isAdmin ? Colors.amber.shade700 : Colors.blue,
        foregroundColor: Colors.white,
        elevation: 0,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            // Bagian Banner Atas dan Lingkaran Avatar Profile
            Container(
              width: double.infinity,
              decoration: BoxDecoration(
                color: isAdmin ? Colors.amber.shade700 : Colors.blue,
                borderRadius: const BorderRadius.only(
                  bottomLeft: Radius.circular(32),
                  bottomRight: Radius.circular(32),
                ),
              ),
              padding: const EdgeInsets.only(bottom: 30, top: 10),
              child: Column(
                children: [
                  CircleAvatar(
                    radius: 45,
                    backgroundColor: Colors.white,
                    child: Text(
                      userData['name']?[0].toUpperCase() ?? 'U',
                      style: TextStyle(
                        fontSize: 36,
                        fontWeight: FontWeight.bold,
                        color: isAdmin ? Colors.amber.shade800 : Colors.blue,
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Text(
                    userData['name'] ?? 'User',
                    style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
                  ),
                  const SizedBox(height: 6),
                  Container(
                    // Perbaikan utama: Mengganti 'py: 4' menjadi sintaks EdgeInsects resmi milik Flutter
                    padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 4),
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.2),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      (userData['role'] ?? 'USER').toString().toUpperCase(),
                      style: const TextStyle(fontSize: 11, fontWeight: FontWeight.bold, color: Colors.white, letterSpacing: 1.2),
                    ),
                  ),
                ],
              ),
            ),
            
            // Bagian Detail Informasi Akun
            Padding(
              padding: const EdgeInsets.all(20.0),
              child: Container(
                constraints: const BoxConstraints(maxWidth: 450),
                child: Card(
                  elevation: 2,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                  child: Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Column(
                      children: [
                        _buildProfileItem(Icons.email_outlined, 'Alamat Email', userData['email']),
                        const Divider(height: 24),
                        _buildProfileItem(Icons.wc_outlined, 'Jenis Kelamin', userData['jenis_kelamin'] ?? '-'),
                        const Divider(height: 24),
                        _buildProfileItem(Icons.brightness_5_outlined, 'Agama', userData['agama'] ?? '-'),
                        const Divider(height: 24),
                        _buildProfileItem(Icons.sports_basketball_outlined, 'Hobi Terpilih', hobiTeks),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildProfileItem(IconData icon, String label, String? value) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Icon(icon, color: Colors.grey.shade600, size: 24),
        const SizedBox(width: 14),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(label, style: TextStyle(fontSize: 12, color: Colors.grey.shade500, fontWeight: FontWeight.w500)),
              const SizedBox(height: 2),
              Text(
                value ?? '-',
                style: const TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Colors.black87),
              ),
            ],
          ),
        ),
      ],
    );
  }
}