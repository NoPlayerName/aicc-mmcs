<div class="p-2">
    <div class="row g-4">
        <div class="col-md-3">
            <label class="form-label font-weight-bold text-muted">Charging Time</label>
            <div class="input-group input-group-lg">
                <input type="number" class="form-control @error('form.charge_time') is-invalid @enderror"
                    placeholder="0" wire:model='form.charge_time'>
                <div class="input-group-append">
                    <span class="input-group-text bg-light small font-weight-bold">Min</span>
                </div>
            </div>
            @error('form.charge_time')
            <small class="text-danger position-absolute">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label font-weight-bold text-muted">KWH Start Charge</label>
            <div class="input-group input-group-lg">
                <input type="number" class="form-control @error('form.kwh_start_charge') is-invalid @enderror"
                    placeholder="0.0" wire:model='form.kwh_start_charge'>
                <div class="input-group-append">
                    <span class="input-group-text bg-light small font-weight-bold">kWh</span>
                </div>
            </div>
            @error('form.kwh_start_charge')
            <small class="text-danger position-absolute">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label font-weight-bold text-muted">KWH Ok Charge</label>
            <div class="input-group input-group-lg">
                <input type="number" class="form-control @error('form.kwh_ok_charge') is-invalid @enderror"
                    placeholder="0.0" wire:model='form.kwh_ok_charge'>
                <div class="input-group-append">
                    <span class="input-group-text bg-light small font-weight-bold">kWh</span>
                </div>
            </div>
            @error('form.kwh_ok_charge')
            <small class="text-danger position-absolute">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label font-weight-bold text-muted">Power</label>
            <div class="input-group input-group-lg">
                <input type="number" class="form-control @error('form.power') is-invalid @enderror" placeholder="0"
                    wire:model='form.power'>
                <div class="input-group-append">
                    <span class="input-group-text bg-light small font-weight-bold">kW</span>
                </div>
            </div>
            @error('form.power')
            <small class="text-danger position-absolute">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-soft-info d-flex align-items-center mb-0" role="alert">
                <i class="fas fa-info-circle mr-2"></i>
                <small>Input nilai KWH berdasarkan pembacaan meter panel furnace untuk perhitungan konsumsi energi
                    per-charging.</small>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-end">
            <button type="button" class="btn btn-success btn-lg px-5 shadow font-weight-bold" wire:click='save'
                wire:loading.attr="disabled">
                <i class="fas fa-save mr-2" wire:loading.remove wire:target="save"></i>
                <span class="spinner-border spinner-border-sm mr-2" wire:loading wire:target="save"></span>
                Save
            </button>
        </div>
    </div>
</div>