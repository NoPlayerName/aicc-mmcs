@push('style')
<style>
    body[data-layout-mode="dark"] .thead-light th {
        background-color: #32394e !important;
        border-color: #3a4258 !important;
        color: #e9edf4 !important;
    }

    body[data-layout-mode="dark"] .table,
    body[data-layout-mode="dark"] .table td,
    body[data-layout-mode="dark"] .table th,
    body[data-layout-mode="dark"] .card-body,
    body[data-layout-mode="dark"] h5 {
        color: #e9edf4 !important;
    }

    body[data-layout-mode="dark"] .table-hover tbody tr:hover {
        background-color: #32394e !important;
    }

    body[data-layout-mode="dark"] .text-muted {
        color: #a6b0cf !important;
    }
</style>
@endpush

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18 text-uppercase">ACE Material Adjust</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0 small">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">ACE</a></li>
                            <li class="breadcrumb-item active">Material Adjust</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="font-weight-bold">Tanggal Transaksi</label>
                        <input type="date" class="form-control @error('transaction_date') is-invalid @enderror"
                            wire:model="transaction_date">
                        @error('transaction_date')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="font-weight-bold">Material</label>
                        <div wire:ignore>
                            <select class="form-control @error('material_id') is-invalid @enderror"
                                id="ace-adjust-material-select2">
                                <option value="">Select Material</option>
                                @foreach ($materials as $material)
                                <option value="{{ $material['material_code'] }}"
                                    @selected($material_id===$material['material_code'])>[{{ $material['material_type']
                                    }}] {{
                                    $material['material_name'] }} ({{ $material['material_code'] }})</option>
                                @endforeach
                            </select>
                        </div>
                        @error('material_id')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="font-weight-bold">Qty Adjust (kg)</label>
                        <input type="number" step="0.001" class="form-control @error('qty_adjust') is-invalid @enderror"
                            wire:model="qty_adjust" placeholder="contoh: -25.500 / 10.000">
                        @error('qty_adjust')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="font-weight-bold">Note</label>
                        <input type="text" class="form-control @error('note') is-invalid @enderror" wire:model="note"
                            placeholder="Alasan adjustment">
                        @error('note')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" wire:click="addDraft">
                            <i class="fas fa-plus mr-1"></i>Add Draft
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Draft Transaction Adjust</h5>
                    <button type="button" class="btn btn-success" wire:click="saveAdjust">
                        <i class="fas fa-save mr-1"></i>Save Adjust
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-centered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Material</th>
                                <th>Tipe</th>
                                <th class="text-right">Qty Adjust (kg)</th>
                                <th>Note</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($draftAdjust as $index => $item)
                            <tr>
                                <td>{{ $item['transaction_date'] }}</td>
                                <td>{{ $item['material_name'] }} ({{ $item['material_id'] }})</td>
                                <td>{{ $item['material_type'] }}</td>
                                <td
                                    class="text-right font-weight-bold {{ $item['qty_adjust'] < 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($item['qty_adjust'], 3) }}
                                </td>
                                <td>{{ $item['note'] ?: '-' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-outline-danger btn-sm"
                                        wire:click="removeDraft({{ $index }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada draft adjust.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function initAceAdjustMaterialSelect2() {
        const $select = $('#ace-adjust-material-select2');
        if (!$select.length) return;

        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }

        $select.select2({
            width: '100%',
            placeholder: 'Select Material',
            allowClear: true
        }).off('change.aceAdjust').on('change.aceAdjust', function () {
            Livewire.dispatch('aceAdjustMaterialSelected', { materialId: $(this).val() || '' });
        });
    }

    $(document).on('livewire:navigated', function () {
        initAceAdjustMaterialSelect2();

        if (!window.__aceAdjustSelectBound) {
            window.__aceAdjustSelectBound = true;
            Livewire.on('resetAceAdjustMaterialSelect', () => {
                const $select = $('#ace-adjust-material-select2');
                if ($select.length) {
                    $select.val('').trigger('change.select2');
                }
            });
        }
    });
</script>
@endpush