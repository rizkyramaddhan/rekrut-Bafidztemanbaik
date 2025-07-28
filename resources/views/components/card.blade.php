<div class="col-md-4 mb-3">
    <div class="card shadow-sm border-0 rounded">
        <div class="card-body">
            <div class="row align-items-start">
                <div class="col-9">
                    <h6 class="card-title text-muted mb-1" style="font-size: 0.875rem;">{{ $title }}</h6>
                    <h2 class="text-primary mb-2 fw-bold" style="font-size: 2.5rem;">{{ $value }}</h2>
                    <a href="{{ $href }}" class="btn btn-primary btn-sm px-3 py-1" style="font-size: 0.75rem;">
                        Lihat Pelamar Proses
                    </a>
                </div>
                <div class="col-3 text-end">
                    <i class="{{ $icon }} text-primary" style="font-size: 2.5rem; margin-top: 0.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>