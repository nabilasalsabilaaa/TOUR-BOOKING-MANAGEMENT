<div align="center">

<h1 style="font-weight:800; font-size:42px; margin-bottom:0;">
🌍 TourBooking Management 🌍
</h1>
<p style="font-size:18px; color:#777; margin-top:5px;">
Laravel Tour Booking & Management System
</p>

</div>

---

<h2>🌟 Overview</h2>

<p>
<strong>TourBooking Management</strong> adalah aplikasi manajemen pemesanan tour berbasis Laravel. 
Sistem ini memungkinkan customer melakukan booking tour, melihat jadwal, dan melakukan pembayaran, 
sementara admin dapat mengelola tour, jadwal, booking, dan melihat statistik sistem.
</p>

---

<h2>🚀 Features</h2>

<h3>✨ Customer</h3>
<ul>
  <li>Melihat daftar tour & pencarian.</li>
  <li>Melihat detail tour + jadwal.</li>
  <li>Booking tour dengan jumlah peserta.</li>
  <li>Membatalkan booking yang belum dikonfirmasi.</li>
  <li>Dashboard personal: booking list, upcoming schedule.</li>
</ul>

<h3>🛠️ Admin</h3>
<ul>
  <li>Akses dashboard admin.</li>
  <li>Kelola Tour: tambah/edit/hapus tour.</li>
  <li>Kelola Jadwal: buat jadwal + slot peserta.</li>
  <li>Konfirmasi atau batalkan booking customer.</li>
  <li>Lihat statistik booking & upcoming tours.</li>
</ul>

---

<h2>🧱 Tech Stack</h2>

<table>
<tr><th>Tech</th><th>Description</th></tr>
<tr><td>Laravel 12</td><td>Backend Framework</td></tr>
<tr><td>Blade</td><td>Template Engine</td></tr>
<tr><td>TailwindCSS</td><td>UI Styling</td></tr>
<tr><td>FontAwesome</td><td>Icons</td></tr>
<tr><td>MySQL</td><td>Main Database</td></tr>
<tr><td>Local Storage</td><td>Image & File Storage</td></tr>
</table>

---

<h2>📌 Requirements</h2>

<ul>
  <li><strong>PHP:</strong> 8.2.12</li>
  <li><strong>Composer:</strong> 2.8.12</li>
  <li><strong>Laravel:</strong> 12.x</li>
  <li><strong>Node.js:</strong> v22+</li>
  <li><strong>NPM:</strong> 10+</li>
  <li><strong>Database:</strong> MySQL 10.4+</li>
</ul>

---

<h2>⚙ Installation</h2>

<ol>
  <li>
    <strong>Clone Repository</strong>
    <pre><code>
git clone https://github.com/nabilasalsabilaaa/TourBookingManagement.git
cd TourBookingManagement
    </code></pre>
  </li>

  <li>
    <strong>Install Dependencies</strong>
    <pre><code>
composer install
npm install
npm run build
    </code></pre>
  </li>

  <li>
    <strong>Copy .env file</strong>
    <pre><code>
cp .env.example .env
    </code></pre>
  </li>

  <li>
    <strong>Setup database configuration:</strong>
    <pre><code>
DB_CONNECTION=mysql
DB_DATABASE=tourbooking
DB_USERNAME=root
DB_PASSWORD=
    </code></pre>
  </li>

  <li>
    <strong>Generate Application Key</strong>
    <pre><code>
php artisan key:generate
    </code></pre>
  </li>

  <li>
    <strong>Buat Storage Symlink</strong>
    <pre><code>
php artisan storage:link
    </code></pre>
  </li>

  <li>
    <strong>Run Migration + Seeder</strong>
    <pre><code>
php artisan migrate:fresh --seed
    </code></pre>
    <p>Seeder akan membuat data awal untuk:</p>
    <ul>
      <li>Admin & Customer accounts</li>
      <li>Tour samples</li>
      <li>Schedule samples</li>
    </ul>
  </li>

  <br>

  <li>
    <strong>Start the server</strong>
    <pre><code>
php artisan serve
    </code></pre>
    <p>Akses aplikasi: <code>http://localhost:8000</code></p>
  </li>
</ol>

---

<h2>🔐 Default Login Accounts</h2>

<table>
<tr><th>Role</th><th>Email</th><th>Password</th></tr>
<tr><td>Admin</td><td>admin@gmail.com</td><td>12345678</td></tr>
</table>

---

<h2 align="center">👩‍💻 Developed By</h2>

<p align="center">

  <!-- Developer 1 -->
  <a href="https://github.com/nabilasalsabilaaa" target="_blank">
    <img 
      src="https://github.com/nabilasalsabilaaa.png" 
      width="110" 
      style="border-radius: 50%; margin: 10px;"
    />
  </a>

  <!-- Developer 2 -->
  <a href="https://github.com/DewiDevX" target="_blank">
    <img 
      src="https://github.com/DewiDevX.png" 
      width="110" 
      style="border-radius: 50%; margin: 10px;"
    />
  </a>

  <!-- Developer 3 -->
  <a href="https://github.com/zakiyah10" target="_blank">
    <img 
      src="https://github.com/zakiyah10.png" 
      width="110" 
      style="border-radius: 50%; margin: 10px;"
    />
  </a>

</p>

<p align="center">
  <b>Nabila Salsabila</b> • 
  <b>Dewi Astuti Muchtar</b> • 
  <b>Syahrani Zakiyah Nurfaizah</b>
</p>

<p align="center">
  <img src="https://img.shields.io/github/contributors/nabilasalsabilaaa/TOUR-BOOKING-MANAGEMENT?style=for-the-badge&color=A78BFA">
</p>

