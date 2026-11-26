<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jobarkiv</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-2">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Jobbank.dk</a>
            <div class="d-flex gap-3 small">
                <a href="#" class="link-light text-decoration-none">Log ind</a>
                <a href="#" class="link-light text-decoration-none">Opret profil</a>
                <a href="#" class="link-light text-decoration-none">In English</a>
            </div>
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary bg-opacity-75 py-2 border-top border-light border-opacity-10">
        <div class="container">
            <ul class="navbar-nav me-auto gap-2 small">
                <li class="nav-item"><a class="nav-link py-1" href="#">Find job</a></li>
                <li class="nav-item"><a class="nav-link py-1" href="#">Få karriereråd</a></li>
                <li class="nav-item"><a class="nav-link py-1" href="#">Udforsk arbejdspladser</a></li>
                <li class="nav-item"><a class="nav-link py-1" href="#">Find graduateforløb</a></li>
                <li class="nav-item"><a class="nav-link py-1" href="#">Senkarriere</a></li>
            </ul>
            <a href="#" class="btn btn-success btn-sm">Er du arbejdsgiver?</a>
        </div>
    </nav>
    <section class="bg-white border-bottom py-5">
        <div class="container" style="max-width: 900px;">
            <div class="text-center mb-4">
                <h1 class="fw-bold text-uppercase mb-3">Jobarkiv</h1>
                <p class="text-muted mb-0">
                    Find inaktive jobs og find ud af, hvis virksomheder accepterer uopfordret ansøgning.
                    Gem firma, søg relevante stillinger op til 5 år tilbage og se trend i jobhistorik.
                </p>
            </div>

            <h2 class="h5 text-center fw-semibold mb-3">Søgning og filtering</h2>

            <div class="row g-2 mb-4">
                <div class="col-6 col-md-3">
                    <button type="button" class="btn btn-success w-100">Din uddannelse</button>
                </div>
                <div class="col-6 col-md-3">
                    <button type="button" class="btn btn-success w-100">Ønsket jobtype</button>
                </div>
                <div class="col-6 col-md-3">
                    <button type="button" class="btn btn-success w-100">I området</button>
                </div>
                <div class="col-6 col-md-3">
                    <button type="button" class="btn btn-success w-100">Søgeperiode</button>
                </div>
            </div>

            <div class="row g-3 align-items-center">
                <div class="col-md-4 small text-muted">
                    Ord, som skal indgå i søgningen. Brug gåseøjne til et udtryk, fx "digital marketing".
                </div>
                <div class="col-md-8">
                    <form class="d-flex gap-2" method="GET" action="#">
                        <input
                            type="text"
                            name="q"
                            class="form-control"
                            placeholder="Søgeord til at indsnævre resultatet"
                        >
                        <button type="submit" class="btn btn-dark px-3" aria-label="Søg">
                            Søg
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <main class="container py-5" style="max-width: 780px;">
        <div class="d-flex flex-column gap-4">

            @foreach ($results as $result)

                @php
                    $companyInfo = $result["company_info"]["hits"]["hits"][0]["_source"];
                @endphp
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="bg-dark" style="height: 220px;"></div>
                    <div class="card-body p-4">
                        <div class="row align-items-center mb-4">
                            <div class="col-sm-5">
                                <span class="fw-bold fs-4 text-danger">{{$companyInfo["companyName"]}}</span>
                            </div>
                            <div class="col-sm-7 text-sm-end">
                                <h3 class="h5 fw-bold mb-0">{{$companyInfo["companyName"]}}</h3>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-5">
                                <div class="p-3 rounded bg-success-subtle h-100 d-flex flex-column justify-content-center">
                                    <div>2 aktive jobs</div>
                                    <div>{{ $result["doc_count"] }} inaktive jobs</div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="p-3 rounded bg-light border h-100">
                                    <div class="small text-uppercase text-muted fw-semibold mb-2">
                                        Trend i jobhistorik
                                    </div>
                                    <div class="bg-black rounded w-100" style="height: 64px;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Se alle jobs</a>

                            @if ($companyInfo["companyUnsolicitedApplications"] === "1")
                                <span class="small text-muted">eller</span>
                                <a href="{{$companyInfo["companyUrl"]}}" target="_blank" class="btn btn-success btn-sm rounded-pill px-3">Ansøg uopfordret</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </main>

</body>
</html>
