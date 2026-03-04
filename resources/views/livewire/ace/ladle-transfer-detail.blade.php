<div wire:ignore.self class="modal fade" id="modal-ladle-transfer-detail" tabindex="-1" role="dialog"
    aria-labelledby="modal-ladle-transfer-detail-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 95vw;">
        <div class="modal-content shadow-lg border-0">

            <div class="modal-header bg-light border-bottom py-3">
                <h5 class="modal-title h5 font-weight-bold text-dark mb-0" id="modal-ladle-transfer-detail-label">
                    <i class="fas fa-flask text-primary mr-2"></i>Detail Inoculant & Ladle Input
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4">
                <div class="card border-0 bg-soft-primary mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-end g-3">
                            <div class="col-md-3">
                                <label class="text-muted small text-uppercase font-weight-bold mb-1 d-block">Furnace
                                    Source</label>
                                <input class="form-control form-control-md" type="text" value="{{ $furnace }}" readonly>
                            </div>
                            <div class="col-md-5">
                                <label class="text-muted small text-uppercase font-weight-bold mb-1 d-block">Product
                                    Name</label>
                                <input class="form-control form-control-md" type="text" value="{{ $product }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="text-muted small text-uppercase font-weight-bold mb-1 d-block">Lot
                                    No.</label>
                                <input class="form-control form-control-md font-weight-bold text-center" type="text"
                                    value="{{ $lot }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border shadow-none h-100 mb-0">
                            <div class="card-header bg-transparent border-bottom">
                                <h6 class="mb-0 font-weight-bold text-dark">Process Parameters</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold small text-muted">Molt Temp on
                                        Ladle</label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" value="{{ $moltTmpt }}" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-light">°C</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label font-weight-bold small text-muted">Berat Molten</label>
                                    <div class="input-group">
                                        <input class="form-control font-weight-bold" type="number"
                                            value="{{ $beratMolt }}" readonly>
                                        <div class="input-group-append">
                                            <span
                                                class="input-group-text bg-light text-primary font-weight-bold">KG</span>
                                        </div>
                                    </div>
                                </div>

                                <label class="form-label font-weight-bold small text-muted text-uppercase mb-2">Status
                                    Proses</label>
                                <div class="bg-light rounded p-3">
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="detail-timbangan"
                                            @checked($weighingStatus) disabled>
                                        <label class="custom-control-label text-dark" for="detail-timbangan">Timbangan
                                            OK</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="detail-conveyor"
                                            @checked($moltStatusConvy) disabled>
                                        <label class="custom-control-label text-dark" for="detail-conveyor">Mat turun ke
                                            Conveyor</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="detail-ladle"
                                            @checked($moltLadleStatus) disabled>
                                        <label class="custom-control-label text-dark" for="detail-ladle">Mat turun ke
                                            Ladle</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="detail-treatment"
                                            @checked($treatmentStatus) disabled>
                                        <label class="custom-control-label text-dark" for="detail-treatment">Treatment
                                            &gt; 10 detik</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card border shadow-none h-100 mb-0">
                            <div
                                class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
                                <h6 class="mb-0 font-weight-bold text-dark">Inoculant / Material Details</h6>
                                <span class="badge badge-soft-dark">{{ count($dataMat) }} Item</span>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive border rounded overflow-hidden">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light text-muted small text-uppercase">
                                            <tr>
                                                <th class="px-3 border-0">Material Name</th>
                                                <th class="text-center border-0" style="width: 140px;">Weight</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($dataMat as $mat)
                                            <tr>
                                                <td class="px-3 font-weight-medium">{{ $mat['material_name'] }}</td>
                                                <td class="text-center font-weight-bold text-primary">{{ $mat['weight']
                                                    }} <small>kg</small>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="2" class="text-center text-muted py-4">No material details.
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
            </div>

            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-white px-4 font-weight-bold text-muted"
                    data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>