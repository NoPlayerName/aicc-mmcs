<div wire:ignore.self class="modal fade bs-example-modal-xl" id="modal-detail-charging" tabindex="-1" role="dialog"
    aria-labelledby="modal-detail-charging-label" aria-hidden="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 95vw;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">Detail Material Input Furnace</h5>
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
                                    <ul wire:ignore.self class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab"
                                                href="#rawMaterialDetail-{{ $chargeId }}" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                <span class="d-none d-sm-block">
                                                    <h5>
                                                        Raw Material </h5>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#additiveDetail-{{ $chargeId }}"
                                                role="tab">
                                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                                <span class="d-none d-sm-block">
                                                    <h5>
                                                        Additive
                                                    </h5>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kwhDetail-{{ $chargeId }}"
                                                role="tab">
                                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                                <span class="d-none d-sm-block">
                                                    <h5>
                                                        KWH
                                                    </h5>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab"
                                                href="#temptTappingDetail-{{ $chargeId }}" role="tab">
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
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane show active" id="rawMaterialDetail-{{ $chargeId }}"
                                            role="tabpanel">
                                            <table class="table table-striped mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Material Name</th>
                                                        <th>Weight (KG)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($rawMaterial != [])


                                                    @foreach ($rawMaterial as $index => $rW )
                                                    <tr>
                                                        <th>{{ $rW->material_id }}</th>
                                                        <td>{{ $rW->weight }}</td>

                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">
                                                            Data kosong
                                                        </td>
                                                    </tr>
                                                    @endif

                                                </tbody>
                                                @if ($rawMaterial != [])
                                                <tfoot>
                                                    <tr class="table-secondary" style="font-weight: bold;">
                                                        <td colspan="1" class="text-end">TOTAL</td>
                                                        <td>{{ number_format(collect($rawMaterial)->sum('weight'), 0,
                                                            ',',
                                                            '.') }} KG</td>
                                                    </tr>
                                                </tfoot>
                                                @endif
                                            </table>

                                        </div>
                                        <div class="tab-pane fade" id="additiveDetail-{{ $chargeId }}" role="tabpanel">
                                            <table class="table table-striped mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Material Name</th>
                                                        <th>Type Adjustment</th>
                                                        <th>Weight (KG)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($additive != [])


                                                    @foreach ($additive as $index => $aW )
                                                    <tr>
                                                        <th>{{ $aW->material_id }}</th>
                                                        <th>{{ $aW->type_additive_text }}</th>
                                                        <td>{{ $aW->weight }}</td>

                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">
                                                            Data kosong
                                                        </td>
                                                    </tr>
                                                    @endif

                                                </tbody>
                                                @if ($additive != [])
                                                <tfoot>
                                                    <tr class="table-secondary" style="font-weight: bold;">
                                                        <td colspan="2" class="text-end">TOTAL</td>
                                                        <td>{{ number_format(collect($additive)->sum('weight'), 0, ',',
                                                            '.') }} KG</td>
                                                    </tr>
                                                </tfoot>
                                                @endif
                                            </table>
                                        </div>
                                        <div class="tab-pane" id="kwhDetail-{{ $chargeId }}" role="tabpanel">
                                            <table class="table table-striped mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Charging Time</th>
                                                        <th>KWH Start Charge</th>
                                                        <th>KWH Start OK Charge</th>
                                                        <th>Power</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($kwh != null)


                                                    @foreach ($kwh as $index => $kw )
                                                    <tr>
                                                        <th>{{ $kw->charge_time }}</th>
                                                        <td>{{ $kw->kwh_start_charge }}</td>
                                                        <td>{{ $kw->kwh_ok_charge }}</td>
                                                        <td>{{ $kw->power }}</td>

                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">
                                                            Data kosong
                                                        </td>
                                                    </tr>
                                                    @endif

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane" id="temptTappingDetail-{{ $chargeId }}" role="tabpanel">
                                            <table class="table table-striped mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Temperature</th>
                                                        <th>Type Tapping</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($tapping != null)


                                                    @foreach ($tapping as $index => $rW )
                                                    <tr>
                                                        <th>{{ $rW->temperatur }}</th>
                                                        <td>{{ $rW->type_tapping_text }}</td>

                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">
                                                            Data kosong
                                                        </td>
                                                    </tr>
                                                    @endif

                                                </tbody>
                                            </table>

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