<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">ACE Ladle Transfer Adjust Report</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Report</a></li>
                            <li class="breadcrumb-item active">ACE Ladle TF Adjust</li>
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

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Inoculant (optional)</label>
                                    <select class="form-control" wire:model="material">
                                        <option value="">All Inoculant</option>
                                        @foreach ($materials as $item)
                                        <option value="{{ $item['material_code'] }}">{{ $item['material_name'] }}
                                            ({{ $item['material_code'] }})</option>
                                        @endforeach
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
                                        <th>Transaction Date</th>
                                        <th>Material ID</th>
                                        <th>Material Name</th>
                                        <th>Qty Adjust (kg)</th>
                                        <th>Note</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rows as $row)
                                    <tr>
                                        <td>{{ !empty($row['transaction_date']) ? date('d/m/Y',
                                            strtotime($row['transaction_date'])) : '-' }}</td>
                                        <td>{{ $row['material_id'] ?? '-' }}</td>
                                        <td>{{ $row['material_name'] ?? '-' }}</td>
                                        @php
                                        $qtyAdjust = (float) ($row['qty_adjust'] ?? 0);
                                        $displayQtyAdjust = rtrim(rtrim(number_format($qtyAdjust, 3, '.', ''), '0'),
                                        '.');
                                        @endphp
                                        <td class="text-right {{ $qtyAdjust < 0 ? 'text-danger' : 'text-success' }}">
                                            {{ $displayQtyAdjust === '' ? '0' : $displayQtyAdjust }}
                                        </td>
                                        <td>{{ $row['note'] ?? '-' }}</td>
                                        <td>{{ $row['created_by'] ?? '-' }}</td>
                                        <td>{{ !empty($row['created_at']) ? date('d/m/Y H:i:s',
                                            strtotime($row['created_at'])) : '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">Belum ada data.</td>
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