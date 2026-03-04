<div wire:ignore.self class="modal fade" id="modal-inoculant-input" tabindex="-1" role="dialog"
    aria-labelledby="modal-inoculant-input-label" aria-hidden="true" wire:key="modal-inoculant-input">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 95vw;">
        <div class="modal-content shadow-lg border-0">

            <div class="modal-header bg-light border-bottom py-3">
                <h5 class="modal-title h5 font-weight-bold text-dark mb-0" id="myExtraLargeModalLabel">
                    <i class="fas fa-flask text-primary mr-2"></i>Inoculant & Ladle Input
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
                                <div wire:ignore>
                                    <label class="text-muted small text-uppercase font-weight-bold mb-1 d-block">Furnace
                                        Source</label>
                                    <select
                                        class="form-control form-control-md select2-search-disable  @error('furnace') is-invalid @enderror"
                                        id="selectFurnace">
                                        <option value="">Select</option>
                                        @foreach ($furnaces as $furnace)
                                        <option value="{{ $furnace['id'] }}">{{ $furnace['furnace'] }}</option>
                                        @endforeach


                                    </select>
                                </div>
                                @error('furnace')
                                <small class="text-danger position-absolute">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <div wire:ignore>
                                    <label class="text-muted small text-uppercase font-weight-bold mb-1 d-block">Product
                                        Name</label>
                                    <select class="form-control form-control-md @error('product') is-invalid @enderror"
                                        id="product-select2">
                                        <option value="">Choose Product</option>
                                        @foreach ($products as $product)
                                        <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('product')
                                    <small class="text-danger position-absolute">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="text-muted small text-uppercase font-weight-bold mb-1 d-block">Lot
                                    No.</label>
                                <input
                                    class="form-control form-control-md font-weight-bold text-center  @error('lot') is-invalid @enderror"
                                    type="text" placeholder="00" wire:model='lot'>
                                @error('lot')
                                <small class="text-danger position-absolute">{{ $message }}</small>
                                @enderror
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
                                        <input class="form-control @error('moltTmpt') is-invalid @enderror"
                                            type="number" placeholder="0" wire:model='moltTmpt' min="0">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-light">°C</span>
                                        </div>
                                    </div>
                                    @error('moltTmpt')
                                    <small class="text-danger position-absolute">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="form-label font-weight-bold small text-muted">Berat Molten</label>
                                    <div class="input-group">
                                        <input
                                            class="form-control font-weight-bold @error('beratMolt') is-invalid @enderror"
                                            type="number" placeholder="0" wire:model='beratMolt' min="0">
                                        <div class="input-group-append">
                                            <span
                                                class="input-group-text bg-light text-primary font-weight-bold">KG</span>
                                        </div>
                                    </div>
                                    @error('beratMolt')
                                    <small class="text-danger position-absolute">{{ $message }}</small>
                                    @enderror
                                </div>

                                <label class="form-label font-weight-bold small text-muted text-uppercase mb-2">Status
                                    Proses</label>
                                <div class="bg-light rounded p-3">
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox"
                                            class="custom-control-input  @error('weighingStatus') is-invalid @enderror"
                                            id="timbangan" wire:model='weighingStatus'>
                                        <label class="custom-control-label text-dark" for="timbangan">Timbangan
                                            OK</label>
                                        @error('weighingStatus')
                                        <small class="text-danger position-absolute">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox"
                                            class="custom-control-input  @error('moltStatusConvy') is-invalid @enderror"
                                            id="conveyor" wire:model='moltStatusConvy'>
                                        <label class="custom-control-label text-dark" for="conveyor">Mat turun ke
                                            Conveyor</label>
                                        @error('moltStatusConvy')
                                        <small class="text-danger position-absolute">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox"
                                            class="custom-control-input  @error('moltLadleStatus') is-invalid @enderror"
                                            id="ladle" wire:model='moltLadleStatus'>
                                        <label class="custom-control-label text-dark" for="ladle">Mat turun ke
                                            Ladle</label>
                                        @error('moltLadleStatus')
                                        <small class="text-danger position-absolute">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                            class="custom-control-input  @error('treatmentStatus') is-invalid @enderror"
                                            id="treatment" wire:model='treatmentStatus'>
                                        <label class="custom-control-label text-dark" for="treatment">Treatment &gt; 10
                                            detik</label>
                                        @error('treatmentStatus')
                                        <small class="text-danger position-absolute">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card border shadow-none h-100 mb-0">
                            <div class="card-header bg-transparent border-bottom">
                                <h6 class="mb-0 font-weight-bold text-dark">Inoculant / Material Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-end g-3 mb-4">
                                    <div class="col-md-6">
                                        <div wire:ignore>
                                            <label class="form-label small font-weight-bold text-muted">Select
                                                Material</label>
                                            <select
                                                class="form-control select2-search-disable  @error('material') is-invalid @enderror"
                                                id="material" wire:model='material'>
                                                <option value="">Select Material</option>
                                                @foreach($inoculant as $item)
                                                <option value="{{ $item['material_code'] }}">{{ $item['material_name']
                                                    }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('material')
                                        <small class="text-danger position-absolute">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small font-weight-bold text-muted">Weight (KG)</label>
                                        <input
                                            class="form-control font-weight-bold  @error('weight') is-invalid @enderror"
                                            type="number" placeholder="0" wire:model='weight' min="0">
                                        @error('weight')
                                        <small class="text-danger position-absolute">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary btn-block shadow-sm" wire:click='addMaterial'>
                                            <i class="fas fa-plus mr-1"></i> Add
                                        </button>
                                    </div>
                                </div>

                                <div class="table-responsive border rounded overflow-hidden">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light text-muted small text-uppercase">
                                            <tr>
                                                <th class="px-3 border-0">Material Name</th>
                                                <th class="text-center border-0" style="width: 120px;">Weight</th>
                                                <th class="text-right px-3 border-0" style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($dataMat as $index => $mat )
                                            <tr>
                                                <td class="px-3 font-weight-medium">{{ $mat['material_name'] }}</td>
                                                <td class="text-center font-weight-bold text-primary">{{ $mat['weight']
                                                    }}
                                                    <small>kg</small>
                                                </td>
                                                <td class="text-right px-3">
                                                    <button
                                                        class="btn btn-outline-danger btn-sm rounded-circle waves-effect shadow-none"
                                                        wire:click='remove({{ $index }})'>
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty

                                            @endforelse

                                        </tbody>
                                    </table>
                                </div>
                                @error('dataMat')
                                <small class="text-danger d-block mt-2">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-white px-4 font-weight-bold text-muted"
                    data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-5 shadow font-weight-bold" wire:click='save()'>
                    <i class="fas fa-save mr-2"></i>Save
                </button>
            </div>
        </div>
    </div>
</div>