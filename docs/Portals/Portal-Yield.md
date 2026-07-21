# Portal Yield
## Deskripsi
Portal Yield merupakan pusat operasional produksi pada PROD.IO.
Portal ini mengelola seluruh aktivitas produksi mulai dari Perencanaan Produksi hingga produk dinyatakan **Release**. Seluruh transaksi produksi dilakukan berdasarkan **Batch Produksi** yang terbentuk saat proses produksi dimulai.
Portal Yield menjadi sumber utama informasi produksi, penggunaan bahan baku, hasil produksi, serta perhitungan Yield perusahaan.
---
# Tujuan
Portal Yield dikembangkan untuk:
- Mengelola seluruh aktivitas produksi secara terintegrasi.
- Mencatat seluruh transaksi produksi berdasarkan Batch Produksi.
- Menghitung Yield Produksi secara akurat.
- Menyediakan informasi produksi secara real-time.
- Menjadi sumber data utama bagi Portal Mansys.
---
# Business Process
Alur proses pada Portal Yield adalah sebagai berikut:
1. Planning Produksi
2. Counter Filler
3. Batch Produksi
4. MP Usage
5. Filkar
6. Sortasi
7. Release
---
# Penjelasan Proses
## Planning Produksi
Planning Produksi merupakan rencana produksi yang akan dijalankan.
Planning berisi informasi produk yang akan diproduksi, target produksi, jadwal produksi, serta informasi lain yang dibutuhkan sebelum proses produksi dimulai.
Planning Produksi belum menghasilkan transaksi produksi.
---
## Counter Filler
Counter Filler merupakan awal dari proses produksi aktual.
Operator memilih Planning Produksi yang akan dijalankan kemudian melakukan proses **Pergantian Batch** dengan menginput nilai counter dari setiap mesin filler yang digunakan.
Saat proses disimpan, sistem secara otomatis akan:
- Membuat Batch Produksi.
- Menyimpan data Counter setiap mesin.
- Menghubungkan Batch Produksi dengan Planning Produksi.
- Menghitung Total Counter dari seluruh mesin yang diinput.
- Menyimpan Total Counter ke dalam Batch Produksi.
  Counter Filler menjadi titik awal seluruh transaksi produksi pada Portal Yield.
---
## Batch Produksi
Batch Produksi terbentuk secara otomatis saat proses Counter Filler disimpan.
Batch Produksi menjadi identitas utama seluruh transaksi produksi berikutnya.
Seluruh proses MP Usage, Filkar, Sortasi hingga Release selalu mengacu pada Batch Produksi.
Batch Produksi tidak dapat dibuat secara manual oleh pengguna.
---
## MP Usage
MP Usage digunakan untuk mencatat penggunaan bahan baku pada setiap Batch Produksi.
Perhitungan penggunaan bahan baku mengacu pada Formula yang digunakan serta penggunaan Rework apabila diperlukan.
MP Usage menjadi dasar pengurangan stok bahan baku.
---
## Filkar
Filkar digunakan untuk mencatat hasil produksi setelah proses filler selesai.
Data Filkar menjadi dasar proses Sortasi.
---
## Sortasi
Sortasi digunakan untuk mencatat hasil pemisahan produk berdasarkan kategori hasil produksi.
Data Sortasi menjadi dasar penentuan hasil akhir produksi sebelum produk dinyatakan Release.
---
## Release
Release merupakan tahap akhir pada Portal Yield.
Produk yang telah memenuhi persyaratan dinyatakan Release dan siap diproses pada Portal Mansys.
---
# Dashboard Yield
Dashboard Yield merupakan modul monitoring.
Dashboard mengambil data dari seluruh transaksi pada Portal Yield untuk menghasilkan informasi performa produksi dan nilai Yield.
Dashboard tidak melakukan perubahan terhadap data transaksi produksi.
---
# Master Data
Portal Yield menggunakan Master Data berikut:
- Mesin
- Varian
- Formula
- Detail Formula
- Bahan
- Bad Product
- User
  Seluruh Master Data menggunakan tabel yang sama dan dapat digunakan bersama oleh portal lainnya.
---
# Output
Portal Yield menghasilkan data sebagai berikut:
- Batch Produksi
- Counter Produksi
- Penggunaan Bahan Baku
- Hasil Filkar
- Hasil Sortasi
- Data Release
- Informasi Yield Produksi
  Seluruh data tersebut menjadi sumber informasi bagi Dashboard Yield serta menjadi dasar proses pada Portal Mansys.
---
# Ruang Lingkup
Portal Yield mengelola seluruh aktivitas produksi mulai dari Planning Produksi hingga Release.
Aktivitas setelah Release tidak termasuk dalam ruang lingkup Portal Yield dan dikelola melalui Portal Mansys.
