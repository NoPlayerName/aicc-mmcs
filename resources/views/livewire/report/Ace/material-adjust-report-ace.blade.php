<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">ACE Material Adjust Report</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Report</a></li>
                            <li class="breadcrumb-item active">ACE Material Adjust</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="font-weight-bold">Start Date</label>
                        <input type="date" class="form-control" wire:model="startDate">
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold">End Date</label>
                        <input type="date" class="form-control" wire:model="endDate">
                    </div>
                    <div class="col-md-4">
                        <label class="font-weight-bold">Material (optional)</label>
                        <select class="form-control" wire:model="material">
                            <option value="">All Material</option>
                            @foreach ($materials as $item)
                            <option value="{{ $item['material_code'] }}">[{{ $item['material_type'] }}] {{
                                $item['material_name'] }} ({{ $item['material_code'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-block" wire:click="search">
                            <i class="fas fa-search mr-1"></i>Search
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Adjustment Result - ACE</h5>
                    <button class="btn btn-success" wire:click="export" @if(!$hasSearched) disabled @endif>
                        <i class="far fa-file-excel mr-1"></i>Export
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Material</th>
                                <th>Type</th>
                                <th class="text-right">Qty Adjust (kg)</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$hasSearched)
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Pilih filter lalu klik Search.</td>
                            </tr>
                            @elseif (empty($rows))
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data (UI only).</td>
                            </tr>
                            @else
                            @foreach ($rows as $row)
                            <tr>
                                <td>{{ $row['transaction_date'] }}</td>
                                <td>{{ $row['material_name'] }} ({{ $row['material_id'] }})</td>
                                <td>{{ $row['material_type'] }}</td>
                                <td class="text-right">{{ number_format($row['qty_adjust'], 3) }}</td>
                                <td>{{ $row['note'] ?: '-' }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>