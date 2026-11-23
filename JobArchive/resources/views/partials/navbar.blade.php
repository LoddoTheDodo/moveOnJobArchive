<header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-jobbank-top py-2">
        <div class="container" style="max-width: 920px;">
            <div class="d-flex flex-wrap align-items-center gap-4 small">
                <a href="{{ url('/login') }}" class="link-light text-decoration-none">Log ind</a>
                <a href="{{ url('/register') }}" class="link-light text-decoration-none">Opret profil</a>
                <a href="#" class="link-light text-decoration-none">In English</a>
            </div>

            <a class="navbar-brand fw-bold text-end lh-1 ms-auto me-0" href="{{ url('/') }}">
                <span class="fs-4 d-block">Jobbank.dk</span>
                <span class="text-white-50 fw-normal">AKADEMIKERNES JOBBANK</span>
            </a>
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-jobbank-sub py-2">
        <div class="container" style="max-width: 920px;">
            <ul class="navbar-nav me-auto flex-row flex-wrap gap-3 small">
                <li class="nav-item">
                    <a class="nav-link py-1 px-0 text-white" href="{{ url('/') }}">Find job</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-1 px-0 text-white" href="#">Få karriereråd</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-1 px-0 text-white" href="#">Udforsk arbejdspladser</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-1 px-0 text-white" href="#">Find graduateforløb</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-1 px-0 text-white" href="#">Senkarriere</a>
                </li>
            </ul>

            <a href="#" class="btn btn-jobbank-green btn-sm px-3">Er du arbejdsgiver?</a>
        </div>
    </nav>
</header>