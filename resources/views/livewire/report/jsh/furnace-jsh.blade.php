@push('style')
<style>
    .table-responsive {
        max-height: 600px;
        overflow-y: auto;
        overflow-x: auto;
    }

    .sticky-col {
        position: sticky;
        left: 0;
        background-color: white !important;
        z-index: 1;
        min-width: 180px;
    }

    .sticky-col-charging {
        position: sticky;
        left: 180px;
        background-color: white !important;
        z-index: 1;
        min-width: 90px;
    }

    .sticky-col-lot {
        position: sticky;
        left: 270px;
        background-color: white !important;
        z-index: 1;
        min-width: 120px;
    }

    .sticky-col-head {
        position: sticky;
        left: 0;
        background-color: rgb(37, 37, 37) !important;
        z-index: 1;
    }

    .sticky-col-head-charging {
        position: sticky;
        left: 180px;
        background-color: rgb(37, 37, 37) !important;
        z-index: 1;
    }

    .sticky-col-head-lot {
        position: sticky;
        left: 270px;
        background-color: rgb(37, 37, 37) !important;
        z-index: 1;
    }

    .sticky-col-kwh-charging,
    .sticky-col-kwh-charging-head {
        position: sticky;
        left: 0;
        z-index: 2;
    }

    .sticky-col-kwh-lot,
    .sticky-col-kwh-lot-head {
        position: sticky;
        left: 90px;
        z-index: 2;
    }

    .sticky-col-kwh-charging {
        background-color: white !important;
        min-width: 90px;
    }

    .sticky-col-kwh-lot {
        background-color: white !important;
        min-width: 120px;
    }

    .sticky-col-kwh-charging-head,
    .sticky-col-kwh-lot-head {
        background-color: rgb(37, 37, 37) !important;
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
                                <button class="btn btn-md btn-success" wire:click='export' @if(!$hasSearched) disabled
                                    @endif>
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
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab == 'kwh' ? 'active' : '' }}" data-toggle="tab"
                                    href="#kwh" role="tab" wire:click.prevent="$set('activeTab', 'kwh')">
                                    <span class="d-none d-sm-block">
                                        <p>KWH</p>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab == 'tapping' ? 'active' : '' }}" data-toggle="tab"
                                    href="#tapping" role="tab" wire:click.prevent="$set('activeTab', 'tapping')">
                                    <span class="d-none d-sm-block">
                                        <p>Temperature Tapping</p>
                                    </span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content p-3 text-muted">
                            @if($hasSearched)
                            <!-- TAB RAW MATERIAL -->
                            <div class="tab-pane {{ $activeTab == 'raw-material' ? 'active' : '' }}" id="rawMaterial"
                                role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-dark">
                                            <tr>
                                                <th rowspan="2" class="sticky-col-head">Material Name</th>
                                                <th rowspan="2" class="sticky-col-head-charging">Charging</th>
                                                <th rowspan="2" class="sticky-col-head-lot">Lot</th>
                                                <th colspan="{{ count($dateRange) }}">
                                                    <div>Actual Usage (Kg)</div>
                                                    @if(!empty($data) && $data->first())
                                                    <span class="badge badge-soft-light text-warning fw-bold"> Furnace
                                                        {{ $data->first()->plan_furnace ?? '-' }}
                                                    </span>
                                                    @endif
                                                </th>
                                                <th rowspan="2">Subtotal</th>
                                            </tr>
                                            <tr>
                                                @foreach($dateRange as $date)
                                                <th>{{ \Carbon\Carbon::parse($date)->format('d/m') }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $prevCharging = null; @endphp
                                            @forelse($data as $row)
                                            @php
                                            $currentCharging = $row->charging ?? '-';
                                            $rowClass = ($prevCharging !== null && $prevCharging != $currentCharging) ?
                                            'table-secondary' : '';
                                            $prevCharging = $currentCharging;
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td class="text-start sticky-col">{{ $row->material_name }}</td>
                                                <td class="sticky-col-charging">{{ $row->charging ?? '-' }}</td>
                                                <td class="sticky-col-lot">{{ $row->lot ?? '-' }}</td>
                                                @foreach($dateRange as $date)
                                                @php $dateKey = str_replace('-', '_', $date); $alias = 'date_' .
                                                $dateKey; @endphp
                                                <td>{{ isset($row->$alias) && $row->$alias > 0 ?
                                                    number_format($row->$alias, 0) : '-' }}</td>
                                                @endforeach
                                                <td class="fw-bold bg-light">{{ number_format($row->subtotal, 0) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td class="text-start sticky-col">No data available</td>
                                                <td class="sticky-col-charging">-</td>
                                                <td class="sticky-col-lot">-</td>
                                                <td colspan="{{ count($dateRange) + 1 }}" class="text-center py-3">-
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- TAB ADDITIVE -->
                            <div class="tab-pane {{ $activeTab == 'additive' ? 'active' : '' }}" id="additive"
                                role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-dark">
                                            <tr>
                                                <th rowspan="3" class="sticky-col-head">Material Name</th>
                                                <th rowspan="3" class="sticky-col-head-charging">Charging</th>
                                                <th rowspan="3" class="sticky-col-head-lot">Lot</th>
                                                <th colspan="{{ count($dateRange) * 2 }}">
                                                    <div>Actual Usage (Kg)</div>
                                                    @if(!empty($data) && $data->first())
                                                    <span class="badge badge-soft-light text-warning fw-bold"> Furnace
                                                        {{ $data->first()->plan_furnace ?? '-' }}
                                                    </span>
                                                    @endif
                                                </th>
                                                <th rowspan="3">Subtotal</th>
                                            </tr>
                                            <tr>
                                                @foreach($dateRange as $date)
                                                <th colspan="2">{{ \Carbon\Carbon::parse($date)->format('d/m') }}</th>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                @foreach($dateRange as $date)
                                                <th style="font-size: 10px;" class="bg-secondary">P.ADJ</th>
                                                <th style="font-size: 10px;" class="bg-info">ADJ</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $prevCharging = null; @endphp
                                            @forelse($data as $row)
                                            @php
                                            $currentCharging = $row->charging ?? '-';
                                            $rowClass = ($prevCharging !== null && $prevCharging != $currentCharging) ?
                                            'table-secondary' : '';
                                            $prevCharging = $currentCharging;
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td class="text-start sticky-col">{{ $row->material_name }}</td>
                                                <td class="sticky-col-charging">{{ $row->charging ?? '-' }}</td>
                                                <td class="sticky-col-lot">{{ $row->lot ?? '-' }}</td>
                                                @foreach($dateRange as $date)
                                                @php
                                                $dateKey = str_replace('-', '_', $date);
                                                $preAlias = 'pre_date_' . $dateKey;
                                                $adjAlias = 'date_' . $dateKey;
                                                @endphp
                                                <td>{{ isset($row->$preAlias) && $row->$preAlias > 0 ?
                                                    number_format($row->$preAlias, 0) : '-' }}</td>
                                                <td>{{ isset($row->$adjAlias) && $row->$adjAlias > 0 ?
                                                    number_format($row->$adjAlias, 0) : '-' }}</td>
                                                @endforeach
                                                <td class="fw-bold bg-light">{{ number_format($row->subtotal, 0) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td class="text-start sticky-col">No data available</td>
                                                <td class="sticky-col-charging">-</td>
                                                <td class="sticky-col-lot">-</td>
                                                <td colspan="{{ (count($dateRange) * 2) + 1 }}"
                                                    class="text-center py-3">-</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- TAB KWH -->
                            <div class="tab-pane {{ $activeTab == 'kwh' ? 'active' : '' }}" id="kwh" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="sticky-col-kwh-charging-head">Charging</th>
                                                <th class="sticky-col-kwh-lot-head">Lot</th>
                                                <th>Date</th>
                                                <th>Furnace</th>
                                                <th>Charge Time (hour)</th>
                                                <th>KWH Start Charge</th>
                                                <th>KWH Ok Charge</th>
                                                <th>Power (KW)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($kwhData as $kwh)
                                            <tr>
                                                <td class="sticky-col-kwh-charging">{{ $kwh['charging'] ?? '-' }}</td>
                                                <td class="sticky-col-kwh-lot">{{ $kwh['lot'] ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($kwh['date'])->format('d/m/Y') }}</td>
                                                <td>{{ $kwh['plan_furnace'] ?? '-' }}</td>
                                                <td>{{ $kwh['charge_time'] ?? '-' }}</td>
                                                <td>{{ $kwh['kwh_start_charge'] ?? 0 }}</td>
                                                <td>{{ $kwh['kwh_ok_charge'] ?? 0 }}</td>
                                                <td>{{ $kwh['power'] ?? 0 }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-3">No KWH data available</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- TAB TEMPERATURE TAPPING -->
                            <div class="tab-pane {{ $activeTab == 'tapping' ? 'active' : '' }}" id="tapping"
                                role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="sticky-col-kwh-charging-head">Charging</th>
                                                <th class="sticky-col-kwh-lot-head">Lot</th>
                                                <th>Date</th>
                                                <th>Furnace</th>
                                                <th>Temperature (°C)</th>
                                                <th>Type Tapping</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($tappingData as $tapping)
                                            <tr>
                                                <td class="sticky-col-kwh-charging">{{ $tapping['charging'] ?? '-' }}
                                                </td>
                                                <td class="sticky-col-kwh-lot">{{ $tapping['lot'] ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($tapping['date'])->format('d/m/Y') }}</td>
                                                <td>{{ $tapping['plan_furnace'] ?? '-' }}</td>
                                                <td>{{ $tapping['temperatur'] ?? 0 }}</td>
                                                <td>{{ $tapping['type_tapping'] ?? '-' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-3">No Temperature Tapping data
                                                    available</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
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