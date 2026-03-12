<div class="p-2">
    <div class="row align-items-end g-3 mb-4">

        <div class="col-md-4">
            <label class="form-label font-weight-bold">Temperatur</label>
            <div class="input-group input-group-lg">
                <input type="number" class="form-control @error('temperatur') is-invalid @enderror" placeholder="1000"
                    min="0" wire:model='temperatur'>
                <div class="input-group-append">
                    <span class="input-group-text bg-light text-danger font-weight-bold">°C</span>
                </div>
            </div>
            @error('temperatur')
            <small class="text-danger position-absolute">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-md-5">
            <label class="form-label font-weight-bold">Type Tapping</label>
            <div wire:ignore>
                <select class="form-control form-control-lg select2-search-disable" id="Type-Tapping-select2">
                    <option value="">Select Tapping Type</option>
                    @foreach ($selectType as $st)
                    <option value="{{ $st->value }}">{{ $st->text }}</option>
                    @endforeach
                </select>
            </div>
            @error('typeTapping')
            <small class="text-danger position-absolute">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-md-3">
            <button class="btn btn-lg btn-primary btn-block shadow-sm" wire:click='addTapping'
                wire:loading.attr="disabled">
                <i class="fas fa-plus mr-1" wire:loading.remove wire:target="addTapping"></i>
                <span class="spinner-border spinner-border-sm mr-1" role="status" wire:loading
                    wire:target="addTapping"></span>
                Add Record
            </button>
        </div>
    </div>

    <div class="card border rounded shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover table-centered mb-0 mt-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3" style="width: 40%;">Temperatur</th>
                        <th style="width: 40%;">Type Tapping</th>
                        <th class="text-center" style="width: 20%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataTapping as $index => $rW)
                    <tr>
                        <td class="px-4">
                            <span class="h5 mb-0 font-weight-bold text-danger">{{ $rW['temperatur'] }}</span>
                            <small class="text-muted">°C</small>
                        </td>
                        <td>
                            <span class="badge badge-soft-dark px-3 py-2 font-size-13">
                                {{ $rW['type_tapping_text'] }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-outline-danger btn-sm rounded-circle waves-effect"
                                wire:click='remove({{ $index }})' data-toggle="tooltip" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted bg-white">
                            <i class="fas fa-thermometer-empty fa-3x mb-3 d-block text-light"></i>
                            Belum ada data temperatur tapping
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-end align-items-center bg-light p-3 rounded border">
            <button type="button" class="btn btn-success btn-lg px-5 shadow font-weight-bold" wire:click='save'
                wire:loading.attr="disabled">
                <i class="fas fa-save mr-2" wire:loading.remove wire:target="save"></i>
                <span class="spinner-border spinner-border-sm mr-2" wire:loading wire:target="save"></span>
                Save
            </button>
        </div>
    </div>
</div>