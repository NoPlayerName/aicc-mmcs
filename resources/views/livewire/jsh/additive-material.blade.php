<div>

    <div class="row align-items-end">
        <div class="col-md-3" wire:ignore>

            <label class="control-label">Material</label>
            <select class="form-control form-control-lg select2-search-disable" id="Additive-select2">
                <option>Select</option>
                <option value="carbon g-8">Carbon G-8</option>
                <option value="carbon sp">Carbon SP-500</option>
                <option value="fe">Fe.Si</option>
            </select>
            <div style="height: 20px;"></div>
        </div>
        <div class="col-md-3" wire:ignore>
            <label class="control-label">Type Adjustment</label>
            <select class="form-control form-control-lg select2-search-disable" id="Type-Adjust-select2">
                <option>Select</option>
                <option value="1">Pra Adjust</option>
                <option value="2">Adjustment</option>
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
        <div class="col-md-auto">
            <button class="btn btn-lg btn-primary" data-toggle="tooltip" title="Add Material" wire:click='addMat'> <i
                    class="fas fa-plus"></i>
                Add</button>
        </div>
    </div>
    <div class="row">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Material Name</th>
                    <th>Type Adjustment</th>
                    <th>Weight (KG)</th>
                    <th style="width: 150px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($dataAdditiveMat != [])


                @foreach ($dataAdditiveMat as $index => $aW )
                <tr>
                    <th>{{ $aW['material_id'] }}</th>
                    <th>{{ $aW['type_additive_text'] }}</th>
                    <td>{{ $aW['weight'] }}</td>
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
                    <td colspan="4" class="text-center text-muted">
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