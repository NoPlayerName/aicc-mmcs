<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">ACE Ladle Transfer Report</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Report</a></li>
                            <li class="breadcrumb-item active">ACE Ladle Transfer</li>
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
                            <div class="col-md-4">
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
                                    <label>Shift</label>
                                    <select class="form-control" wire:model="shift">
                                        <option value="">All Shift</option>
                                        <option value="D">D</option>
                                        <option value="S">S</option>
                                        <option value="N">N</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-auto">
                                <button class="btn btn-md btn-primary" wire:click="search" wire:loading.attr="disabled">
                                    <i class="fas fa-search" wire:loading.remove></i>
                                    <span class="spinner-border spinner-border-sm" wire:loading></span>
                                    Search
                                </button>
                                <div style="height: 15px;"></div>
                            </div>

                            <div class="col-md-auto">
                                <button class="btn btn-md btn-success" wire:click="export" @if(!$hasSearched) disabled
                                    @endif>
                                    <i class="far fa-file-excel"></i> Export
                                </button>
                                <div style="height: 15px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($hasSearched)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm align-middle mb-0">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>Date</th>
                                        <th>Shift</th>
                                        <th>Furnace</th>
                                        <th>Lot</th>
                                        <th>Product</th>
                                        <th>Molten Weight (kg)</th>
                                        <th>Ladle Temp</th>
                                        <th>Weighing</th>
                                        <th>Conveyor</th>
                                        <th>Ladle Drop</th>
                                        <th>Treatment</th>
                                        <th>Total Inoculant (kg)</th>
                                        <th>Inoculant Name</th>
                                        <th>Created By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rows as $row)
                                    <tr>
                                        @php
                                        $moltenWeight = (float) ($row['molten_weight'] ?? 0);
                                        $displayMoltenWeight = rtrim(rtrim(number_format($moltenWeight, 3, '.', ''),
                                        '0'), '.');
                                        $ladleTemp = (float) ($row['ladle_molten_temp'] ?? 0);
                                        $displayLadleTemp = rtrim(rtrim(number_format($ladleTemp, 3, '.', ''), '0'),
                                        '.');
                                        $totalInoculantWeight = (float) ($row['total_inoculant_weight'] ?? 0);
                                        $displayTotalInoculantWeight = rtrim(rtrim(number_format($totalInoculantWeight,
                                        3, '.', ''), '0'), '.');
                                        @endphp
                                        <td>{{ !empty($row['transaction_date']) ? date('d/m/Y',
                                            strtotime($row['transaction_date'])) : '-' }}</td>
                                        <td class="text-center">{{ $row['shift'] ?? '-' }}</td>
                                        <td class="text-center">{{ $row['furnace'] ?? '-' }}</td>
                                        <td>{{ $row['lot'] ?? '-' }}</td>
                                        <td>{{ $row['product'] ?? '-' }}</td>
                                        <td class="text-right">{{ $displayMoltenWeight === '' ? '0' :
                                            $displayMoltenWeight }}</td>
                                        <td class="text-right">{{ $displayLadleTemp === '' ? '0' : $displayLadleTemp }}
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-{{ !empty($row['weighing_status']) ? 'success' : 'secondary' }}">
                                                {{ !empty($row['weighing_status']) ? 'OK' : 'NO' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-{{ !empty($row['conveyor_drop_status']) ? 'success' : 'secondary' }}">
                                                {{ !empty($row['conveyor_drop_status']) ? 'OK' : 'NO' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-{{ !empty($row['ladle_drop_status']) ? 'success' : 'secondary' }}">
                                                {{ !empty($row['ladle_drop_status']) ? 'OK' : 'NO' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-{{ !empty($row['treatment_duration_check']) ? 'success' : 'secondary' }}">
                                                {{ !empty($row['treatment_duration_check']) ? 'OK' : 'NO' }}
                                            </span>
                                        </td>
                                        <td class="text-right">{{ $displayTotalInoculantWeight === '' ? '0' :
                                            $displayTotalInoculantWeight }}</td>
                                        <td>
                                            @if(!empty($row['inoculants']))
                                            @foreach($row['inoculants'] as $item)
                                            <div>{{ $item['material_name'] ?? '-' }}</div>
                                            @endforeach
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>{{ $row['created_by'] ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="14" class="text-center py-4">Belum ada data.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
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