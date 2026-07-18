@push('style')
<style>
    .custom-accordion .card-header {
        padding: 0;
        border: none;
    }

    .accordion-button-custom {
        display: block;
        padding: 1.25rem;
        background-color: #f8f9fa;
        border-radius: 8px !important;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .accor-down-icon {
        transition: transform 0.3s ease;
        display: inline-block;
    }

    .accordion-button-custom:not(.collapsed) .accor-down-icon {
        transform: rotate(180deg);
        color: #556ee6 !important;
    }

    .accordion-button-custom:focus {
        outline: none;
        box-shadow: none;
    }

    .accordion-button-custom:hover {
        background-color: #f1f3f5;
        text-decoration: none;
    }

    .accordion-button-custom[aria-expanded="true"] {
        background-color: #fff;
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-bottom: 1px solid transparent;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #6c757d;
        font-weight: 600;
        display: block;
    }

    .info-value {
        font-size: 0.95rem;
        color: #343a40;
        font-weight: 700;
    }

    .badge-soft-primary {
        background-color: rgba(85, 110, 230, 0.1);
        color: #556ee6;
    }

    body[data-layout-mode="dark"] .accordion-button-custom,
    body[data-layout-mode="dark"] .accordion-button-custom[aria-expanded="true"],
    body[data-layout-mode="dark"] .card-body.border.rounded-bottom,
    body[data-layout-mode="dark"] .card-body.border.rounded-bottom.bg-white {
        background-color: #2a3042 !important;
        border-color: #3a4258 !important;
        color: #e9edf4 !important;
    }

    body[data-layout-mode="dark"] .accordion-button-custom:hover {
        background-color: #32394e !important;
    }

    body[data-layout-mode="dark"] .info-label,
    body[data-layout-mode="dark"] .text-muted {
        color: #a6b0cf !important;
    }

    body[data-layout-mode="dark"] .info-value,
    body[data-layout-mode="dark"] .text-dark,
    body[data-layout-mode="dark"] .table,
    body[data-layout-mode="dark"] .table td,
    body[data-layout-mode="dark"] .table th,
    body[data-layout-mode="dark"] h5 {
        color: #e9edf4 !important;
    }

    body[data-layout-mode="dark"] .thead-light th {
        background-color: #32394e !important;
        border-color: #3a4258 !important;
        color: #e9edf4 !important;
    }

    body[data-layout-mode="dark"] .table-hover tbody tr:hover {
        background-color: #32394e !important;
    }

    body[data-layout-mode="dark"] .badge-light {
        background-color: #3a4258 !important;
        color: #e9edf4 !important;
    }
</style>
@endpush

<div class="page-content" wire:poll.visible.10000ms="changeFilter">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">JSH Material Input</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">JSH</a></li>
                            <li class="breadcrumb-item active">JSH Material</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold"><i class="mdi mdi-calendar mr-1"></i>Select
                                        Date</label>
                                    <input type="text" class="form-control form-control-lg" data-provide="datepicker"
                                        data-date-format="dd/mm/yyyy" data-date-autoclose="true"
                                        placeholder="Choose Date" inputmode="none">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold"><i class="mdi mdi-clock-outline mr-1"></i>Select
                                        Shift</label>
                                    <div wire:ignore>
                                        <select class="form-control form-control-lg" id="jsh-material-shift">
                                            <option value="">Select</option>
                                            <option value="D">D </option>
                                            <option value="S">S </option>
                                            <option value="N">N </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-right">
                                <button type="button" class="btn btn-primary btn-lg waves-effect waves-light shadow-sm"
                                    wire:click="addFurnace">
                                    <i class="mdi mdi-plus-circle mr-2"></i>Add New Furnace
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @forelse (($data ?? []) as $indexPlan => $dt)
                <div id="accordion-{{ $indexPlan }}" class="custom-accordion mb-3" wire:key="plan-{{ $indexPlan }}">
                    <div class="card shadow-none border-0 mb-0">
                        <div class="card-header p-0" id="heading{{ $indexPlan }}">
                            <a href="#"
                                class="accordion-button-custom text-dark {{ $openIndex === $indexPlan ? '' : 'collapsed' }}"
                                wire:click.prevent="toggleAccordion({{ $indexPlan }})"
                                aria-expanded="{{ $openIndex === $indexPlan ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $indexPlan }}">

                                <div class="row align-items-center text-center text-md-left">
                                    <div class="col-md-2 mb-2 mb-md-0">
                                        <small class="info-label">Furnace</small>
                                        <span class="info-value text-primary font-size-15">
                                            <i class="fas fa-fire-alt mr-2"></i>{{ $dt['furnace'] }}
                                        </span>
                                    </div>

                                    <div class="col-md-2 mb-2 mb-md-0 border-left">
                                        <small class="info-label">Processing Date</small>
                                        <span class="info-value">{{ $dt['date'] }}</span>
                                    </div>

                                    <div class="col-md-1 mb-2 mb-md-0 border-left text-center">
                                        <small class="info-label">Shift</small>
                                        <span class="badge badge-soft-primary px-3 py-1 mt-1 font-size-12">{{
                                            $dt['shift'] }}</span>
                                    </div>

                                    <div class="col-md-3 border-left text-center">
                                        <small class="info-label">Total Raw Material</small>
                                        <span class="info-value text-dark">
                                            {{ number_format($dt['total_raw_material'] ?? 0, 0, ',', '.') }}
                                            <small class="text-muted font-weight-normal ml-1">kg</small>
                                        </span>
                                    </div>

                                    <div class="col-md-3 border-left text-center border-right">
                                        <small class="info-label">Total Additive</small>
                                        <span class="info-value text-info">
                                            {{ number_format($dt['total_additive'] ?? 0, 1, ',', '.') }}
                                            <small class="text-muted font-weight-normal ml-1">kg</small>
                                        </span>
                                    </div>

                                    <div class="col-md-1 text-right">
                                        <i class="mdi mdi-chevron-down font-size-24 accor-down-icon text-muted"></i>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div id="collapse{{ $indexPlan }}"
                            class="collapse {{ $openIndex === $indexPlan ? 'show' : '' }}"
                            aria-labelledby="heading{{ $indexPlan }}" data-parent="#accordion-{{ $indexPlan }}">
                            <div class="card-body border rounded-bottom bg-white">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="m-0 font-size-15"><i
                                            class="mdi mdi-format-list-bulleted mr-2"></i>Charging Details</h5>
                                    <button type="button" class="btn btn-outline-primary btn-sm waves-effect"
                                        wire:click="addManualCharging({{ $indexPlan }}, {{ $dt['furnace'] }}, '{{ $dt['date'] }}', '{{ $dt['shift'] }}')">

                                        <i class="mdi mdi-plus mr-1"></i>Add Charging
                                    </button>
                                    {{-- @if(in_array($dt['plan_furnace'], [1, 2, 3, 4, 5]))
                                    <div class="d-flex flex-column align-items-end">
                                        <button type="button" class="btn btn-outline-primary btn-sm waves-effect"
                                            wire:click="addManualCharging({{ $indexPlan }}, {{ $dt['plan_furnace'] }}, '{{ $dt['plan_process_date'] }}', '{{ $dt['shift'] }}')"
                                            title="Add Manual Charging">
                                            <i class="mdi mdi-plus mr-1"></i>Add Charging
                                        </button>
                                        <small class="text-warning ml-2">Khusus untuk material transfer</small>
                                    </div>
                                    @endif --}}
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover table-centered mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-top-0">Charging #</th>
                                                <th class="border-top-0">Lot Number</th>
                                                <th class="border-top-0">Product</th>
                                                <th class="border-top-0">Description</th>
                                                <th class="border-top-0 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($dt['chargings'] as $indexCharge => $charge)
                                            <tr wire:key="charge-{{ $indexPlan }}-{{ $indexCharge }}">
                                                <td class="font-weight-bold text-dark">{{ $charge['charging'] ?? '-' }}
                                                </td>
                                                <td><span class="badge badge-light px-2 py-1">{{ $charge['lot']
                                                        }}</span></td>
                                                <td>{{ $charge['model_id'] }}</td>
                                                <td>{{ $charge['desc'] }}</td>
                                                <td class="text-right">
                                                    <button class="btn btn-info btn-sm"
                                                        wire:click='Detail({{ $indexPlan}}, {{ $indexCharge }})'
                                                        title="View Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if ($this->can('can_edit'))
                                                    <button class="btn btn-warning btn-sm"
                                                        wire:click='Edit({{ $indexPlan}}, {{ $indexCharge }})'
                                                        title="Edit Data">
                                                        <i class="fas fa-edit text-white"></i>
                                                    </button>
                                                    @endif
                                                    <button class="btn btn-primary btn-sm"
                                                        wire:click="Proccess({{ $indexPlan }}, {{ $indexCharge }})"
                                                        title="Process">
                                                        <i class="fas fa-cogs"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted small italic">
                                                    <i class="fas fa-info-circle fa-4x text-muted mb-3 d-block"></i>
                                                    <h5 class="text-dark font-weight-bold">No charging data recorded for
                                                        this furnace.</h5>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="card shadow-sm border-dashed">
                    <div class="card-body text-center py-5">
                        <div class="avatar-lg mx-auto mb-4">
                            <i class="fas fa-info-circle fa-4x text-muted mb-3 d-block"></i>
                        </div>
                        <h5 class="text-dark">Data Tidak Ditemukan</h5>
                        <p class="text-muted mx-auto w-50">Silahkan pilih tanggal dan shift lain atau tambahkan furnace
                            baru untuk memulai input material.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- form modal --}}
    @livewire('jsh.form-material-input')
    @livewire('jsh.detail-charging')
</div>
@push('scripts')
<script>
    function cleanupSelect2ById(id) {
        if (!id) return;

        const $el = $(`#${id}`);
        if ($el.length && $el.hasClass('select2-hidden-accessible') && $el.data('select2')) {
            $el.select2('destroy');
        }

        $el.next('.select2-container').remove();
        $(`#select2-${id}-container`).closest('.select2-container').remove();
        $(`[aria-labelledby="select2-${id}-container"]`).closest('.select2-container').remove();
    }

    function cleanupJshSelect2Artifacts() {
        [
            'jsh-material-shift',
            'rawMat-select2',
            'Additive-select2',
            'Type-Adjust-select2',
            'Type-Tapping-select2'
        ].forEach(cleanupSelect2ById);
    }

    function initJshDatepicker() {
        $('[data-provide="datepicker"]').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true
        }).off('changeDate.jshMaterialInput').on('changeDate.jshMaterialInput', function (e) {
            let selectedDate = e.format();
            Livewire.dispatch('Date', {data: selectedDate});
        });
    }

    function initSelect2WithDispatch(selector, options, eventName, payloadBuilder) {
        const $el = $(selector);
        if (!$el.length) return;

        const select2Options = { ...(options || {}) };
        if (select2Options.dropdownParent && !select2Options.dropdownParent.length) {
            delete select2Options.dropdownParent;
        }

        const elementId = $el.attr('id');
        if (elementId) {
            cleanupSelect2ById(elementId);
        }

        $el.select2(select2Options)
            .off('change.jshMaterialInput')
            .on('change.jshMaterialInput', function () {
                Livewire.dispatch(eventName, payloadBuilder($(this)));
            })
            .off('select2:open.jshMaterialInput')
            .on('select2:open.jshMaterialInput', function () {
                // Blur search input untuk mencegah keyboard otomatis
                setTimeout(() => {
                    const searchInput = $(this).data('select2').$dropdown?.find('.select2-search__field');
                    if (searchInput && searchInput.length) {
                        searchInput.blur();
                    }
                }, 10);
            });
    }

    function initJshSelects() {
        initSelect2WithDispatch('#jsh-material-shift', {
            minimumResultsForSearch: Infinity
        }, 'Shift', ($el) => ({
            data: $el.val()
        }));

        initSelect2WithDispatch('#rawMat-select2', {
            minimumResultsForSearch: 5
        }, 'rawMat', ($el) => ({
            rawMat: $el.val(),
            name: $el.find('option:selected').text()
        }));

        initSelect2WithDispatch('#Additive-select2', {
            minimumResultsForSearch: 0
        }, 'additMat', ($el) => ({
            data: $el.val(),
            name: $el.find('option:selected').text()
        }));

        initSelect2WithDispatch('#Type-Adjust-select2', {}, 'typeAddjust', ($el) => ({
            data: $el.val()
        }));

        initSelect2WithDispatch('#Type-Tapping-select2', {}, 'typeTapping', ($el) => ({
            data: $el.val()
        }));
    }

    function bindJshMaterialInputHandlers() {
        cleanupJshSelect2Artifacts();
        initJshDatepicker();
        initJshSelects();

        if (!window.__jshMaterialInputLivewireBound) {
            window.__jshMaterialInputLivewireBound = true;

            Livewire.on('showFormInput', () => {
                $('#modal-material-input').modal('show');
            });

            Livewire.on('showFormEdit', () => {
                $('#modal-material-input').modal('show');
            });

            Livewire.on('showDetailCharge', () => {
                $('#modal-detail-charging').modal('show');
            });

            Livewire.on('saved', () => {
                $('#modal-material-input').modal('hide');
            });

            Livewire.on('loadMaterial', () => {
                setTimeout(() => {
                    initSelect2WithDispatch('#rawMat-select2', {
                        minimumResultsForSearch: 5
                    }, 'rawMat', ($el) => ({
                        rawMat: $el.val(),
                        name: $el.find('option:selected').text()
                    }));
                }, 100);
            });

            Livewire.on('loadAdditive', () => {
                setTimeout(() => {
                    initSelect2WithDispatch('#Additive-select2', {
                        minimumResultsForSearch: 5
                    }, 'additMat', ($el) => ({
                        data: $el.val(),
                        name: $el.find('option:selected').text()
                    }));
                }, 100);
            });
        }
    }

    function enterJshMaterialInputPage() {
        const pageKey = `${window.location.pathname}${window.location.search}`;
        if (window.__jshMaterialInputInitKey === pageKey) {
            return;
        }

        window.__jshMaterialInputInitKey = pageKey;
        bindJshMaterialInputHandlers();
    }

    $(document)
        .off('livewire:navigating.jshMaterialInput')
        .on('livewire:navigating.jshMaterialInput', function () {
            window.__jshMaterialInputInitKey = null;
            cleanupJshSelect2Artifacts();
        });

    $(document)
        .off('livewire:navigated.jshMaterialInput')
        .on('livewire:navigated.jshMaterialInput', function () {
            enterJshMaterialInputPage();
        });

    enterJshMaterialInputPage();
</script>
@endpush