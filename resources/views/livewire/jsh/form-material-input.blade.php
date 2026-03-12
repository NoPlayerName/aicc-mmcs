<div wire:ignore.self class="modal fade" id="modal-material-input" tabindex="-1" role="dialog"
    aria-labelledby="modal-material-input-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 95vw;">
        <div class="modal-content shadow-lg">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title h5" id="myExtraLargeModalLabel">
                    <i class="fas fa-industry  mr-2"></i>Material Input Furnace
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-0">
                <div class="card border-0 mb-0">

                    <div class="card-header bg-light border-bottom">
                        <div class="row align-items-center">

                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted mr-2">Charging:</span>
                                    @if($charging != '-')
                                    <span class="badge badge-soft-primary p-2 font-size-14">{{ $charging }}</span>
                                    @else
                                    <div class="input-group input-group-sm" style="width: 120px;">
                                        <input type="text" class="form-control" wire:model='charging'
                                            wire:change='saveCharge'>
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-edit"></i></span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted mr-2">Lot No:</span>
                                    <span class="font-weight-bold text-dark">{{ $lot }}</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted mr-2">Product:</span>
                                    <span class="font-weight-bold text-dark text-truncate">{{ $product }}</span>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="card-body p-4">
                        <ul wire:ignore class="nav nav-pills nav-justified bg-light rounded p-1 mb-4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#rawMaterial" role="tab">
                                    <i class="fas fa-cubes mr-1"></i> Raw Material
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#additive" role="tab">
                                    <i class="fas fa-flask mr-1"></i> Additive
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kwh" role="tab">
                                    <i class="fas fa-bolt mr-1"></i> KWH
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#temptTapping" role="tab">
                                    <i class="fas fa-temperature-high mr-1"></i> Temp Tapping
                                </a>
                            </li>
                        </ul>

                        <div wire:ignore class="tab-content pt-2">

                            <div class="tab-pane active" id="rawMaterial" role="tabpanel"
                                wire:key="rawMat-{{ $chargeId }}">
                                <div class="border rounded p-3 bg-white shadow-sm">
                                    @livewire('jsh.raw-material', ['chargeId' => $chargeId, 'edit' => $edit])
                                </div>
                            </div>

                            <div class="tab-pane" id="additive" role="tabpanel" wire:key="Additive-{{ $chargeId }}">
                                <div class="border rounded p-3 bg-white shadow-sm">
                                    @livewire('jsh.additive-material', ['chargeId' => $chargeId])
                                </div>
                            </div>

                            <div class="tab-pane" id="kwh" role="tabpanel" wire:key="Kwh-{{ $chargeId }}">
                                <div class="border rounded p-3 bg-white shadow-sm">
                                    @livewire('jsh.input-kwh', ['chargeId' => $chargeId, 'edit' => $edit])
                                </div>
                            </div>

                            <div class="tab-pane" id="temptTapping" role="tabpanel"
                                wire:key="temptTapping-{{ $chargeId }}">
                                <div class="border rounded p-3 bg-white shadow-sm">
                                    @livewire('jsh.input-tempt-tapping', ['chargeId' => $chargeId, 'edit' => $edit])
                                </div>
                            </div>

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