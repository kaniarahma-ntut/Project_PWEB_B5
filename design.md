# 🎨 Smart Library - Design System Documentation

## 1. Overview & Vibes
> **Vibe:** Profesional, *Modern SaaS (Software as a Service)*, bersih, dan kokoh.  
> Menggabungkan ketegasan warna *Navy* khas sistem industrial dengan antarmuka *Light Theme* yang cerah, memberikan kesan bahwa sistem ini stabil, terpercaya, namun tetap sangat nyaman digunakan berlama-lama (tidak membuat mata cepat lelah).

---

## 2. Typography & Font Pairing

Aplikasi ini menggunakan kombinasi font dari Google Fonts untuk memisahkan antara elemen hierarki (judul) dengan elemen bacaan (data).

* **Primary Font (Headers, Titles, Buttons, Badges):** `Montserrat` (Bold 700 / SemiBold 600)
    * *Karakteristik:* Tegas, geometris, memberikan kesan kokoh.
* **Secondary Font (Body Text, Form Inputs, Table Data):** `Open Sans` (Regular 400 / SemiBold 600)
    * *Karakteristik:* Sangat mudah dibaca (*legible*) dalam ukuran kecil, membulat, dan netral.

> **Import Link:** > `@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600;700&display=swap');`

---

## 3. Color Palette

### 🔵 Utama (Brand Colors)
| Color | Name | Hex Code | Penggunaan Utama |
| :--- | :--- | :--- | :--- |
| ![#1B262C](https://via.placeholder.com/15/1B262C/1B262C?text=+) | **Deep Navy** | `#1B262C` | Judul Halaman (H1/H2), Teks Highlight, Tombol Utama (Hover), Border Aktif. |
| ![#0F4C75](https://via.placeholder.com/15/0F4C75/0F4C75?text=+) | **Royal Ink** | `#0F4C75` | Teks Paragraf, Label Form, Teks Tabel, Ikon Inaktif. |
| ![#3282B8](https://via.placeholder.com/15/3282B8/3282B8?text=+) | **Electric Blue**| `#3282B8` | Tombol Utama, Ikon Aktif, Link, Aksen *Hover*, Peringatan Info. |

### ⚪ Latar & Garis (Background & Borders)
| Color | Name | Hex Code | Penggunaan Utama |
| :--- | :--- | :--- | :--- |
| ![#BBE1FA](https://via.placeholder.com/15/BBE1FA/BBE1FA?text=+) | **Frost White** | `#BBE1FA` | Garis Batas (*Border*), *Divider*, *Background Badge* transparan. |
| ![#F4F9FD](https://via.placeholder.com/15/F4F9FD/F4F9FD?text=+) | **Pale Blue** | `#F4F9FD` | Latar Belakang Input Form, Latar Belakang *Body* Web (Area Luar Card). |
| ![#FFFFFF](https://via.placeholder.com/15/FFFFFF/FFFFFF?text=+) | **Pure Base** | `#FFFFFF` | *Background Sidebar*, *Background Card* Dashboard/Buku, Form Container. |

### 🔴 Status & Semantik (Semantic Colors)
| Color | Name | Hex Code | Penggunaan Utama |
| :--- | :--- | :--- | :--- |
| ![#D32F2F](https://via.placeholder.com/15/D32F2F/D32F2F?text=+) | **Danger / Red** | `#D32F2F` | Tombol Logout, Buku Nonaktif, Stok Kosong, Pesan Error Form, Denda. |
| ![#2E7D32](https://via.placeholder.com/15/2E7D32/2E7D32?text=+) | **Success/Green**| `#2E7D32` | Status Buku Tersedia, Denda Lunas. |
| ![#F57C00](https://via.placeholder.com/15/F57C00/F57C00?text=+) | **Warning/Orange**|`#F57C00` | Status Menunggu Konfirmasi/Proses. |

---

## 4. UI Element Guidelines

### A. Sudut Membulat (Border Radius)
Sistem ini menggunakan bentuk membulat yang cukup ekstrim untuk melembutkan warna Navy yang kaku:
* **Card / Panel Utama:** `rounded-2xl` (Sangat membulat).
* **Form Input, Tombol, Select Box:** `rounded-xl` atau `rounded-lg`.
* **Badge / Status:** `rounded-md` atau `rounded-full`.

### B. Bayangan (Shadows) & Efek Melayang
* **Shadow Default (Card diam):** Lembut dan nyaris tidak terlihat.
    * *Class:* `shadow-[0_2px_16px_rgba(27,38,44,0.04)]`
* **Shadow Hover (Card disorot):** Sedikit lebih menyebar ke luar.
    * *Class:* `hover:shadow-[0_8px_40px_rgba(27,38,44,0.08)]`

### C. Efek Interaksi (Hover Transitions)
Setiap elemen yang bisa diklik harus memiliki transisi (*transition-all duration-300*):
1.  **Tombol/Link Sidebar:** Ikon sedikit membesar (`scale-110`) dan teks bergeser sedikit ke kanan (`translate-x-1`).
2.  **Card Buku:** Garis tepi (border) berubah dari `Frost White` ke `Electric Blue`, *cover* gambar membesar (`scale-105`), judul berubah warna ke `Electric Blue`.
3.  **Input Form:** Latar input yang awalnya `Pale Blue` berubah menjadi `Pure Base` (Putih) dan memunculkan efek `ring` berwarna `Electric Blue` saat diklik (focus).

---

## 5. Komponen Tailwind Kunci (Quick Copy)

**Primary Button (Electric Blue ke Deep Navy):**
```html
<button class="px-6 py-2.5 bg-[#3282B8] hover:bg-[#1B262C] text-white font-montserrat text-xs font-bold rounded-lg transition-colors shadow-md">
    Simpan
</button>
