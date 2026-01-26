<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel & Berita - Smart Shuttle</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Reset dan base styles */
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        .articles-header {
            background: linear-gradient(135deg, #123352, #00308F);
            color: white;
            padding: 80px 0 40px;
            text-align: center;
            margin-bottom: 40px;
        }

        .articles-title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .articles-subtitle {
            font-size: 16px;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Search and Filter Section */
        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
            justify-content: space-between;
        }

        .search-box {
            flex: 1;
            min-width: 300px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 12px 20px 12px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: #FF581E;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .kategori-filter {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .kategori-btn {
            padding: 8px 16px;
            background: #f0f0f0;
            border: none;
            border-radius: 20px;
            color: #333;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .kategori-btn:hover,
        .kategori-btn.active {
            background: #FF581E;
            color: white;
        }

        /* Articles Grid */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .article-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .article-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .article-content {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .article-category {
            display: inline-block;
            background: #FF581E;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
            align-self: flex-start;
        }

        .article-title {
            font-size: 18px;
            font-weight: 700;
            color: #123352;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .article-excerpt {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            flex: 1;
        }

        .article-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #f0f0f0;
            padding-top: 15px;
            margin-top: auto;
        }

        .article-date {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .article-read-more {
            color: #FF581E;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }

        .article-read-more:hover {
            color: #123352;
            text-decoration: underline;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 40px 0;
        }

        .page-link {
            padding: 8px 15px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: #f0f0f0;
        }

        .page-link.active {
            background: #FF581E;
            color: white;
            border-color: #FF581E;
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
            margin: 40px 0;
        }

        .no-results i {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .no-results h3 {
            font-size: 24px;
            color: #666;
            margin-bottom: 10px;
        }

        .no-results p {
            color: #888;
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .articles-grid {
                grid-template-columns: 1fr;
            }

            .articles-title {
                font-size: 28px;
            }

            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                min-width: 100%;
            }

            .kategori-filter {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="articles-header">
        <div class="container">
            <h1 class="articles-title">Artikel & Berita Terbaru</h1>
            <p class="articles-subtitle">
                Dapatkan informasi terbaru seputar layanan transportasi, tips perjalanan, dan berita terbaru dari Smart Shuttle.
            </p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <!-- Search and Filter -->
        <div class="filter-section">
            <form action="{{ route('customer.artikel.index') }}" method="GET" class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Cari artikel..." value="{{ request('search') }}">
            </form>

            <div class="kategori-filter">
                <a href="{{ route('customer.artikel.index') }}"
                   class="kategori-btn {{ !request('kategori') ? 'active' : '' }}">
                    Semua
                </a>
                @foreach($kategoriList as $kategoriItem)
                    <a href="{{ route('customer.artikel.kategori', $kategoriItem) }}"
                       class="kategori-btn {{ request('kategori') == $kategoriItem ? 'active' : '' }}">
                        {{ $kategoriItem }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Articles Grid -->
        @if(isset($articles) && $articles->count() > 0)
            <div class="articles-grid">
                @foreach($articles as $article)
                    @php
                        // Gunakan gambar dari database jika ada
                        $gambarUrl = asset('images/AR1.png'); // default
                        
                        if ($article->gambar) {
                            if (Storage::disk('public')->exists($article->gambar)) {
                                $gambarUrl = asset('storage/' . $article->gambar);
                            }
                        }
                        
                        // Format tanggal
                        $tanggalFormat = \Carbon\Carbon::parse($article->tanggal_publikasi)
                            ->translatedFormat('d F Y');
                    @endphp

                    <div class="article-card">
                        <img src="{{ $gambarUrl }}" 
                             alt="{{ $article->judul }}" 
                             class="article-image"
                             onerror="this.onerror=null; this.src='{{ asset('images/AR1.png') }}';">
                        <div class="article-content">
                            <span class="article-category">{{ $article->kategori }}</span>
                            <h3 class="article-title">{{ $article->judul }}</h3>
                            <p class="article-excerpt">{{ Str::limit(strip_tags($article->konten), 150) }}</p>
                            <div class="article-meta">
                                <div class="article-date">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $tanggalFormat }}
                                </div>
                                <a href="{{ route('customer.artikel.show', $article->slug) }}" class="article-read-more">
                                    Baca Selengkapnya →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($articles->hasPages())
                <div class="pagination">
                    @if($articles->onFirstPage())
                        <span class="page-link disabled">«</span>
                    @else
                        <a href="{{ $articles->previousPageUrl() }}" class="page-link">«</a>
                    @endif

                    @for($i = 1; $i <= $articles->lastPage(); $i++)
                        <a href="{{ $articles->url($i) }}"
                           class="page-link {{ $articles->currentPage() == $i ? 'active' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    @if($articles->hasMorePages())
                        <a href="{{ $articles->nextPageUrl() }}" class="page-link">»</a>
                    @else
                        <span class="page-link disabled">»</span>
                    @endif
                </div>
            @endif
        @else
            <div class="no-results">
                <i class="far fa-newspaper"></i>
                <h3>Tidak ada artikel ditemukan</h3>
                <p>Coba gunakan kata kunci lain atau lihat kategori yang tersedia.</p>
                <a href="{{ route('customer.artikel.index') }}" class="kategori-btn">
                    Lihat Semua Artikel
                </a>
            </div>
        @endif
    </main>

    <!-- Footer Navigation -->
    <footer style="background: #f0f0f0; padding: 40px 0; margin-top: 60px;">
        <div class="container" style="text-align: center;">
            <a href="{{ route('customer.beranda') }}"
               style="display: inline-block; padding: 12px 30px; background: #FF581E; color: white; text-decoration: none; border-radius: 25px; font-weight: 600;">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
        </div>
    </footer>

    <script>
        // Auto submit search on enter
        document.querySelector('.search-box input')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });

        // Auto focus search input
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-box input');
            const urlParams = new URLSearchParams(window.location.search);
            if (searchInput && urlParams.has('search')) {
                searchInput.focus();
                searchInput.select();
            }
        });
    </script>
</body>
</html>