@push('style')
<style>
    /* Styling untuk baris tabel agar lebih bersih */
    .table-centered th,
    .table-centered td {
        vertical-align: middle !important;
    }

    .card-header-flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .bg-soft-light {
        background-color: #f8f9fa;
    }

    .btn-group-action .btn {
        border-radius: 6px;
        margin: 0 2px;
    }
</style>
@endpush

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">ACE Ladle Transfer</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Ace</a></li>
                            <li class="breadcrumb-item active">ACE Ladle Transfer</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-bold"><i class="mdi mdi-calendar mr-1"></i>Select
                                Date</label>
                            <input type="text" id="ladle-transfer-date" class="form-control form-control-md"
                                data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true"
                                placeholder="Choose Date">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label class="font-weight-bold"><i class="mdi mdi-clock-outline mr-1"></i>Select
                                Shift</label>
                            <select class="form-control form-control-md" id="ace-ladle-shift">
                                <option value="">Select</option>
                                <option value="D">D </option>
                                <option value="S">S </option>
                                <option value="N">N </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <button type="button" id="btn-add-inoculant"
                            class="btn btn-primary btn-lg waves-effect waves-light shadow-sm" wire:click="addInoculant">
                            <i class="mdi mdi-plus-circle mr-2"></i>Add Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered table-nowrap mb-0">
                                <thead class="bg-soft-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="px-4 py-3 border-0">Furnace No.</th>
                                        <th class="border-0">Lot Number</th>
                                        <th class="border-0">Product Name</th>
                                        <th class="border-0 text-right px-4" style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dataLadle as $index => $item)
                                    <tr wire:key="ladle-row-{{ $item->id }}">
                                        <th class="px-4">
                                            <span class="avatar-xs d-inline-block mr-2">
                                                <span
                                                    class="avatar-title bg-soft-primary text-primary rounded-circle">{{
                                                    $item->furnace?->furnace ?? '-' }}</span>
                                            </span>
                                        </th>
                                        <td><span class="badge badge-soft-dark p-2 font-size-12">{{ $item->lot }}</span>
                                        </td>
                                        <td class="font-weight-medium">{{ $item->product?->alias ?? '-' }}</td>
                                        <td class="text-right px-4">
                                            <div class="btn-group-action">
                                                <button type="button" class="btn btn-info btn-sm waves-effect"
                                                    wire:click="showFormDetail({{ $item->id }})">
                                                    <i class="fas fa-eye" data-toggle="tooltip" title="View"></i>
                                                </button>
                                                <button class="btn btn-warning btn-sm waves-effect"
                                                    wire:click="showFormEdit({{ $item->id }})">
                                                    <i class="fas fa-edit" data-toggle="tooltip" title="Edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    @empty
                                    <tr wire:key="empty-row" class="bg-light">
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="mdi mdi-database-off mdi-48px"></i>
                                            <p class="mb-0 mt-2">No data available for the selected date and shift.</p>
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
    </div>

    {{-- form modal --}}
</div>
<livewire:ace.form-input-inoculant :key="'ace-form-input-inoculant'" />
<livewire:ace.form-edit-inoculant :key="'ace-form-edit-inoculant'" />
<livewire:ace.ladle-transfer-detail :key="'ace-ladle-transfer-detail'" />

@push('scripts')
<script>
    window.__aceEditInoculantSync = window.__aceEditInoculantSync || { furnace: null, product: null };

    function cleanupLadleSelect2ById(id) {
        if (!id) return;

        const $el = $(`#${id}`);
        if ($el.length && $el.hasClass('select2-hidden-accessible') && $el.data('select2')) {
            $el.select2('destroy');
        }

        $el.next('.select2-container').remove();
        $(`#select2-${id}-container`).closest('.select2-container').remove();
        $(`[aria-labelledby="select2-${id}-container"]`).closest('.select2-container').remove();
    }

    function cleanupLadleSelect2Artifacts() {
        [
            'ace-ladle-shift',
            'selectFurnace',
            'material',
            'material-edit',
            'Type-Adjust-select2',
            'Type-Tapping-select2',
            'product-select2',
            'product-select2-edit',
            'selectFurnaceEdit',
            'rawMat-select2',
            'Additive-select2'
        ].forEach(cleanupLadleSelect2ById);
    }

    function applyEditInoculantSync() {
        const sync = window.__aceEditInoculantSync || {};

        const $furnace = $('#selectFurnaceEdit');
        if ($furnace.length) {
            $furnace.val(sync.furnace ?? '').trigger('change');
        }

        const $product = $('#product-select2-edit');
        if ($product.length) {
            $product.val(sync.product ?? '').trigger('change');
        }
    }

    function initDatepicker() {
        $('#ladle-transfer-date').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true
        }).off('changeDate.aceMaterialInput').on('changeDate.aceMaterialInput', function (e) {
            let selectedDate = e.format();
            Livewire.dispatch('Date', { data: selectedDate, shift: $('#ace-ladle-shift').val() || null });
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
            $(`.select2-container:has([id="select2-${elementId}-container"])`).remove();
        }

        if (!$el.hasClass('select2-hidden-accessible')) {
            $el.next('.select2-container').remove();
        }

        if ($el.hasClass('select2-hidden-accessible') && $el.data('select2')) {
            $el.select2('destroy');
        }

        $el.select2(select2Options)
            .off('change.aceMaterialInput')
            .on('change.aceMaterialInput', function () {
                Livewire.dispatch(eventName, payloadBuilder($(this)));
            });
    }
    function initSelectLadleTf() {
        initSelect2WithDispatch('#ace-ladle-shift', {
            minimumResultsForSearch: Infinity
        }, 'Shift', ($el) => ({
            data: $el.val(),
             date: $('#ladle-transfer-date').val() || null
        }));
         initSelect2WithDispatch('#selectFurnace', {
            minimumResultsForSearch: Infinity
        }, 'selectFurnace', ($el) => ({
            furnace: $el.val(),
            // name: $el.find('option:selected').text()
        }), '#modal-inoculant-input');
          initSelect2WithDispatch('#material', {
            minimumResultsForSearch: 0
        }, 'materialSelect', ($el) => ({
            data: $el.val(),
            name: $el.find('option:selected').text()
        }), '#modal-inoculant-input');

        initSelect2WithDispatch('#material-edit', {
            minimumResultsForSearch: 0,
            dropdownParent: $('#modal-inoculant-edit'),
        }, 'materialSelectEdit', ($el) => ({
            data: $el.val(),
            name: $el.find('option:selected').text()
        }), '#modal-inoculant-edit');
        
        initSelect2WithDispatch('#Type-Adjust-select2', {}, 'typeAddjust', ($el) => ({
            data: $el.val()
        }), '#modal-inoculant-input');

         initSelect2WithDispatch('#Type-Tapping-select2', {}, 'typeTapping', ($el) => ({
            data: $el.val()
        }), '#modal-inoculant-input');

        initSelect2WithDispatch('#product-select2', {
            width: '100%',
            placeholder: 'Choose Product...',
            allowClear: true,
            dropdownParent: $('#modal-inoculant-input'),
            minimumResultsForSearch: 0,
        }, 'productSelect', ($el) => ({
            productId: $el.val() ? Number($el.val()) : null,
        }), '#modal-inoculant-input');

        initSelect2WithDispatch('#product-select2-edit', {
            width: '100%',
            placeholder: 'Choose Product...',
            allowClear: true,
            dropdownParent: $('#modal-inoculant-edit'),
            minimumResultsForSearch: 0,
        }, 'productSelectEdit', ($el) => ({
            productId: $el.val() ? Number($el.val()) : null,
        }), '#modal-inoculant-edit');

        initSelect2WithDispatch('#selectFurnaceEdit', {
            minimumResultsForSearch: Infinity,
            dropdownParent: $('#modal-inoculant-edit'),
        }, 'selectFurnaceEdit', ($el) => ({
            furnace: $el.val(),
        }), '#modal-inoculant-edit');
    }

    function bindAceMaterialLadleTransferHandlers() {
        cleanupLadleSelect2Artifacts();
        initDatepicker();
        initSelectLadleTf();

        $('#modal-inoculant-input')
            .off('shown.bs.modal.aceMaterialInput hide.bs.modal.aceMaterialInput hidden.bs.modal.aceMaterialInput')
            .on('shown.bs.modal.aceMaterialInput', function () {
                initSelectLadleTf();
            })
            .on('hide.bs.modal.aceMaterialInput', function () {
                const activeElement = document.activeElement;
                if (activeElement && this.contains(activeElement)) {
                    activeElement.blur();
                }
            })
            .on('hidden.bs.modal.aceMaterialInput', function () {
                if (window.__aceInoculantTrigger && window.__aceInoculantTrigger.length) {
                    window.__aceInoculantTrigger.trigger('focus');
                }
            });

        $('#modal-inoculant-edit')
            .off('shown.bs.modal.aceMaterialInput hide.bs.modal.aceMaterialInput hidden.bs.modal.aceMaterialInput')
            .on('shown.bs.modal.aceMaterialInput', function () {
                initSelectLadleTf();
                applyEditInoculantSync();
            })
            .on('hide.bs.modal.aceMaterialInput', function () {
                const activeElement = document.activeElement;
                if (activeElement && this.contains(activeElement)) {
                    activeElement.blur();
                }
            })
            .on('hidden.bs.modal.aceMaterialInput', function () {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('padding-right', '');

                if (window.__aceRefreshAfterEditSave) {
                    window.__aceRefreshAfterEditSave = false;
                    Livewire.dispatch('refreshLadleTransfer');
                }
            });

        $('#modal-ladle-transfer-detail')
            .off('hide.bs.modal.aceMaterialInput hidden.bs.modal.aceMaterialInput')
            .on('hide.bs.modal.aceMaterialInput', function () {
                const activeElement = document.activeElement;
                if (activeElement && this.contains(activeElement)) {
                    activeElement.blur();
                }
            })
            .on('hidden.bs.modal.aceMaterialInput', function () {
                if (window.__aceLadleTransferDetailTrigger && window.__aceLadleTransferDetailTrigger.length) {
                    window.__aceLadleTransferDetailTrigger.trigger('focus');
                }
            });
            
           

        if (!window.__aceMaterialLadleTransferLivewireBound) {
            window.__aceMaterialLadleTransferLivewireBound = true;
            
             Livewire.on('showFormEdit', () => {
                // window.__aceInoculantTrigger = $('#btn-add-inoculant');
                $('#modal-inoculant-edit').modal('show');
            });

            Livewire.on('syncEditInoculantSelect', ({ furnace, product }) => {
                window.__aceEditInoculantSync = {
                    furnace: furnace ?? null,
                    product: product ?? null,
                };
                applyEditInoculantSync();
            });
            Livewire.on('savedInoculant', () => {
                            $('#modal-inoculant-input').modal('hide');
                            $('#modal-inoculant-edit').modal('hide');
                        });

            Livewire.on('showLadleTransferDetailModal', () => {
                window.__aceLadleTransferDetailTrigger = $(document.activeElement);
                $('#modal-ladle-transfer-detail').modal('show');
            });

            Livewire.on('refreshLadleTransferClient', () => {
                window.__aceRefreshAfterEditSave = true;
            });
            // Livewire.on('showFormInput', () => {
            //     window.__aceInoculantTrigger = $('#btn-add-inoculant');
            //     $('#modal-inoculant-input').modal('show');
            //     setTimeout(() => {
            //         initSelectLadleTf();
            //     }, 100);
            // });

             Livewire.on('showFormInoculant', () => {
            window.__aceInoculantTrigger = $('#btn-add-inoculant');
            $('#modal-inoculant-input').modal("show");
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

            Livewire.on('resetMaterialSelect', () => {
                const $material = $('#modal-inoculant-input').find('#material').first();
                if (!$material.length) return;

                if ($material.hasClass('select2-hidden-accessible')) {
                    $material.val('').trigger('change');
                    return;
                }
                $material.val('');
            });
            Livewire.on('resetMaterialSelectUpdate', () => {
                const $material = $('#modal-inoculant-edit').find('#material-edit').first();
                if (!$material.length) return;

                if ($material.hasClass('select2-hidden-accessible')) {
                    $material.val('').trigger('change');
                    return;
                }
                $material.val('');
            });
            Livewire.on('resetFurnace', () => {
                const $material = $('#modal-inoculant-input').find('#selectFurnace').first();
                if (!$material.length) return;

                if ($material.hasClass('select2-hidden-accessible')) {
                    $material.val('').trigger('change');
                    return;
                }
                $material.val('');
            });
            Livewire.on('resetProduct', () => {
                const $material = $('#modal-inoculant-input').find('#product-select2').first();
                if (!$material.length) return;

                if ($material.hasClass('select2-hidden-accessible')) {
                    $material.val('').trigger('change');
                    return;
                }
                $material.val('');
            });
        }
    }

    function enterAceMaterialLadleTransferPage() {
        const pageKey = `${window.location.pathname}${window.location.search}`;
        if (window.__aceMaterialLadleTransferInitKey === pageKey) {
            return;
        }

        window.__aceMaterialLadleTransferInitKey = pageKey;
        bindAceMaterialLadleTransferHandlers();
    }

    $(document)
        .off('livewire:navigating.aceMaterialLadleTransferPage')
        .on('livewire:navigating.aceMaterialLadleTransferPage', function () {
            window.__aceMaterialLadleTransferInitKey = null;
            cleanupLadleSelect2Artifacts();
        });

    $(document)
        .off('livewire:navigated.aceMaterialLadleTransferPage')
        .on('livewire:navigated.aceMaterialLadleTransferPage', function () {
            enterAceMaterialLadleTransferPage();
        });

    enterAceMaterialLadleTransferPage();
</script>
@endpush