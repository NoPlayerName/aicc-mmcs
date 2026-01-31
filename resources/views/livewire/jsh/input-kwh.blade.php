<div>
    <div class="row align-items-end">
        <div class="col-md-3">
            <label class="control-lable">Charging Time</label>
            <input class="form-control form-control-lg @error('form.charge_time') is-invalid @enderror" type="text"
                wire:model='form.charge_time'>
            @error('form.charge_time')
            <small class="text-danger d-block">{{ $message }}</small>
            @else
            <div style="height: 20px;"></div> @enderror
        </div>
        <div class="col-md-3">
            <label class="control-lable">KWH Start Charge</label>
            <input class="form-control form-control-lg @error('form.kwh_start_charge') is-invalid @enderror" type="text"
                wire:model='form.kwh_start_charge'>
            @error('form.kwh_start_charge')
            <small class="text-danger d-block">{{ $message }}</small>
            @else
            <div style="height: 20px;"></div> @enderror
        </div>
        <div class="col-md-3">
            <label class="control-lable">KWH Ok Charge</label>
            <input class="form-control form-control-lg @error('form.kwh_ok_charge') is-invalid @enderror" type="text"
                wire:model='form.kwh_ok_charge'>
            @error('form.kwh_ok_charge')
            <small class="text-danger d-block">{{ $message }}</small>
            @else
            <div style="height: 20px;"></div> @enderror
        </div>
        <div class="col-md-3">
            <label class="control-lable">Power</label>
            <input class="form-control form-control-lg @error('form.power') is-invalid @enderror" type="text"
                wire:model='form.power'>
            @error('form.power')
            <small class="text-danger d-block">{{ $message }}</small>
            @else
            <div style="height: 20px;"></div> @enderror
        </div>

    </div>
    <div class="row mt-3">
        <div class="col d-flex justify-content-end">
            <button type="button" class="btn btn-primary btn-lg" wire:click='save'>
                <i class="mdi mdi-content-save"></i> Save
            </button>
        </div>
    </div>
</div>