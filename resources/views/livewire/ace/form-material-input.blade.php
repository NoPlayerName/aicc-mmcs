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
                                        <option value="{{ $p->id }}">{{ $p->alias }}</option>
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

                    <div wire:ignore class="tab-content p-4 border border-top-0 rounded-bottom bg-white">

                        <div class="tab-pane active fade show" id="rawMaterial" role="tabpanel"
                            wire:key="rawMat-{{ $chargeId }}">

                            @livewire('ace.raw-material', ['chargeId' => $chargeId, 'edit' => $edit])

                        </div>

                        <div class="tab-pane fade" id="additive" role="tabpanel" wire:key="Additive-{{ $chargeId }}">
                            @livewire('ace.additive-material', ['chargeId' => $chargeId])
                        </div>

                        <div class="tab-pane fade" id="kwh" role="tabpanel" wire:key="kwh-{{ $chargeId }}">
                            @livewire('ace.input-kwh', ['chargeId' => $chargeId, 'edit' => $edit])
                        </div>

                        <div class="tab-pane fade" id="temptTapping" role="tabpanel"
                            wire:key="temptTapping-{{ $chargeId }}">
                            @livewire('ace.input-tempt-tapping', ['chargeId' => $chargeId, 'edit' => $edit])
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer border-top bg-light text-right">
                <small class="text-muted mr-auto">Please ensure all data is saved within each tab.</small>
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>