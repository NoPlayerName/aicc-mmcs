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
        /* Penting agar rotasi bekerja */
    }

    .accordion-button-custom:not(.collapsed) .accor-down-icon {
        transform: rotate(180deg);
        color: #556ee6 !important;
        /* Opsional: warna berubah saat buka */
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
        text-uppercase: uppercase;
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
                    <h4 class="mb-0 font-size-18">ACE Material Input</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Ace</a></li>
                            <li class="breadcrumb-item active">ACE Material</li>
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
                                        <select class="form-control form-control-lg" id="ace-material-shift">
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
                @forelse ($furnace as $indexPlan => $item)
                <div id="accordion-{{ $item->id }}" class="custom-accordion mb-3"
                    wire:key='furnace-wrapper-{{ $item->id }}'>
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
                                            <i class="fas fa-fire-alt mr-2"></i>{{ $item->furnace }}
                                        </span>
                                    </div>

                                    <div class="col-md-2 mb-2 mb-md-0 border-left">
                                        <small class="info-label">Processing Date</small>
                                        <span class="info-value">{{ $item->date }}</span>
                                    </div>

                                    <div class="col-md-1 mb-2 mb-md-0 border-left text-center">
                                        <small class="info-label">Shift</small>
                                        <span class="badge badge-soft-primary px-3 py-1 mt-1 font-size-12">{{
                                            $item->shift }}</span>
                                    </div>

                                    <div class="col-md-3 border-left text-center">
                                        <small class="info-label">Total Raw Material</small>
                                        <span class="info-value text-dark">
                                            {{ number_format($item->total_raw_material ?? 0, 0, ',', '.') }}
                                            <small class="text-muted font-weight-normal ml-1">kg</small>
                                        </span>
                                    </div>

                                    <div class="col-md-3 border-left text-center border-right">
                                        <small class="info-label">Total Additive</small>
                                        <span class="info-value text-info">
                                            {{ number_format($item->total_additive ?? 0, 0, ',', '.') }}
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
                            aria-labelledby="heading{{ $indexPlan }}" data-parent="#accordion-{{ $item->id }}">
                            <div class="card-body border rounded-bottom bg-white">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="m-0 font-size-15"><i
                                            class="mdi mdi-format-list-bulleted mr-2"></i>Charging Details</h5>
                                    <button type="button" class="btn btn-outline-primary btn-sm waves-effect"
                                        wire:click="addCharge({{ $item->id }}, {{ $indexPlan }})">
                                        <i class="mdi mdi-plus mr-1"></i>Add Charging
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover table-centered mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-top-0">Charging #</th>
                                                <th class="border-top-0">Lot Number</th>
                                                <th class="border-top-0">Product Name</th>
                                                <th class="border-top-0 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($item->chargings as $indexCharge => $charge)
                                            <tr>
                                                <td class="font-weight-bold text-dark">{{ $charge->charging }}</td>
                                                <td><span class="badge badge-light px-2 py-1">{{ $charge->lot ?? '-'
                                                        }}</span></td>
                                                <td>{{ $charge->product->alias ?? '-' }}</td>
                                                <td class="text-right">
                                                    <div class="btn-group">
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
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted small italic">
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

        {{-- Modals --}}
        @livewire('ace.form-material-input')
        @livewire('ace.detail-charging')
    </div>
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

    function cleanupAceSelect2Artifacts() {
        [
            'ace-material-shift',
            'rawMat-select2',
            'Additive-select2',
            'Type-Adjust-select2',
            'Type-Tapping-select2',
            'selectLotMultiple',
            'product-select2'
        ].forEach(cleanupSelect2ById);
    }

    function initAceDatepicker() {
        $('[data-provide="datepicker"]').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true
        }).off('changeDate.aceMaterialInput').on('changeDate.aceMaterialInput', function (e) {
            let selectedDate = e.format();
            Livewire.dispatch('Date', { data: selectedDate, shift: $('#ace-material-shift').val() || null });
        });
    }

    function initSelect2WithDispatch(selector, options, eventName, payloadBuilder, rootSelector = null) {
        const $root = rootSelector ? $(rootSelector) : $(document);
        const $el = $root.find(selector).first();
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
            .off('change.aceMaterialInput')
            .on('change.aceMaterialInput', function () {
                Livewire.dispatch(eventName, payloadBuilder($(this)));
            })
            .off('select2:open.aceMaterialInput')
            .on('select2:open.aceMaterialInput', function () {
                // Blur search input untuk mencegah keyboard otomatis
                setTimeout(() => {
                    const searchInput = $(this).data('select2').$dropdown?.find('.select2-search__field');
                    if (searchInput && searchInput.length) {
                        // searchInput.prop('readonly', true);
                        searchInput.blur();
                    }
                }, 10);
            });
    }

    function initAceSelect() {
        initSelect2WithDispatch('#ace-material-shift', {
            minimumResultsForSearch: Infinity
        }, 'Shift', ($el) => ({
            data: $el.val(),
             date: $('[data-provide="datepicker"]').val() || null
        }));
         initSelect2WithDispatch('#rawMat-select2', {
            minimumResultsForSearch: 5
        }, 'rawMat', ($el) => ({
            rawMat: $el.val(),
            name: $el.find('option:selected').text()
        }), '#modal-material-input');
          initSelect2WithDispatch('#Additive-select2', {
            minimumResultsForSearch: 5
        }, 'additMat', ($el) => ({
            data: $el.val(),
            name: $el.find('option:selected').text()
        }), '#modal-material-input');
        
        initSelect2WithDispatch('#Type-Adjust-select2', {}, 'typeAddjust', ($el) => ({
            data: $el.val()
        }), '#modal-material-input');

         initSelect2WithDispatch('#Type-Tapping-select2', {}, 'typeTapping', ($el) => ({
            data: $el.val()
        }), '#modal-material-input');

         initSelect2WithDispatch('#selectLotMultiple', {
            width: '100%',
            placeholder: 'Choose Lots...',
            allowClear: true,
            dropdownParent: $('#modal-material-input'),
            maximumSelectionLength: 5,
            minimumResultsForSearch: Infinity,
        }, 'lotSelection', ($el) => ({
            productId: $('#modal-material-input').find('#product-select2').val() ? Number($('#modal-material-input').find('#product-select2').val()) : null,
            lotIds: ($el.val() || []).map(v => Number(v))
        }), '#modal-material-input');
        initSelect2WithDispatch('#product-select2', {
            width: '100%',
            placeholder: 'Choose Product...',
            allowClear: true,
            dropdownParent: $('#modal-material-input'),
            minimumResultsForSearch: 0,
        }, 'productSelect', ($el) => ({
            productId: $el.val() ? Number($el.val()) : null,
            lotIds: ($('#modal-material-input').find('#selectLotMultiple').val() || []).map(v => Number(v))
        }), '#modal-material-input');
    }

    function bindAceMaterialInputPageHandlers() {
        cleanupAceSelect2Artifacts();
        initAceDatepicker();
        initAceSelect();

        $('#modal-material-input')
            .off('shown.bs.modal.aceMaterialInput hide.bs.modal.aceMaterialInput hidden.bs.modal.aceMaterialInput')
            .on('shown.bs.modal.aceMaterialInput', function () {
                initAceSelect();
            })
            .on('hide.bs.modal.aceMaterialInput', function () {
                const activeElement = document.activeElement;
                if (activeElement && this.contains(activeElement)) {
                    activeElement.blur();
                }
            })
            .on('hidden.bs.modal.aceMaterialInput', function () {
                if (window.__aceMaterialInputTrigger && window.__aceMaterialInputTrigger.length) {
                    window.__aceMaterialInputTrigger.trigger('focus');
                }
            });

        $('#modal-detail-charging')
            .off('hide.bs.modal.aceMaterialInput hidden.bs.modal.aceMaterialInput')
            .on('hide.bs.modal.aceMaterialInput', function () {
                const activeElement = document.activeElement;
                if (activeElement && this.contains(activeElement)) {
                    activeElement.blur();
                }
            })
            .on('hidden.bs.modal.aceMaterialInput', function () {
                if (window.__aceMaterialInputTrigger && window.__aceMaterialInputTrigger.length) {
                    window.__aceMaterialInputTrigger.trigger('focus');
                }
            });

        if (!window.__aceMaterialInputPageLivewireBound) {
            window.__aceMaterialInputPageLivewireBound = true;
            
             Livewire.on('showFormEdit', () => {
                window.__aceMaterialInputTrigger = $(document.activeElement);
                $('#modal-material-input').modal('show');
            });

            Livewire.on('showFormInput', () => {
                window.__aceMaterialInputTrigger = $(document.activeElement);
                $('#modal-material-input').modal('show');
            });
             Livewire.on('showDetailCharge', () => {
                window.__aceMaterialInputTrigger = $(document.activeElement);
                $('#modal-detail-charging').modal('show');
            });

            Livewire.on('loadMaterial', () => {
                setTimeout(() => {
                    initSelect2WithDispatch('#rawMat-select2', {
                        minimumResultsForSearch: 0
                    }, 'rawMat', ($el) => ({
                        rawMat: $el.val(),
                        name: $el.find('option:selected').text()
                    }), '#modal-material-input');
                }, 100);
            });

              Livewire.on('loadAdditive', () => {
                setTimeout(() => {
                    initSelect2WithDispatch('#Additive-select2', {
                        minimumResultsForSearch: 0
                    }, 'additMat', ($el) => ({
                        data: $el.val(),
                        name: $el.find('option:selected').text()
                    }), '#modal-material-input');
                }, 100);
            });
        }
    }

    function enterAceMaterialInputPage() {
        const pageKey = `${window.location.pathname}${window.location.search}`;
        if (window.__aceMaterialInputInitKey === pageKey) {
            return;
        }

        window.__aceMaterialInputInitKey = pageKey;
        bindAceMaterialInputPageHandlers();
    }

    $(document)
        .off('livewire:navigating.aceMaterialInputPage')
        .on('livewire:navigating.aceMaterialInputPage', function () {
            window.__aceMaterialInputInitKey = null;
            cleanupAceSelect2Artifacts();
        });

    $(document)
        .off('livewire:navigated.aceMaterialInputPage')
        .on('livewire:navigated.aceMaterialInputPage', function () {
            enterAceMaterialInputPage();
        });

    enterAceMaterialInputPage();
</script>
@endpush