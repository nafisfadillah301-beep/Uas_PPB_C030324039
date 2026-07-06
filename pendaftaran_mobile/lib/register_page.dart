import 'package:flutter/material.dart';
import 'package:dio/dio.dart';

class RegisterPage extends StatefulWidget {
  const RegisterPage({super.key});

  @override
  State<RegisterPage> createState() => _RegisterPageState();
}

class _RegisterPageState extends State<RegisterPage> {
  final _formKey = GlobalKey<FormState>();
  final Dio _dio = Dio();

  final TextEditingController _namaController = TextEditingController();
  final TextEditingController _komentarController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();

  String _jenisKelamin = 'Pria';
  String? _agamaTerpilih;
  
  final Map<String, bool> _hobiList = {
    'Sepakbola': false,
    'Bola Voli': false,
    'Badminthon': false,
  };

  // URL khusus untuk Google Chrome / Web localhost
  final String _baseUrl = 'http://localhost:8000/api'; 

  void _prosesRegister() async {
    if (_formKey.currentState!.validate()) {
      if (_agamaTerpilih == null) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Silakan pilih agama terlebih dahulu')),
        );
        return;
      }

      List<String> hobiTerpilih = [];
      _hobiList.forEach((key, value) {
        if (value == true) hobiTerpilih.add(key);
      });

      try {
        Response response = await _dio.post(
          '$_baseUrl/register',
          data: {
            'nama_lengkap': _namaController.text,
            'jenis_kelamin': _jenisKelamin,
            'agama': _agamaTerpilih,
            'hobi': hobiTerpilih,
            'komentar ': _komentarController.text,
            'email': _emailController.text,
            'password': _passwordController.text,
          },
        );

        if (response.statusCode == 200 || response.statusCode == 201) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Registrasi Berhasil! Silakan Login.')),
          );
          Navigator.pop(context); 
        }
      } on DioException catch (e) {
        String pesanError = e.response?.data['message'] ?? 'Terjadi kesalahan sistem';
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal: $pesanError')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Formulir Pendaftaran Ekskul Olahraga')),
      body: Center(
        child: Container(
          constraints: const BoxConstraints(maxWidth: 500), 
          padding: const EdgeInsets.all(24.0),
          child: Card(
            elevation: 4,
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(16.0),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('FORMULIR PENDAFTARAN EKSKUL OLAHRAGA', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const Divider(),
                    TextFormField(
                      controller: _namaController,
                      decoration: const InputDecoration(labelText: 'Nama Lengkap :'),
                      validator: (value) => value!.isEmpty ? 'Nama tidak boleh kosong' : null,
                    ),
                    const SizedBox(height: 15),
                    
                    const Text('Jenis Kelamin :', style: TextStyle(fontWeight: FontWeight.bold)),
                    Row(
                      children: [
                        Radio(
                          value: 'Pria',
                          groupValue: _jenisKelamin,
                          onChanged: (val) => setState(() => _jenisKelamin = val.toString()),
                        ),
                        const Text('Pria'),
                        const SizedBox(width: 20),
                        Radio(
                          value: 'Wanita',
                          groupValue: _jenisKelamin,
                          onChanged: (val) => setState(() => _jenisKelamin = val.toString()),
                        ),
                        const Text('Wanita'),
                      ],
                    ),
                    const SizedBox(height: 15),

                    DropdownButtonFormField<String>(
                      decoration: const InputDecoration(labelText: 'Agama :'),
                      value: _agamaTerpilih,
                      items: ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha']
                          .map((e) => DropdownMenuItem(value: e, child: Text(e)))
                          .toList(),
                      onChanged: (val) => setState(() => _agamaTerpilih = val),
                    ),
                    const SizedBox(height: 15),

                    const Text('Hobi :', style: TextStyle(fontWeight: FontWeight.bold)),
                    Column(
                      children: _hobiList.keys.map((String key) {
                        return CheckboxListTile(
                          title: Text(key),
                          value: _hobiList[key],
                          controlAffinity: ListTileControlAffinity.leading,
                          onChanged: (bool? value) {
                            setState(() {
                              _hobiList[key] = value!;
                            });
                          },
                        );
                      }).toList(),
                    ),
                    
                    TextFormField(
                      controller: _komentarController,
                      decoration: const InputDecoration(labelText: 'Komentar (masukan nomor telpon dan kelas) :'),
                      maxLines: 3,
                    ),
                    TextFormField(
                      controller: _emailController,
                      decoration: const InputDecoration(labelText: 'Email Login :'),
                      keyboardType: TextInputType.emailAddress,
                      validator: (value) => value!.isEmpty ? 'Email wajib diisi' : null,
                    ),
                    TextFormField(
                      controller: _passwordController,
                      decoration: const InputDecoration(labelText: 'Password :'),
                      obscureText: true,
                      validator: (value) => value!.length < 6 ? 'Password minimal 6 karakter' : null,
                    ),
                    const SizedBox(height: 30),
                    
                    Row(
                      mainAxisAlignment: MainAxisAlignment.end,
                      children: [
                        ElevatedButton(
                          onPressed: () => Navigator.pop(context),
                          style: ElevatedButton.styleFrom(backgroundColor: Colors.grey[300]),
                          child: const Text('Batal', style: TextStyle(color: Colors.black)),
                        ),
                        const SizedBox(width: 10),
                        ElevatedButton(
                          onPressed: _prosesRegister,
                          style: ElevatedButton.styleFrom(backgroundColor: Colors.blue),
                          child: const Text('Kirim', style: TextStyle(color: Colors.white)),
                        ),
                      ],
                    ),
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