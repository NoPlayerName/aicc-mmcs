<div wire:ignore.self class="modal fade" id="modal-detail-charging" tabindex="-1" role="dialog"
    aria-labelledby="modal-detail-charging-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 95vw;">
        <div class="modal-content shadow-lg border-0">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title h5 font-weight-bold" id="myExtraLargeModalLabel">
                    <i class="fas fa-file-alt mr-2"></i>Detail Material Input Furnace
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-0">
                <div class="bg-light border-bottom px-4 py-3">
                    <div class="row align-items-center font-weight-bold">
                        <div class="col-md-3">
                            <small class="text-muted d-block font-weight-normal">Lot Number</small>
                            <span class="h5 mb-0">{{ $lot }}</span>
                        </div>
                        <div class="col-md-3 border-left">
                            <small class="text-muted d-block font-weight-normal">Charging</small>
                            @if($charging != '-')
                            <span class="h5 mb-0 text-primary">{{ $charging }}</span>
                            @else
                            <div class="d-flex align-items-center">
                                <input type="text" class="form-control form-control-sm mr-2" style="width: 80px"
                                    wire:model='charging'>
                                <button class="btn btn-sm btn-primary" wire:click='saveCharge'><i
                                        class="fas fa-check"></i></button>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6 border-left text-right text-md-left pl-md-4">
                            <small class="text-muted d-block font-weight-normal">Product Type</small>
                            <span class="h5 mb-0 text-dark">{{ $product }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#rawMaterialDetail-{{ $chargeId }}"
                                role="tab">
                                <span class="font-weight-bold"><i class="fas fa-layer-group mr-1"></i> Raw
                                    Material</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#additiveDetail-{{ $chargeId }}" role="tab">
                                <span class="font-weight-bold"><i class="fas fa-flask mr-1"></i> Additive</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#kwhDetail-{{ $chargeId }}" role="tab">
                                <span class="font-weight-bold"><i class="fas fa-bolt mr-1"></i> Energy (KWH)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#temptTappingDetail-{{ $chargeId }}" role="tab">
                                <span class="font-weight-bold"><i class="fas fa-thermometer-half mr-1"></i>
                                    Tapping</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content pt-3">

                        <div class="tab-pane show active fade" id="rawMaterialDetail-{{ $chargeId }}" role="tabpanel">
                            <div class="table-responsive border rounded bg-white">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Material Name</th>
                                            <th class="text-right px-4" style="width: 250px;">Weight (KG)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($rawMaterial as $rW)
                                        <tr>
                                            <td class="font-weight-medium">{{ $rW->material_name }}</td>
                                            <td class="text-right px-4 font-weight-bold text-primary">{{
                                                number_format($rW->weight, 0, ',', '.') }} kg</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-muted small italic">Data kosong
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    @if (count($rawMaterial) > 0)
                                    <tfoot class="bg-soft-primary">
                                        <tr class="font-weight-bold">
                                            <td class="text-right">TOTAL RAW MATERIAL</td>
                                            <td class="text-right px-4 h5 mb-0">{{
                                                number_format(collect($rawMaterial)->sum('weight'), 0, ',', '.') }} KG
                                            </td>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="additiveDetail-{{ $chargeId }}" role="tabpanel">
                            <div class="table-responsive border rounded bg-white">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light text-dark">
                                        <tr>
                                            <th>Material Name</th>
                                            <th class="text-center">Type Adjustment</th>
                                            <th class="text-right px-4">Weight (KG)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($additive as $aW)
                                        <tr>
                                            <td class="font-weight-medium">{{ $aW->material_name }}</td>
                                            <td class="text-center"><span class="badge badge-soft-info">{{
                                                    $aW->type_additive_text }}</span></td>
                                            <td class="text-right px-4 font-weight-bold text-info">{{
                                                number_format($aW->weight, 0, ',', '.') }} kg</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">Data kosong</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    @if (count($additive) > 0)
                                    <tfoot class="bg-soft-info">
                                        <tr class="font-weight-bold">
                                            <td colspan="2" class="text-right">TOTAL ADDITIVE</td>
                                            <td class="text-right px-4">{{
                                                number_format(collect($additive)->sum('weight'), 0, ',', '.') }} KG</td>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="kwhDetail-{{ $chargeId }}" role="tabpanel">
                            <div class="table-responsive border rounded bg-white shadow-sm">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr class="text-muted small text-uppercase">
                                            <th>Charging Time</th>
                                            <th class="text-center">KWH Start Charge</th>
                                            <th class="text-center">KWH OK Charge</th>
                                            <th class="text-center">Power</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-dark font-weight-bold">
                                        @forelse ($kwh as $kw)
                                        <tr>
                                            <td>{{ $kw->charge_time }} <small class="text-muted">Min</small></td>
                                            <td class="text-center">{{ number_format($kw->kwh_start_charge, 0, ',', '.')
                                                }} <small>kWh</small></td>
                                            <td class="text-center text-success">{{ number_format($kw->kwh_ok_charge, 0,
                                                ',', '.') }} <small>kWh</small></td>
                                            <td class="text-center text-primary">{{ number_format($kw->power, 0, ',',
                                                '.') }} <small>kW</small></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted small">Data KWH tidak
                                                ditemukan</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="temptTappingDetail-{{ $chargeId }}" role="tabpanel">
                            <div class="table-responsive border rounded bg-white">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Temperature</th>
                                            <th class="text-right px-4">Type Tapping</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tapping as $t)
                                        <tr>
                                            <td class="h5 mb-0 text-danger font-weight-bold">
                                                {{ $t->temperatur }} <small class="text-muted">°C</small>
                                            </td>
                                            <td class="text-right px-4">
                                                <span class="badge badge-outline-dark">{{ $t->type_tapping_text
                                                    }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-muted">Belum ada data tapping
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary px-4 shadow-sm" data-dismiss="modal">Close
                    Detail</button>
            </div>

        </div>
    </div>
</div>