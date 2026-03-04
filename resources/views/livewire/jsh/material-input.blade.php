@push('style')
<style>
    .custom-accordion .card {
        border: 1px solid #f1f3f5;
        margin-bottom: 0.8rem;
        border-radius: 10px;
        overflow: hidden;
    }

    .accordion-button-custom {
        display: block;
        padding: 1rem 1.25rem;
        background-color: #fcfcfd;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        border-radius: 10px !important;
    }

    .accordion-button-custom:hover {
        background-color: #f8f9fa;
        text-decoration: none;
    }

    .accordion-button-custom[aria-expanded="true"] {
        background-color: #fff;
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Fix Rotasi Icon */
    .accor-down-icon {
        transition: transform 0.3s ease;
        display: inline-block;
    }

    .accordion-button-custom:not(.collapsed) .accor-down-icon {
        transform: rotate(180deg);
        color: #556ee6 !important;
    }

    .info-label {
        font-size: 11px;
        text-transform: uppercase;
        color: #adb5bd;
        letter-spacing: 0.5px;
        font-weight: 700;
        display: block;
    }

    .info-value {
        font-size: 14px;
        color: #495057;
        font-weight: 700;
        display: block;
    }

    .badge-soft-primary {
        background-color: rgba(85, 110, 230, 0.1);
        color: #556ee6;
        font-weight: 600;
    }

    @media (min-width: 768px) {
        .border-md-left {
            border-left: 1px solid #eff2f7 !important;
        }
    }
</style>
@endpush

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18 text-uppercase">JSH Material Input</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0 small">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">JSH</a></li>
                            <li class="breadcrumb-item active">JSH</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-bold"><i class="mdi mdi-calendar mr-1"></i>Select
                                Date</label>
                            <input type="text" class="form-control form-control-lg" data-provide="datepicker"
                                data-date-format="dd/mm/yyyy" data-date-autoclose="true" placeholder="Choose Date">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-bold"><i class="mdi mdi-clock-outline mr-1"></i>Select
                                Shift</label>
                            <select class="form-control form-control-lg select2-search-disable" id="shift">
                                <option value="">Select</option>
                                <option value="D">D </option>
                                <option value="S">S </option>
                                <option value="N">N </option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div id="accordion" class="custom-accordion">
            @forelse (($data ?? []) as $indexPlan => $dt)
            <div class="card mb-2 shadow-none border-0" wire:key="plan-{{ $indexPlan }}">
                <div class="card-header p-0" id="heading{{ $indexPlan }}">
                    <a href="#collapse{{ $indexPlan }}"
                        class="accordion-button-custom text-dark {{ $openIndex === $indexPlan ? '' : 'collapsed' }}"
                        data-toggle="collapse" aria-expanded="{{ $openIndex === $indexPlan ? 'true' : 'false' }}">

                        <div class="row align-items-center">
                            <div class="col-md-2 mb-2 mb-md-0">
                                <small class="info-label">Furnace ID</small>
                                <span class="info-value text-primary font-size-15"><i
                                        class="fas fa-fire-alt mr-2"></i>{{ $dt['plan_furnace'] }}</span>
                            </div>

                            <div class="col-md-2 mb-2 mb-md-0 border-md-left pl-md-3">
                                <small class="info-label">Process Date</small>
                                <span class="info-value font-weight-normal">{{ $dt['plan_process_date'] }}</span>
                            </div>

                            <div class="col-md-1 mb-2 mb-md-0 border-md-left text-center">
                                <small class="info-label">Shift</small>
                                <span class="badge badge-soft-primary px-3">{{ $dt['shift'] }}</span>
                            </div>

                            <div class="col-md-3 border-md-left text-center">
                                <small class="info-label">Total Raw Material</small>
                                <span class="info-value font-weight-bold">{{ number_format($dt['total_raw_material'], 0,
                                    ',', '.') }} <small class="text-muted">kg</small></span>
                            </div>

                            <div class="col-md-3 border-md-left text-center">
                                <small class="info-label text-info">Total Additive</small>
                                <span class="info-value text-info">{{ number_format($dt['total_additive'], 0, ',', '.')
                                    }} <small class="text-muted">kg</small></span>
                            </div>

                            <div class="col-md-1 text-right">
                                <i class="mdi mdi-chevron-down font-size-24 accor-down-icon text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div id="collapse{{ $indexPlan }}" class="collapse {{ $openIndex === $indexPlan ? 'show' : '' }}"
                    aria-labelledby="heading{{ $indexPlan }}" data-parent="#accordion">
                    <div class="card-body border-top bg-white p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="py-3">Charging #</th>
                                        <th>Lot Number</th>
                                        <th>Product</th>
                                        <th class="text-right px-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dt['chargings'] as $indexCharge => $charge)
                                    <tr wire:key="charge-{{ $indexPlan }}-{{ $indexCharge }}">
                                        <td class="font-weight-bold text-dark">{{ $charge['charging'] ?? '-' }}</td>
                                        <td><span class="badge badge-light p-2 font-size-12">{{ $charge['lot'] }}</span>
                                        </td>
                                        <td>{{ $charge['model_id'] }}</td>
                                        <td class="text-right px-4">
                                            <div class="btn-group">
                                                <button class="btn btn-info btn-sm"
                                                    wire:click='Detail({{ $indexPlan}}, {{ $indexCharge }})'
                                                    title="View Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-warning btn-sm"
                                                    wire:click='Edit({{ $indexPlan}}, {{ $indexCharge }})'
                                                    title="Edit Data">
                                                    <i class="fas fa-edit text-white"></i>
                                                </button>
                                                <button class="btn btn-primary btn-sm"
                                                    wire:click="Proccess({{ $indexPlan }}, {{ $indexCharge }})"
                                                    title="Process">
                                                    <i class="fas fa-cogs"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card border-dashed p-5 text-center bg-transparent">
                <i class="fas fa-info-circle fa-4x text-muted mb-3 d-block"></i>
                <h5 class="text-dark font-weight-bold">Data Tidak ditemukan</h5>
                <p class="text-muted">Silahkan periksa filter tanggal atau shift Anda.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- form modal --}}
    @livewire('jsh.form-material-input')
    @livewire('jsh.detail-charging')
</div>
@push('scripts')
<script>
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

        if ($el.hasClass('select2-hidden-accessible') && $el.data('select2')) {
            $el.select2('destroy');
        }

        $el.select2(options)
            .off('change.jshMaterialInput')
            .on('change.jshMaterialInput', function () {
                Livewire.dispatch(eventName, payloadBuilder($(this)));
            });
    }

    function initJshSelects() {
        initSelect2WithDispatch('#shift', {
            minimumResultsForSearch: Infinity
        }, 'Shift', ($el) => ({
            data: $el.val()
        }));

        initSelect2WithDispatch('#rawMat-select2', {
            minimumResultsForSearch: 0
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
                        minimumResultsForSearch: 0
                    }, 'rawMat', ($el) => ({
                        rawMat: $el.val(),
                        name: $el.find('option:selected').text()
                    }));
                }, 100);
            });

            Livewire.on('loadAdditive', () => {
                setTimeout(() => {
                    initSelect2WithDispatch('#Additive-select2', {
                        minimumResultsForSearch: 0
                    }, 'additMat', ($el) => ({
                        data: $el.val(),
                        name: $el.find('option:selected').text()
                    }));
                }, 100);
            });
        }
    }

    $(document).ready(function () {
        bindJshMaterialInputHandlers();
    });

    $(document).on('livewire:navigated', function () {
        bindJshMaterialInputHandlers();
    });
</script>
@endpush