<div wire:ignore.self class="modal fade bs-example-modal-xl" id="modal-material-input" tabindex="-1" role="dialog"
    aria-labelledby="modal-material-input-label" aria-hidden="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 95vw;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">Material Input Furnace</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="false">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- <form wire:submit.prevent="save"> --}}
                    <div class="">
                        {{-- <div class="row"> --}}
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <h5>
                                                Lot: {{ $lot }}
                                            </h5>
                                            <div class="row align-items-center">
                                                @if($charging != '-')
                                                <div class="col-auto">
                                                    <h5 class="mb-0">Charging: {{ $charging }}</h5>
                                                </div>
                                                @else

                                                <div class="col-auto">
                                                    <h5 class="mb-0">Charging:</h5>
                                                </div>

                                                <div class="col-auto">

                                                    <input type="text" class="form-control form-control-sm"
                                                        style="width: 4rem" wire:model='charging'
                                                        wire:change='saveCharge'>
                                                </div>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <h5>
                                                Product:{{ $product }}
                                            </h5>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Nav tabs -->
                                    <ul wire:ignore class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#rawMaterial" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                <span class="d-none d-sm-block">
                                                    <h5>
                                                        Raw Material </h5>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#additive" role="tab">
                                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                                <span class="d-none d-sm-block">
                                                    <h5>
                                                        Additive
                                                    </h5>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kwh" role="tab">
                                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                                <span class="d-none d-sm-block">
                                                    <h5>
                                                        KWH
                                                    </h5>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#temptTapping" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                                <span class="d-none d-sm-block">
                                                    <h5>
                                                        Tempt Tapping
                                                    </h5>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div wire:ignore class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="rawMaterial" role="tabpanel"
                                            wire:key="rawMat-{{ $chargeId }}">
                                            @livewire('jsh.raw-material', ['chargeId' => $chargeId, 'edit' => $edit])

                                        </div>
                                        <div class="tab-pane" id="additive" role="tabpanel"
                                            wire:key="Additive-{{ $chargeId }}">
                                            @livewire('jsh.additive-material', ['chargeId' => $chargeId])
                                        </div>
                                        <div class="tab-pane" id="kwh" role="tabpanel" wire:key="Kwh-{{ $chargeId }}">
                                            @livewire('jsh.input-kwh', ['chargeId' => $chargeId, 'edit' => $edit])
                                        </div>
                                        <div class="tab-pane" id="temptTapping" role="tabpanel"
                                            wire:key="temptTapping-{{ $chargeId }}">
                                            @livewire('jsh.input-tempt-tapping', ['chargeId' => $chargeId, 'edit'
                                            => $edit])
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--
                        </div> --}}
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    {{--
                </form> --}}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->