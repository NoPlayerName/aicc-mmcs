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

    .sticky-col-head {
        position: sticky;
        left: 0;
        background-color: rgb(37, 37, 37) !important;
        z-index: 1;
    }
</style>
@endpush

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">JSH Material Adjust Report</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Report</a></li>
                            <li class="breadcrumb-item active">JSH Material Adjust</li>
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

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Material (optional)</label>
                                    <select class="form-control" wire:model="material">
                                        <option value="">All Material</option>
                                        @foreach ($materials as $item)
                                        <option value="{{ $item['material_code'] }}">[{{ $item['material_type'] }}] {{
                                            $item['material_name'] }} ({{ $item['material_code'] }})</option>
                                        @endforeach
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
                        </ul>

                        <div class="tab-content p-3 text-muted">
                            @if($hasSearched)
                            <div class="tab-pane active" id="rawMaterial" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="sticky-col-head" rowspan="2">Material Name</th>
                                                <th colspan="{{ count($dateRange) * 2 }}">Result (kg)</th>
                                                <th rowspan="2">Subtotal Adjust</th>
                                                <th rowspan="2">Subtotal Final</th>
                                            </tr>
                                            <tr>
                                                @foreach($dateRange as $date)
                                                <th style="font-size: 10px;" class="bg-secondary">{{ date('d/m',
                                                    strtotime($date)) }} ADJ</th>
                                                <th style="font-size: 10px;" class="bg-info">{{ date('d/m',
                                                    strtotime($date)) }} FINAL</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($pivotRows as $row)
                                            <tr>
                                                <td class="text-start sticky-col">{{ $row['material_name'] }} ({{
                                                    $row['material_id'] }})</td>
                                                @foreach($dateRange as $date)
                                                @php
                                                $adjustValue = (float) ($row['dates'][$date]['adjust'] ?? 0);
                                                $finalValue = (float) ($row['dates'][$date]['final'] ?? 0);
                                                $displayAdjust = rtrim(rtrim(number_format($adjustValue, 3, '.', ''),
                                                '0'), '.');
                                                $displayFinal = rtrim(rtrim(number_format($finalValue, 3, '.', ''),
                                                '0'), '.');
                                                @endphp
                                                <td class="{{ $adjustValue < 0 ? 'text-danger' : 'text-dark' }}">{{
                                                    $adjustValue != 0 ? $displayAdjust : '-' }}</td>
                                                <td>{{ $finalValue != 0 ? $displayFinal : '-' }}</td>
                                                @endforeach
                                                @php
                                                $subtotalAdjustDisplay = rtrim(rtrim(number_format((float)
                                                $row['subtotal_adjust'], 3, '.', ''), '0'), '.');
                                                $subtotalFinalDisplay = rtrim(rtrim(number_format((float)
                                                $row['subtotal_final'], 3, '.', ''), '0'), '.');
                                                @endphp
                                                <td
                                                    class="font-weight-bold bg-light {{ $row['subtotal_adjust'] < 0 ? 'text-danger' : 'text-dark' }}">
                                                    {{ $row['subtotal_adjust'] != 0 ? $subtotalAdjustDisplay : '-' }}
                                                </td>
                                                <td class="font-weight-bold bg-light">{{ $row['subtotal_final'] != 0 ?
                                                    $subtotalFinalDisplay : '-' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="{{ (count($dateRange) * 2) + 3 }}"
                                                    class="text-center py-4">Belum ada data.</td>
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
        }).on('changeDate', function () {
            let startDate = $('#start-date').val();
            let endDate = $('#end-date').val();

            Livewire.dispatch('Date', {startDate: startDate, endDate: endDate});
        });
    })
</script>
@endpush