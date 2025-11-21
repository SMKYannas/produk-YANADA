<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Beranda | Produk Siswa SMKS Kesehatan Yannas Husada</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <section id="home" class="hero-slider">
        <div class="slide active" data-slide-id="0">
            <img src="image/hero1.jpg" alt="Produk Unggulan Siswa Yannas Husada" class="w-full h-full object-cover" />
            <div class="slide-content">
                <div class="container mx-auto px-4">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Inovasi Kesehatan Karya Siswa</h2>
                    <p class="text-lg md:text-xl mb-6">Produk Herbal Alami dan Berkualitas dari SMKS Kesehatan Yannas
                        Husada Bangkalan</p>
                    <a href="#contact" class="btn-primary px-6 py-3 rounded-full font-medium inline-block">Pesan
                        Sekarang</a>
                </div>
            </div>
        </div>

        <div class="slide" data-slide-id="1">
            <img src="image/hero2.jpg" alt="Siswa sedang memproduksi produk herbal"
                class="w-full h-full object-cover" />
            <div class="slide-content">
                <div class="container mx-auto px-4">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Dukung Kewirausahaan Siswa</h2>
                    <p class="text-lg md:text-xl mb-6">Setiap produk dibuat melalui proses praktik laboratorium yang
                        terstandarisasi.</p>
                    <a href="#contact" class="btn-primary px-6 py-3 rounded-full font-medium inline-block">Pesan
                        Sekarang</a>
                </div>
            </div>
        </div>

        <div class="slide" data-slide-id="2">
            <img src="image/hero3.jpg" alt="Siswa sedang melayani perawatan" class="w-full h-full object-cover" />
            <div class="slide-content">
                <div class="container mx-auto px-4">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Perawatan Karya Siswa</h2>
                    <p class="text-lg md:text-xl mb-6">Pemeriksaan kesehatan dan perawatan wajah.</p>
                    <a href="#contact" class="btn-primary px-6 py-3 rounded-full font-medium inline-block">Pesan
                        Sekarang</a>
                </div>
            </div>
        </div>

        <div class="nav-dots">
            <div class="nav-dot active" data-slide="0"></div>
            <div class="nav-dot" data-slide="1"></div>
            <div class="nav-dot" data-slide="2"></div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 text-center">
            <div data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">
                    <i class="fas fa-microscope text-primary mr-2"></i> Pendidikan Berbasis Produksi
                </h2>
                <div class="section-divider"></div>
                <p class="text-gray-600 max-w-4xl mx-auto text-lg">
                    SMKS Kesehatan Yannas Husada tidak hanya fokus pada teori, tetapi juga membekali siswa dengan
                    keterampilan praktis wirausaha. Produk-produk yang kami hasilkan merupakan hasil inovasi dan kerja
                    keras siswa dalam menerapkan ilmu kesehatan dan farmasi herbal.
                </p>
                <a href="products.php"
                    class="btn-primary mt-8 px-8 py-3 rounded-full font-semibold inline-block transition-transform hover:scale-105"
                    data-aos="zoom-in" data-aos-delay="200">
                    Lihat Katalog Lengkap Produk
                </a>
            </div>
        </div>
    </section>

    <section id="gallery" class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">
                    <i class="fas fa-camera-retro text-primary mr-2"></i> Proses Produksi & Kegiatan
                </h2>
                <div class="section-divider"></div>
                <p class="text-gray-600 max-w-3xl mx-auto">
                    Momen siswa dalam proses praktik dan kewirausahaan produk kesehatan.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div class="overflow-hidden rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
                    <img src="image/images1.jpg" alt="Siswa melakukan uji kualitas produk"
                        class="w-full h-48 object-cover hover:scale-110 transition duration-300" />
                </div>
                <div class="overflow-hidden rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="200">
                    <img src="image/images2.jpg" alt="Siswa melakukan uji kualitas produk"
                        class="w-full h-48 object-cover hover:scale-110 transition duration-300" />
                </div>
                <div class="overflow-hidden rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="300">
                    <img src="image/images3.jpg" alt="Siswa melakukan uji kualitas produk"
                        class="w-full h-48 object-cover hover:scale-110 transition duration-300" />
                </div>
                <div class="overflow-hidden rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="400">
                    <img src="image/images4.jpg" alt="Proses ekstraksi bahan herbal di lab"
                        class="w-full h-48 object-cover hover:scale-110 transition duration-300" />
                </div>
                <div class="overflow-hidden rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="500">
                    <img src="image/pelayanan/tes_darah.jpg" alt="Siswa melakukan uji kualitas produk"
                        class="w-full h-48 object-cover hover:scale-110 transition duration-300" />
                </div>
                <div class="overflow-hidden rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="600">
                    <img src="image/pelayanan/tekanan_darah.jpg" alt="Siswa melakukan uji kualitas produk"
                        class="w-full h-48 object-cover hover:scale-110 transition duration-300" />
                </div>
                <div class="overflow-hidden rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="700">
                    <img src="image/pelayanan/bekam.jpg" alt="Siswa melakukan uji kualitas produk"
                        class="w-full h-48 object-cover hover:scale-110 transition duration-300" />
                </div>
                <div class="overflow-hidden rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="800">
                    <img src="image/pelayanan/facial.jpg" alt="Proses ekstraksi bahan herbal di lab"
                        class="w-full h-48 object-cover hover:scale-110 transition duration-300" />
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="py-16 bg-primary text-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold"><i class="fas fa-shopping-cart mr-2"></i> Pemesanan &
                    Informasi</h2>
                <div class="section-divider bg-white"></div>
                <p class="max-w-3xl mx-auto text-lg opacity-100">Pesan produk unggulan kami dengan mudah melalui WhatsApp
                    atau hubungi kontak resmi sekolah.</p>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 justify-center">
                <div class="lg:w-1/2" data-aos="fade-right">
                    <div class="bg-secondary p-8 rounded-lg shadow-xl">
                        <h3 class="text-2xl font-bold mb-6">Informasi Kontak</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="text-green-600 text-xl mr-4 mt-1">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Alamat</h4>
                                    <p class="text-gray-600">
                                        Jl. Letnan Singosastro No3, Kraton, Bangkalan, Jawa Timur
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="text-green-600 text-xl mr-4 mt-1">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">WhatsApp</h4>
                                    <p class="text-gray-600">
                                        <a href="https://wa.me/6281808001437" target="_blank"
                                            class="underline hover:opacity-80">+62 818-0800-1437 (Admin Penjualan)</a>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="text-green-600 text-xl mr-4 mt-1">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Email</h4>
                                    <p class="text-gray-600">smkkesehatanyannas@gmail.com</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="text-green-600 text-xl mr-4 mt-1">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Jam Operasional</h4>
                                    <p class="text-gray-600">Senin-Jumat: 07.30 - 15.00 WIB</p>
                                    <p class="text-gray-600">Sabtu: 08.00 - 12.00 WIB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md overflow-hidden mt-8">
                        <div class="h-80 w-full">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.800640323701!2d112.7476389!3d-7.0327033000000005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd8058efc05017b%3A0x30252e76a83ee75e!2sHealth%20Vocational%20Yannas%20Husada!5e0!3m2!1sen!2sid!4v1763570049892!5m2!1sen!2sid"
                                style="border:0; width: 100%; height: 100%" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-secondary text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-lg font-semibold mb-2">SMKS Kesehatan Yannas Husada Bangkalan</p>
            <p class="text-sm opacity-80">
                Inovasi Produk Kesehatan Siswa - &copy; 2025. All Rights Reserved.
            </p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="scripts.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
        });
    </script>
</body>

</html>
