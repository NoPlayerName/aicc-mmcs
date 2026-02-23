@push('style')
{{-- custom style can be add here --}}
@endpush
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">ACE Material Input</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">ACE Material Input</a></li>
                            <li class="breadcrumb-item active">ACE</li>
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
                                            data-date-format="dd/mm/yyyy" data-date-autoclose="true">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group ">
                                        <label class="control-label">Select Shift</label>
                                        <select class="form-control select2-search-disable">
                                            <option>Select Shift</option>
                                            <option>D</option>
                                            <option>S</option>
                                            <option>N</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            {{--
                        </form> --}}
                    </div>
                    <div class="card-body">

                        {{-- The whole world belongs to you. --}}
                        <div class="row mb-2">
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-primary mb-2" wire:click="addFurnace"><i
                                        class="mdi mdi-plus mr-2"></i> Add
                                    Furnace</button>
                            </div>
                        </div>
                        @forelse ($furnace as $indexPlan => $item)
                        <div id="accordion" class="custom-accordion" wire:key='{{ $indexPlan }}'>
                            <div class="card mb-1 shadow-none">
                                <a href="#collapse{{ $indexPlan }}" class="text-dark" data-toggle="collapse"
                                    aria-expanded="true" aria-controls="collapse{{ $indexPlan }}">
                                    <div class="card-header" id="heading{{ $indexPlan }}">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="row">


                                                    <div class="col-lg-4">
                                                        <div>
                                                            <strong>
                                                                Furnace : {{ $item->furnace }}
                                                            </strong>
                                                        </div>
                                                        <div>
                                                            <strong>
                                                                Process date: {{ $item->date }}
                                                            </strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div>
                                                            <strong>
                                                                Shift : {{ $item->shift }}
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
                                    aria-labelledby=" heading{{ $indexPlan }}" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-sm-4">
                                                <button type="button" class="btn btn-primary mb-2"
                                                    wire:click="addCharge({{ $item->id }}, {{ $indexPlan }})"><i
                                                        class="mdi mdi-plus mr-2"></i> Add
                                                    Charging </button>
                                            </div>
                                        </div>
                                        <div class="table-responsive" wire:key='charging-{{ $indexPlan }}'>
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
                                                    @forelse ($item->chargings as $indexCharge => $charge)
                                                    <tr>
                                                        <th>{{ $charge->charging }}</th>
                                                        <td>{{ $charge->lot ?? '-' }}</td>
                                                        <td>{{ $charge->product->name ?? '-' }}</td>
                                                        <td>
                                                            <div>
                                                                <button class="btn btn-info btn-sm waves-effect"
                                                                    wire:click='Detail({{ $indexPlan}}, {{ $indexCharge }})'>
                                                                    <i class="fas fa-eye" data-toggle="tooltip"
                                                                        title="View"></i>
                                                                </button>
                                                                <button class="btn btn-warning btn-sm waves-effect"
                                                                    wire:click='Edit({{ $indexPlan}}, {{ $indexCharge }})'>
                                                                    <i class="fas fa-edit" data-toggle="tooltip"
                                                                        title="Edit"></i>
                                                                </button>
                                                                <button class="btn btn-primary btn-sm waves-effect"
                                                                    wire:click="Proccess({{ $indexPlan }}, {{ $indexCharge }})">
                                                                    <i class="fas fa-cogs" data-toggle="tooltip"
                                                                        title="Proccess"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted py-3">
                                                            No Charging Available
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
                        <div class="card">
                            <div class="text-center py-5">
                                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                <h5>Data Tidak ditemukan</h5>
                            </div>
                            @endforelse


                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- form modal --}}
        @livewire('ace.form-material-input')
    </div>
    @push('scripts')
    <script>
        function initAceDatepicker() {
        $('[data-provide="datepicker"]').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true
        }).off('changeDate.aceMaterialInput').on('changeDate.aceMaterialInput', function (e) {
            let selectedDate = e.format();
            Livewire.dispatch('Date', { data: selectedDate });
        });
    }

    function initSelect2WithDispatch(selector, options, eventName, payloadBuilder) {
        const $el = $(selector);
        if (!$el.length) return;

        if ($el.hasClass('select2-hidden-accessible')) {
            $el.select2('destroy');
        }

        $el.select2(options)
            .off('change.aceMaterialInput')
            .on('change.aceMaterialInput', function () {
                Livewire.dispatch(eventName, payloadBuilder($(this)));
            });
    }

    function initAceSelect() {
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

         initSelect2WithDispatch('#selectLotMultiple', {
            width: '100%',
            placeholder: 'Choose Lots...',
            allowClear: true,
            dropdownParent: $('#modal-material-input'),
            maximumSelectionLength: 3,
        }, 'lotSelection', ($el) => ({
            productId: $('#product-select2').val() ? Number($('#product-select2').val()) : null,
            lotIds: ($el.val() || []).map(v => Number(v))
        }));
        initSelect2WithDispatch('#product-select2', {
            width: '100%',
            placeholder: 'Choose Product...',
            allowClear: true,
            dropdownParent: $('#modal-material-input'),
            minimumResultsForSearch: 0,
        }, 'productSelect', ($el) => ({
            productId: $el.val() ? Number($el.val()) : null,
            lotIds: ($('#selectLotMultiple').val() || []).map(v => Number(v))
        }));
    }

    function bindAceMaterialInputHandlers() {
        initAceDatepicker();
        initAceSelect();

        $('#modal-material-input')
            .off('shown.bs.modal.aceMaterialInput')
            .on('shown.bs.modal.aceMaterialInput', function () {
                initAceSelect();
            });

        if (!window.__aceMaterialInputLivewireBound) {
            window.__aceMaterialInputLivewireBound = true;
            
             Livewire.on('showFormEdit', () => {
                $('#modal-material-input').modal('show');
            });

            Livewire.on('showFormInput', () => {
                $('#modal-material-input').modal('show');
                setTimeout(() => {
                    initAceSelect();
                }, 100);
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
        }
    }

    $(document).ready(function () {
        bindAceMaterialInputHandlers();
    });

    $(document).on('livewire:navigated', function () {
        bindAceMaterialInputHandlers();
    });
    </script>
    @endpush