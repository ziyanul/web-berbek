# Business Rules - Portal Yield
## Dokumen ini menjelaskan aturan bisnis yang berlaku pada seluruh proses di Portal Yield.
## Planning Produksi
- Planning Produksi harus memiliki Varian.
- Planning Produksi harus memiliki tanggal produksi.
- Planning Produksi merupakan rencana produksi dan belum menghasilkan transaksi produksi.
- Satu Planning Produksi dapat menghasilkan satu atau lebih Batch Produksi.
---
## Counter Filler
- Counter Filler merupakan awal dari transaksi produksi.
- Counter Filler hanya dapat dibuat berdasarkan Planning Produksi.
- Counter Filler dilakukan melalui proses Pergantian Batch.
- Operator menginput nilai counter setiap mesin filler yang digunakan.
- Satu proses Pergantian Batch menghasilkan satu Batch Produksi.
- Satu proses Pergantian Batch dapat menghasilkan beberapa data Counter sesuai jumlah mesin yang digunakan.
- Saat Counter Filler disimpan, sistem secara otomatis:
  - Membuat Batch Produksi (`tbatch`).
  - Menyimpan Counter setiap mesin (`tcounter`).
  - Menghubungkan Batch Produksi dengan Planning Produksi.
  - Menghitung Total Counter dari seluruh mesin.
  - Menyimpan hasil perhitungan ke `tbatch.total_counter`.
- Nilai `tbatch.total_counter` tidak diinput oleh pengguna, tetapi dihitung otomatis oleh sistem.
---
## Batch Produksi
- Batch Produksi dibuat secara otomatis saat proses Counter Filler disimpan.
- Batch Produksi tidak dapat dibuat secara manual.
- Batch Produksi menjadi identitas utama seluruh transaksi produksi.
- Seluruh transaksi produksi setelah Counter Filler wajib memiliki referensi Batch Produksi.
---
## MP Usage
- MP Usage hanya dapat dibuat apabila Batch Produksi telah terbentuk.
- Penggunaan bahan baku dihitung berdasarkan Formula yang digunakan.
- Rework dapat digunakan sebagai bahan baku sesuai Formula yang berlaku.
- Perubahan MP Usage akan memperbarui total penggunaan bahan baku pada Batch Produksi.
---
## Filkar
- Filkar hanya dapat dibuat berdasarkan Batch Produksi.
- Filkar mencatat hasil produksi setelah proses filler.
- Data Filkar menjadi dasar proses Sortasi.
- Perubahan data Filkar akan memperbarui data ringkasan produksi pada Batch Produksi.
---
## Sortasi
- Sortasi hanya dapat dibuat berdasarkan Batch Produksi.
- Sortasi dilakukan berdasarkan hasil Filkar.
- Satu Batch Produksi dapat memiliki lebih dari satu kategori hasil Sortasi.
- Perubahan data Sortasi akan memperbarui data ringkasan produksi dan Bad Product pada Batch Produksi.
---
## Release
- Release merupakan proses akhir pada Portal Yield.
- Produk hanya dapat dinyatakan Release setelah proses Sortasi selesai.
- Produk yang telah Release menjadi tanggung jawab Portal Mansys.
- Portal Yield tidak mengelola aktivitas setelah Release.
---
## Dashboard Yield
- Dashboard Yield hanya digunakan untuk monitoring.
- Dashboard Yield tidak boleh mengubah data transaksi produksi.
- Seluruh informasi pada Dashboard Yield dihitung berdasarkan data transaksi yang telah tersimpan.
