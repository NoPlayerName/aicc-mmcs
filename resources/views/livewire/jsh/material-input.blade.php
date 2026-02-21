@push('style')
{{-- custom style can be add here --}}
@endpush
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">JSH Material Input</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">JSH Material Input</a></li>
                            <li class="breadcrumb-item active">JSH</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header mt-0">
                        {{-- <form id="form-search" wire:submit.prevent="search" enctype="multipart/form-data"> --}}
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Select Date</label>
                                        <input type="text" class="form-control" data-provide="datepicker"
                                            data-date-format="dd/mm/yyyy" data-date-autoclose="true" wire:model="date"
                                            id="date-input">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group ">
                                        <label class="control-label">Select Shift</label>
                                        <select class="form-control select2-search-disable" id="shift">
                                            <option>Select Shift</option>
                                            <option value="D">D</option>
                                            <option value="S">S</option>
                                            <option value="N">N</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            {{--
                        </form> --}}
                    </div>
                    <div class="card-body">

                        <div id="accordion" class="custom-accordion">
                            @if ($data != null)
                            @foreach ($data as $indexPlan => $dt)
                            <div class="card mb-1 shadow-none" wire:key={{ $indexPlan }}>
                                <a href="#collapse{{ $indexPlan }}" class="text-dark " data-toggle="collapse"
                                    aria-expanded="false">
                                    <div class="card-header" id="heading{{ $indexPlan }}">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div>
                                                            <strong>
                                                                Furnace : {{ $dt['plan_furnace'] }}

                                                            </strong>
                                                        </div>
                                                        <div>
                                                            <strong>
                                                                Process date: {{ $dt['plan_process_date'] }}
                                                            </strong>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <div>
                                                            <strong>
                                                                Shift : {{ $dt['shift'] }}
                                                            </strong>
                                                        </div>
                                                        <div>
                                                            <strong>
                                                                Total Raw Material : 800
                                                            </strong>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4">
                                                        <div>
                                                            <strong>
                                                                Total Additive : 800
                                                            </strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <h6 class="mt-3">
                                                    <i class="mdi mdi-minus float-right accor-plus-icon"></i>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <div id="collapse{{ $indexPlan }}"
                                    class="collapse {{ $openIndex === $indexPlan ? 'show' : '' }}"
                                    aria-labelledby="heading{{ $indexPlan }}">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0">

                                                <thead>
                                                    <tr>
                                                        <th>Charging</th>
                                                        <th>Lot</th>
                                                        <th>Product</th>
                                                        <th style="width: 150px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($dt['chargings'] as $indexCharge => $charge)
                                                    <tr wire:key={{ $indexCharge }}>
                                                        <th>{{ $charge['charging'] ?? '-' }}</th>
                                                        <td>{{ $charge['lot'] }}</td>
                                                        <td>{{ $charge['model_id'] }}</td>
                                                        <td>
                                                            <div>
                                                                <button class="btn btn-info btn-sm waves-effect"
                                                                    wire:click='Detail({{ $indexPlan}}, {{ $indexCharge }})'
                                                                    @disabled(empty($charge['charging']))>
                                                                    <i class="fas fa-eye" data-toggle="tooltip"
                                                                        title="View"></i>
                                                                </button>
                                                                <button class="btn btn-warning btn-sm waves-effect "
                                                                    wire:click='Edit({{ $indexPlan}}, {{ $indexCharge }})'
                                                                    @disabled(empty($charge['charging']))>
                                                                    <i class="fas fa-edit" data-toggle="tooltip"
                                                                        title="Edit"></i>
                                                                </button>
                                                                <button class="btn btn-primary btn-sm waves-effect"
                                                                    wire:click='Proccess({{ $indexPlan}}, {{ $indexCharge }})'>
                                                                    <i class="fas fa-cogs" data-toggle="tooltip"
                                                                        title="Proccess"></i>
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
                            @endforeach

                            @else

                            <!-- 🚨 DATA KOSONG -->
                            <div class="card">
                                <div class="text-center py-5">
                                    <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                    <h5>Data Tidak ditemukan</h5>
                                </div>
                            </div>
                            @endif
                        </div>


                    </div>
                </div>
            </div>
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

        if ($el.hasClass('select2-hidden-accessible')) {
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