<div class="row">
    <div class="col-lg-12 col-xl-6">
        <div class="card">
            <div class="card-body position-relative">
                <div>
                    <h4 class="card-title mb-1">Seja bem-vindo <?= $this->request->getAttribute('identity')->nome; ?></h4>
                    <p class="card-subtitle mb-3 pb-1">Check All The Statastics</p>
                    <button class="btn btn-primary rounded-pill" type="button">
                        Visit Now
                    </button>
                </div>
                <div class="school-img d-none d-sm-block">
                    <img src="img/backgrounds/welcome.png" class="img-fluid" alt="spike-img">
                </div>

                <div class="d-sm-none d-block text-center">
                    <img src="img/backgrounds/welcome.png" class="img-fluid" alt="spike-img">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-xl-6">
        <div class="row">
            <div class="col-sm-4">
                <div class="card warning-card overflow-hidden text-bg-primary">
                    <div class="card-body p-4">
                        <div class="mb-7">
                            <i class="ti ti-brand-producthunt fs-8 fw-lighter"></i>
                        </div>
                        <h5 class="text-white fw-bold fs-14 text-nowrap">
                            2358 <span class="fs-2 fw-light">+23%</span>
                        </h5>
                        <p class="opacity-50 mb-0 ">Agendamentos</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card danger-card overflow-hidden text-bg-primary">
                    <div class="card-body p-4">
                        <div class="mb-7">
                            <i class="ti ti-report-money fs-8 fw-lighter"></i>
                        </div>
                        <h5 class="text-white fw-bold fs-14">
                            356 <span class="fs-2 fw-light">+8%</span>
                        </h5>
                        <p class="opacity-50 mb-0">Refunds</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card info-card overflow-hidden text-bg-primary">
                    <div class="card-body p-4">
                        <div class="mb-7">
                            <i class="ti ti-currency-dollar fs-8 fw-lighter"></i>
                        </div>
                        <h5 class="text-white fw-bold fs-14 text-nowrap">
                            $235.8K <span class="fs-2 fw-light">-3%</span>
                        </h5>
                        <p class="opacity-50 mb-0">Earnings</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>