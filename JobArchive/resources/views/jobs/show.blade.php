<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $job['title'] }} - Jobarkiv</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="job-page">
    <header>
        <nav class="navbar navbar-expand navbar-jobbank-top py-1">
            <div class="container jobbank-nav-container">
                <div class="d-flex align-items-center gap-3 small">
                    <a href="#" class="link-light text-decoration-none">Log ind</a>
                    <a href="#" class="link-light text-decoration-none">Opret profil</a>
                    <a href="#" class="link-light text-decoration-none">In English</a>
                </div>
                <a class="jobbank-logo" href="{{ route('home') }}">
                    <span class="jobbank-logo-mark">J</span>
                    <span><strong>jobbank.dk</strong><small>JOBARKIV OG JOBSØGNING</small></span>
                </a>
            </div>
        </nav>

        <nav class="navbar navbar-expand navbar-jobbank-sub py-0">
            <div class="container jobbank-nav-container">
                <div class="navbar-nav flex-row flex-wrap">
                    <a class="nav-link" href="{{ route('home') }}">Find job</a>
                    <a class="nav-link" href="#">Få karriereråd</a>
                    <a class="nav-link" href="#">Udforsk arbejdspladser</a>
                    <a class="nav-link" href="#">Find graduateforløb</a>
                    <a class="nav-link" href="#">Senkarriere</a>
                </div>
                <a href="#" class="btn btn-jobbank-green btn-sm rounded-0">Er du arbejdsgiver?</a>
            </div>
        </nav>
    </header>

    @php
        $companyName = $job['companyName'] ?? 'Ukendt virksomhed';
        $companyInitials = collect(preg_split('/\s+/', trim($companyName)))
            ->filter()
            ->take(2)
            ->map(fn ($word) => mb_substr($word, 0, 1))
            ->implode('');
        $description = trim(strip_tags($job['extractedContent'] ?? ''));
    @endphp

    <section class="job-company-header">
        <div class="container job-content-width d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="company-monogram" aria-hidden="true">{{ $companyInitials }}</div>
                <span class="company-brand-name">{{ $companyName }}</span>
            </div>
            <h1 class="h4 fw-bold mb-0 text-end">{{ $companyName }}</h1>
        </div>
    </section>

    <main>
        <section class="job-listing-section">
            <div class="container job-content-width">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h2 class="h5 fw-bold mb-0">Jobopslag</h2>
                    <a class="btn btn-outline-secondary btn-sm rounded-pill px-3" href="#recommendations">
                        Se lignende jobs
                    </a>
                </div>

                <article class="job-feature-card">
                    <div class="job-feature-visual">
                        <div class="job-visual-monogram" aria-hidden="true">{{ $companyInitials }}</div>
                        <span class="job-visual-label">JOBARKIV</span>
                    </div>
                    <div class="job-feature-body">
                        <h2 class="h5 fw-bold mb-1">{{ $job['title'] }}</h2>
                        <p class="job-date mb-3">
                            Opslået {{ !empty($job['found']) ? \Illuminate\Support\Carbon::parse($job['found'])->format('d-m-Y') : 'dato ikke angivet' }}
                        </p>

                        <p class="job-description-preview mb-3">
                            {{ \Illuminate\Support\Str::limit($description, 520) }}
                        </p>

                        @if (!empty($job['categories']))
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach (collect($job['categories'])->take(4) as $category)
                                    <span class="badge rounded-pill text-bg-light border fw-normal">
                                        {{ $category['title'] }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="job-meta-row">
                            <span>
                                {{ $job['companyPrimaryAddressZip'] ?? '' }}
                                {{ $job['companyPrimaryAddressCity'] ?? '' }}
                            </span>
                            <span>{{ $companyName }}</span>
                            <a class="btn btn-jobbank-green btn-sm rounded-pill px-3" href="#job-description">
                                Vis jobbet
                            </a>
                        </div>
                    </div>
                </article>

                <article id="job-description" class="job-full-description">
                    <h2 class="h5 fw-bold mb-3">Om jobbet</h2>
                    @if ($description !== '')
                        <p class="mb-0">{{ $description }}</p>
                    @else
                        <p class="text-muted mb-0">Der er ingen jobbeskrivelse tilgængelig.</p>
                    @endif
                </article>
            </div>
        </section>

        <section id="recommendations" class="recommended-jobs-section">
            <div class="container job-content-width">
                <h2 class="h4 fw-bold mb-4">Anbefalede jobs</h2>

                @if ($recommended->isEmpty())
                    <p class="mb-0">Der blev ikke fundet lignende jobs.</p>
                @else
                    <div class="row g-3">
                        @foreach ($recommended as $recommendedJob)
                            @php
                                $recommendedCompany = $recommendedJob['companyName'] ?? 'Ukendt virksomhed';
                                $recommendedInitials = collect(preg_split('/\s+/', trim($recommendedCompany)))
                                    ->filter()
                                    ->take(2)
                                    ->map(fn ($word) => mb_substr($word, 0, 1))
                                    ->implode('');
                            @endphp
                            <div class="col-12 col-md-6 col-lg-4">
                                <article class="recommended-job-card">
                                    <div class="recommended-job-banner">
                                        <span class="recommended-company-mark">{{ $recommendedInitials }}</span>
                                    </div>
                                    <div class="recommended-job-body">
                                        <p class="small text-muted mb-1">{{ $recommendedCompany }}</p>
                                        <h3 class="h6 fw-bold">{{ $recommendedJob['title'] }}</h3>
                                        <p class="recommended-job-location">
                                            {{ $recommendedJob['companyPrimaryAddressZip'] ?? '' }}
                                            {{ $recommendedJob['companyPrimaryAddressCity'] ?? '' }}
                                        </p>
                                        <a href="{{ route('jobs.show', $recommendedJob['id']) }}"
                                           class="btn btn-jobbank-green btn-sm rounded-pill px-3">
                                            Vis jobbet
                                        </a>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </main>
</body>
</html>
