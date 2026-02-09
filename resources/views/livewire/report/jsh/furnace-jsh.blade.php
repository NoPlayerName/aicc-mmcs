@push('style')
<style>
    .table-responsive {
        max-height: 600px;
        overflow-y: auto;
    }

    .sticky-col {
        position: sticky;
        left: 0;
        background-color: white !important;
        z-index: 1;
    }
</style>
@endpush

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">JSH Melting Furnace Report</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Report</a></li>
                            <li class="breadcrumb-item active">JSH Melting</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header mt-0">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Select Date Range</label>
                                    <div class="input-daterange input-group" id="date-range-picker"
                                        data-provide="datepicker" data-date-format="dd/mm/yyyy"
                                        data-date-autoclose="true">
                                        <input type="text" class="form-control" id="start-date" wire:model="startDate"
                                            autocomplete="off" placeholder="Start Date" />
                                        <input type="text" class="form-control" id="end-date" wire:model="endDate"
                                            autocomplete="off" placeholder="End Date" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Select Furnace</label>
                                    <select class="form-control" id="furnace" wire:model.live="furnace">
                                        <option value="">select</option>
                                        @foreach ($furnaceSelect as $fc )
                                        <option value="{{ $fc->value }}">{{ $fc->text }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Select Shift</label>
                                    <select class="form-control" id="shift" wire:model.live="shift">
                                        <option value="">select</option>
                                        <option value="D">D</option>
                                        <option value="S">S</option>
                                        <option value="N">N</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-auto">
                                <button class="btn btn-md btn-primary" wire:click='search' wire:loading.attr="disabled">
                                    <i class="fas fa-search" wire:loading.remove></i>
                                    <span class="spinner-border spinner-border-sm" wire:loading></span>
                                    Search
                                </button>
                                <div style="height: 15px;"></div>
                            </div>

                            <div class="col-md-auto">
                                <button class="btn btn-md btn-success" wire:click='exportExcel' @if(!$hasSearched)
                                    disabled @endif>
                                    <i class="far fa-file-excel"></i> Export
                                </button>
                                <div style="height: 15px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab == 'raw-material' ? 'active' : '' }}" data-toggle="tab"
                                    href="#rawMaterial" role="tab"
                                    wire:click.prevent="$set('activeTab', 'raw-material')">
                                    <span class="d-none d-sm-block">
                                        <p>Raw Material</p>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab == 'additive' ? 'active' : '' }}" data-toggle="tab"
                                    href="#additive" role="tab" wire:click.prevent="$set('activeTab', 'additive')">
                                    <span class="d-none d-sm-block">
                                        <p>Additive</p>
                                    </span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content p-3 text-muted">
                            @if($hasSearched)
                            <div class="tab-pane active" id="rawMaterial" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered mb-0">
                                        <thead class="table-dark text-center">
                                            <tr>
                                                <th rowspan="2" class="align-middle ">Material Name
                                                </th>
                                                <th colspan="{{ count($dateRange) }}">Actual Usage (Kg)</th>
                                                <th rowspan="2" class="align-middle bg-primary">Subtotal</th>
                                            </tr>
                                            <tr>
                                                @foreach($dateRange as $date)
                                                <th style="min-width: 80px;">{{
                                                    \Carbon\Carbon::parse($date)->format('d/m') }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($data as $row)
                                            <tr>
                                                <td class="fw-bold sticky-col">
                                                    {{ $row->material_name }} <br>
                                                    <small class="text-muted">{{ $row->material_id }}</small>
                                                </td>
                                                @foreach($dateRange as $date)
                                                @php $alias = 'date_' . str_replace('-', '_', $date); @endphp
                                                <td class="text-center">
                                                    {{ isset($row->$alias) && $row->$alias > 0 ?
                                                    number_format($row->$alias, 0, ',', '.') : '-' }}
                                                </td>
                                                @endforeach
                                                <td class="text-center fw-bold bg-light text-primary">
                                                    {{ number_format($row->subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="{{ count($dateRange) + 2 }}" class="text-center py-4">
                                                    Data
                                                    tidak ditemukan.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        @if(!empty($data) && count($data) > 0)
                                        <tfoot class="bg-light fw-bold">
                                            <tr>
                                                <td class="text-center sticky-col">GRAND TOTAL</td>
                                                @foreach($dateRange as $date)
                                                @php $alias = 'date_' . str_replace('-', '_', $date); @endphp
                                                <td class="text-center">{{
                                                    number_format(collect($data)->sum($alias), 0,
                                                    ',', '.') }}</td>
                                                @endforeach
                                                <td class="text-center bg-primary text-white">
                                                    {{ number_format(collect($data)->sum('subtotal'), 0, ',', '.')
                                                    }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                <h5>Silakan pilih rentang tanggal dan klik Search</h5>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- form modal --}}
{{-- @livewire('jsh.form-material-input')
@livewire('jsh.detail-charging')
</div> --}}
@push('scripts')
<script>
    $(document).on('livewire:navigated', () => {
        $('#date-range-picker').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true
        }).on('changeDate', function (e) {
            let startDate = $('#start-date').val();
            let endDate = $('#end-date').val();
        
            Livewire.dispatch('Date', {startDate: startDate, endDate: endDate});
        });
         $('#shift').select2({
            minimumResultsForSearch: Infinity
        }).on('change', function (e) {
            let Data = $(this).val()
            Livewire.dispatch('Shift', {data: Data});
        });
         $('#furnace').select2({
            minimumResultsForSearch: Infinity
        }).on('change', function (e) {
            let Data = $(this).val()
            Livewire.dispatch('Furnace', {data: Data});
        });
       
    })
</script>
@endpush