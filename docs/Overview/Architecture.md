# Architecture
## Arsitektur Sistem
PROD.IO merupakan sistem operasional manufaktur yang dibangun menggunakan arsitektur modular. Setiap modul dikelompokkan ke dalam sebuah portal berdasarkan fungsi bisnisnya, namun seluruh portal menggunakan basis data yang saling terintegrasi.
Pendekatan ini memungkinkan setiap departemen bekerja pada portal yang berbeda tanpa menyebabkan duplikasi data maupun perbedaan informasi.
---
# Portal Sistem
Saat ini PROD.IO terdiri dari empat portal utama.
## Portal Yield
Portal Yield merupakan inti proses produksi.
Portal ini mengelola seluruh aktivitas produksi mulai dari perencanaan produksi hingga produk dinyatakan release.
Modul Portal Yield:
- Dashboard Yield
- Planning Produksi
- MP Usage
- Counter Filler
- Filkar
- Sortasi
---
## Portal Paperless
Portal Paperless digunakan untuk mendigitalisasi seluruh formulir operasional perusahaan.
Formulir tidak dikelompokkan berdasarkan nama dokumen, tetapi berdasarkan area proses sehingga setiap area dapat memiliki beberapa formulir dalam satu modul.
---
## Portal Mansys
Portal Mansys merupakan portal yang menghubungkan aktivitas produksi dengan proses setelah produk selesai diproduksi.
Portal ini mengelola aktivitas setelah produk dinyatakan release sampai produk siap didistribusikan.
---
## Portal Maintenance
Portal Maintenance digunakan oleh Departemen Engineering untuk mengelola seluruh aktivitas yang berkaitan dengan mesin produksi.
Modul pada portal ini meliputi monitoring mesin, sparepart, preventive maintenance, corrective maintenance, repair, serta monitoring umur pakai komponen.
---
# Hubungan Antar Portal
## Setiap portal memiliki tanggung jawab yang berbeda namun saling terhubung.
Alur operasional secara umum adalah sebagai berikut.
Planning Produksi
↓
Portal Yield
↓
Release Produk
↓
Portal Mansys
↓
Distribusi Produk
## Portal Maintenance berjalan secara paralel untuk memastikan seluruh mesin produksi selalu berada dalam kondisi optimal.
## Portal Paperless mendukung seluruh portal dengan menyediakan formulir digital sesuai kebutuhan setiap area proses.
---
# Master Data
Master Data tidak dipisahkan menjadi portal tersendiri.
Setiap portal hanya menampilkan Master Data yang berhubungan dengan aktivitas pada portal tersebut.
Seluruh Master Data tetap menggunakan tabel yang sama sehingga perubahan data dapat langsung digunakan oleh seluruh portal.
Contoh:
- Data Mesin digunakan oleh Portal Yield, Portal Paperless dan Portal Maintenance.
- Data Varian digunakan oleh Portal Yield dan Portal Mansys.
- Data User digunakan oleh seluruh portal.
---
# Prinsip Arsitektur
Seluruh pengembangan PROD.IO mengikuti prinsip berikut.
## Single Source of Truth
Setiap data hanya memiliki satu sumber utama sehingga tidak terjadi duplikasi informasi.
## Business Driven
Desain sistem mengikuti proses bisnis perusahaan.
Perubahan proses bisnis akan menjadi dasar perubahan sistem.
## Modular
Portal dapat dikembangkan secara mandiri tanpa mempengaruhi portal lainnya.
## Integrated
Seluruh portal saling terhubung menggunakan basis data yang sama sehingga setiap proses dapat ditelusuri dari awal hingga akhir.
## Scalable
## Arsitektur dirancang agar portal baru dapat ditambahkan di masa mendatang tanpa mengubah struktur dasar sistem.
# Diagram Arsitektur
Diagram arsitektur sistem disimpan pada folder:
ERD/
Flow/
Seluruh diagram pada folder tersebut merupakan bagian dari dokumentasi resmi PROD.IO.
