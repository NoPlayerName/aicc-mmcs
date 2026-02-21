<div wire:ignore.self class="modal fade" id="modal-material-input" tabindex="-1" role="dialog"
    aria-labelledby="modal-material-input-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 95vw;">
        <div class="modal-content shadow-lg border-0">

            <div class="modal-header bg-light border-bottom">
                <h5 class="modal-title h5 font-weight-bold text-dark" id="myExtraLargeModalLabel">
                    <i class="fas fa-industry text-primary mr-2"></i>Material Input Furnace
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4">
                <div class="card border-0 bg-soft-primary mb-4 shadow-sm">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-4 border-right">
                                <label class="text-muted small text-uppercase font-weight-bold mb-1 d-block">Charging
                                    Status</label>
                                <h5 class="mb-0 text-primary font-weight-bold">{{ $charging }}</h5>
                            </div>

                            <div class="col-md-4 border-right">
                                <label class="text-muted small text-uppercase font-weight-bold mb-1 d-block">Lot
                                    Number</label>
                                @if($lot != null)
                                <h5 class="mb-0 font-weight-bold text-dark">{{ $lot }}</h5>
                                @else
                                <div wire:ignore class="mt-1">
                                    <select id="selectLotMultiple" class="form-control select2-multiple"
                                        multiple="multiple">
                                        @for ($a = 1 ; $a <= 100 ; $a++) <option value="{{ $a }}">Lot {{ $a }}</option>
                                            @endfor
                                    </select>
                                </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <label
                                    class="text-muted small text-uppercase font-weight-bold mb-1 d-block">Product</label>
                                @if ($product != null)
                                <h5 class="mb-0 font-weight-bold text-dark text-truncate">{{ $product }}</h5>
                                @else
                                <div wire:ignore wire:key="select-product" class="mt-1">
                                    <select class="form-control select2" id="product-select2">
                                        <option value="">Select Product</option>
                                        @foreach ($products as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="custom-tab">
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold" data-toggle="tab" href="#rawMaterial"
                                role="tab">
                                <i class="fas fa-box-open mr-2 d-none d-sm-inline"></i>Raw Material
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-toggle="tab" href="#additive" role="tab">
                                <i class="fas fa-flask mr-2 d-none d-sm-inline"></i>Additive
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-toggle="tab" href="#kwh" role="tab">
                                <i class="fas fa-bolt mr-2 d-none d-sm-inline"></i>KWH
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-toggle="tab" href="#temptTapping" role="tab">
                                <i class="fas fa-thermometer-half mr-2 d-none d-sm-inline"></i>Temp Tapping
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content p-4 border border-top-0 rounded-bottom bg-white">

                        <div class="tab-pane active fade show" id="rawMaterial" role="tabpanel">
                            <div class="row align-items-end g-3">
                                <div class="col-md-5">
                                    <label class="form-label font-weight-bold text-muted">Material Type</label>
                                    <select
                                        class="form-control form-control-lg select2-search-disable border-primary-soft">
                                        <option>Select Material</option>
                                        <option>Steel Scrap</option>
                                        <option>Bricket</option>
                                        <optgroup label="Return Scrap">
                                            <option value="CA">RS ACE</option>
                                            <option value="NV">AGARI</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label font-weight-bold text-muted">Weight (KG)</label>
                                    <div class="input-group input-group-lg">
                                        <input type="number" class="form-control" placeholder="0.00">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-light font-weight-bold">kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary btn-lg btn-block shadow-sm">
                                        <i class="fas fa-plus mr-1"></i> Add
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-info font-weight-bold">Total Material</label>
                                    <input
                                        class="form-control form-control-lg bg-soft-info border-info text-dark font-weight-bold"
                                        readonly value="0">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="additive" role="tabpanel">
                            <div class="row align-items-end g-3">
                                <div class="col-md-6">
                                    <label class="form-label font-weight-bold text-muted">Additive Type</label>
                                    <select class="form-control form-control-lg">
                                        <option>Select Additive</option>
                                        <option>Carbon G-8</option>
                                        <option>Carbon SP-500</option>
                                        <option>Fe.Si</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label font-weight-bold text-muted">Weight (KG)</label>
                                    <input type="number" class="form-control form-control-lg" placeholder="0.00">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary btn-lg btn-block shadow-sm">
                                        <i class="fas fa-plus mr-1"></i> Add
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="kwh" role="tabpanel">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label font-weight-bold text-muted">Charging Time</label>
                                    <input class="form-control form-control-lg" type="time">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label font-weight-bold text-muted">KWH Start</label>
                                    <input class="form-control form-control-lg" type="number" placeholder="0">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label font-weight-bold text-muted">KWH OK</label>
                                    <input class="form-control form-control-lg" type="number" placeholder="0">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label font-weight-bold text-muted">Power (kW)</label>
                                    <input class="form-control form-control-lg border-primary-soft" type="number"
                                        placeholder="0">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="temptTapping" role="tabpanel">
                            <div class="row align-items-end g-3">
                                <div class="col-md-4">
                                    <label class="form-label font-weight-bold text-muted">Temperature (°C)</label>
                                    <div class="input-group input-group-lg">
                                        <input type="number" class="form-control" placeholder="1000">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-light text-danger"><i
                                                    class="fas fa-thermometer-three-quarters"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label font-weight-bold text-muted">Type Tapping</label>
                                    <select class="form-control form-control-lg">
                                        <option>Select Type</option>
                                        <option>Sample 1</option>
                                        <option>Taping 1</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-primary btn-lg btn-block shadow-sm">
                                        <i class="fas fa-plus mr-1"></i> Add Data
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-link text-muted font-weight-bold px-4"
                    data-dismiss="modal">Discard</button>
                <button type="submit" class="btn btn-primary px-5 shadow font-weight-bold">
                    <i class="fas fa-save mr-2"></i>Save All Changes
                </button>
            </div>
        </div>
    </div>
</div>