<div>
    <div class="row align-items-end">
        <div class="col-md-3" wire:ignore>
            <label class="control-label">Material</label>
            <select class="form-control form-control-lg select2-search-disable" id="rawMat-select2">
                <option>Select</option>
                @foreach ($rawMatSelect as $rw)
                <option value="{{ $rw['material_code'] }}">{{ $rw['material_name'] }}</option>
                @endforeach

            </select>
            <div style="height: 20px;"></div>
        </div>

        <div class="col-md-3">
            <label class="control-label">Weight (KG)</label>
            <input class="form-control form-control-lg @error('weight') is-invalid @enderror" type="text"
                placeholder="Kg" wire:model="weight">
            @error('weight')
            <small class="text-danger d-block">{{ $message }}</small>
            @else
            <div style="height: 20px;"></div> @enderror
        </div>

        <div class="col-md-2">
            <label class="control-label">Total Material</label>
            <input class="form-control form-control-lg" type="text" readonly wire:model='totalWeight'>
            <div style="height: 20px;"></div>
        </div>

        <div class="col-md-auto py-4"> <button class="btn btn-lg btn-primary" wire:click='addRawMat'>
                <i class="fas fa-plus"></i> Add
            </button>
        </div>
    </div>
    <div class="row">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Material Name</th>
                    <th>Weight (KG)</th>
                    <th style="width: 150px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($dataRawMat != [])


                @foreach ($dataRawMat as $index => $rW )
                <tr>
                    <th>{{ $rW['material_name'] }}</th>
                    <td>{{ $rW['weight'] }}</td>
                    <td>
                        <div>
                            <button class="btn btn-danger btn-sm waves-effect" wire:click='remove({{ $index }})'>
                                <i class=" fas fa-trash-alt" data-toggle="tooltip" title="delete"></i>
                            </button>

                        </div>

                    </td>
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
    <div class="row mt-3">
        <div class="col d-flex justify-content-end">
            <button type="button" class="btn btn-primary btn-lg" wire:click='save'>
                <i class="mdi mdi-content-save"></i> Save
            </button>
        </div>
    </div>
</div>