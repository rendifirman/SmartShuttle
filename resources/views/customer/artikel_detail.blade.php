{{-- resources/views/customer/artikel_detail.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->judul }} - Smart Shuttle</title>
    <meta name="description" content="{{ $article->meta_description ?? Str::limit(strip_tags($article->konten), 160) }}">
    <meta name="keywords" content="{{ $article->meta_keywords }}">

    <!-- PERBAIKAN: Tentukan foto untuk OG Image -->
    @php
        // Tentukan foto berdasarkan ID artikel
        $fotoArtikel = [
            1 => 'AR1.png',
            2 => 'AR2.png',
            3 => 'AR3.png',
            4 => 'AR1.png',
            5 => 'AR2.png',
            6 => 'AR3.png',
        ];

        if (isset($fotoArtikel[$article->id])) {
            $fotoUrl = asset('images/' . $fotoArtikel[$article->id]);
        } else {
            $availableFoto = ['AR1.png', 'AR2.png', 'AR3.png'];
            $modIndex = ($article->id - 1) % count($availableFoto);
            $fotoUrl = asset('images/' . $availableFoto[$modIndex]);
        }
    @endphp

    <meta property="og:title" content="{{ $article->judul }}">
    <meta property="og:description" content="{{ $article->meta_description ?? Str::limit(strip_tags($article->konten), 160) }}">
    <meta property="og:image" content="{{ $fotoUrl }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        .article-detail-header {
            background: white;
            padding: 40px 0;
            text-align: center;
        }

        .article-category {
            display: inline-block;
            background: var(--secondary-color, #FF581E);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .article-detail-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-color, #123352);
            margin-bottom: 20px;
            line-height: 1.3;
        }

        .article-meta {
            display: flex;
            justify-content: center;
            gap: 30px;
            color: #666;
            font-size: 14px;
        }

        .article-meta i {
            margin-right: 5px;
            color: var(--secondary-color, #FF581E);
        }

        /* Main Image */
        .article-main-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 40px;
        }

        /* Content */
        .article-content {
            background: white;
            padding: 40px;
            border-radius: 10px;
            margin-bottom: 40px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .article-content h2,
        .article-content h3,
        .article-content h4 {
            color: var(--primary-color, #123352);
            margin: 25px 0 15px;
        }

        .article-content p {
            margin-bottom: 15px;
            text-align: justify;
        }

        .article-content ul,
        .article-content ol {
            margin-left: 20px;
            margin-bottom: 20px;
        }

        .article-content li {
            margin-bottom: 5px;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin: 20px 0;
        }

        /* Share Buttons */
        .share-section {
            text-align: center;
            margin: 40px 0;
        }

        .share-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }

        .share-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .share-btn:hover {
            transform: translateY(-3px);
        }

        .facebook {
            background-color: #3b5998;
        }

        .twitter {
            background-color: #1da1f2;
        }

        .whatsapp {
            background-color: #25d366;
        }

        /* Related Articles */
        .related-articles {
            margin: 60px 0;
        }

        .section-title {
            font-size: 24px;
            color: var(--primary-color, #123352);
            margin-bottom: 30px;
            text-align: center;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .related-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }

        .related-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .related-content {
            padding: 15px;
        }

        .related-category {
            font-size: 11px;
            color: var(--secondary-color, #FF581E);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .related-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-color, #123352);
            line-height: 1.4;
        }

        /* Navigation */
        .article-navigation {
            display: flex;
            justify-content: space-between;
            margin: 40px 0;
            padding: 20px;
            background: white;
            border-radius: 10px;
        }

        .nav-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            background: #f0f0f0;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }

        .nav-btn:hover {
            background: var(--secondary-color, #FF581E);
            color: white;
        }

        /* Back Button */
        .back-section {
            text-align: center;
            margin: 40px 0;
        }

        .back-btn {
            display: inline-block;
            padding: 12px 30px;
            background: var(--secondary-color, #FF581E);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: var(--primary-color, #123352);
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .article-detail-title {
                font-size: 24px;
            }

            .article-main-image {
                height: 250px;
            }

            .article-content {
                padding: 20px;
            }

            .article-meta {
                flex-direction: column;
                gap: 10px;
            }

            .related-grid {
                grid-template-columns: 1fr;
            }

            .article-navigation {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Article Header -->
    <div class="article-detail-header">
        <div class="container">
            <span class="article-category">{{ $article->kategori }}</span>
            <h1 class="article-detail-title">{{ $article->judul }}</h1>
            <div class="article-meta">
                <div><i class="far fa-user"></i> {{ $article->penulis }}</div>
                <div><i class="far fa-calendar-alt"></i> {{ $article->tanggal_format }}</div>
                <div><i class="far fa-clock"></i> {{ $article->waktu_baca }}</div>
                <div><i class="far fa-eye"></i> {{ $article->dilihat }} dilihat</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container">
        <!-- Main Image -->
        <!-- FOTO UTAMA ARTIKEL (Pakai foto kamu) -->
        <img src="{{ $fotoUrl }}" alt="{{ $article->judul }}" class="article-main-image">

        <!-- Article Content -->
        <div class="article-content">
            {!! $article->konten !!}
        </div>

        <!-- Share Section -->
        <div class="share-section">
            <p style="color: #666; margin-bottom: 15px;">Bagikan artikel ini:</p>
            <div class="share-buttons">
                <a href="#" class="share-btn facebook" onclick="shareOnFacebook()">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="share-btn twitter" onclick="shareOnTwitter()">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="share-btn whatsapp" onclick="shareOnWhatsApp()">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>

        <!-- Related Articles -->
        @if($relatedArticles->count() > 0)
            <div class="related-articles">
                <h2 class="section-title">Artikel Terkait</h2>
                <div class="related-grid">
                    @foreach($relatedArticles as $related)
                        <!-- Tentukan foto untuk artikel terkait -->
                        @php
                            // LOGIKA YANG SAMA untuk artikel terkait
                            if (isset($fotoArtikel[$related->id])) {
                                $relatedFotoUrl = asset('images/' . $fotoArtikel[$related->id]);
                            } else {
                                $availableFoto = ['AR1.png', 'AR2.png', 'AR3.png'];
                                $modIndex = ($related->id - 1) % count($availableFoto);
                                $relatedFotoUrl = asset('images/' . $availableFoto[$modIndex]);
                            }
                        @endphp

                        <a href="{{ route('artikel.show', $related->slug) }}" class="related-card">
                            <img src="{{ $relatedFotoUrl }}" alt="{{ $related->judul }}" class="related-image">
                            <div class="related-content">
                                <div class="related-category">{{ $related->kategori }}</div>
                                <h3 class="related-title">{{ $related->judul }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="back-section">
            <a href="{{ route('customer.artikel.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Artikel
            </a>
        </div>
    </main>

    <script>
        // Fungsi share
        function shareOnFacebook() {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent('{{ $article->judul }}');
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${title}`, '_blank');
        }

        function shareOnTwitter() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent('{{ $article->judul }}');
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
        }

        function shareOnWhatsApp() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent('{{ $article->judul }}');
            window.open(`https://api.whatsapp.com/send?text=${text} ${url}`, '_blank');
        }
    </script>
</body>
</html>
