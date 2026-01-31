<div>
    <div class="row align-items-end">
        <div class="col-md-3">
            <label class="control-lable">Temperatur</label>
            <input class="form-control form-control-lg @error('temperature') is-invalid @enderror" type="text"
                wire:model='temperature'>
            @error('temperature')
            <small class="text-danger d-block">{{ $message }}</small>
            @else
            <div style="height: 20px;"></div> @enderror
        </div>
        <div class="col-md-3">
            <div>
                <label class="control-label">Type Tapping</label>
                <select class="form-control form-control-lg select2-search-disable" id="Type-Tapping-select2">
                    <option>Select</option>
                    @foreach ($selectType as $st)
                    <option value="{{ $st->value }}">{{ $st->text }}</option>
                    @endforeach
                </select>
            </div>
            @error('typeTapping')
            <small class="text-danger d-block">{{ $message }}</small>
            @else
            <div style="height: 20px;"></div> @enderror
        </div>
        <div class="col-md-auto">
            <button class="btn btn-lg btn-primary" data-toggle="tooltip" title="Add Material" wire:click='addTapping'>
                <i class="fas fa-plus"></i> Add</button>
            <div style="height: 20px;"></div>
        </div>


    </div>
    <div class="row">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Temperature</th>
                    <th>Type Tapping</th>
                    <th style="width: 150px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($dataTapping != [])


                @foreach ($dataTapping as $index => $rW )
                <tr>
                    <th>{{ $rW['temperatur'] }}</th>
                    <td>{{ $rW['typeTappingText'] }}</td>
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