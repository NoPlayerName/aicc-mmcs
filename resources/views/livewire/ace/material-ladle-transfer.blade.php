@push('style')
{{-- custom style can be add here --}}
@endpush
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">ACE Ladle Transfer</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">ACE Ladle Transfer</a></li>
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
                        <table class="table table-striped mb-0">
                            <div class="row mb-2">
                                <button class="btn btn-primary btn-md ml-2" wire:click="addInoculant"><i
                                        class="fas fa-plus mr-2"></i>Add Data</button>
                            </div>
                            <thead>
                                <tr>
                                    <th>Furnace</th>
                                    <th>Lot</th>
                                    <th>Product</th>
                                    <th style="width: 150px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>6</th>
                                    <td>23</td>
                                    <td>Bearing Holder</td>
                                    <td>
                                        <div>
                                            <button class="btn btn-info btn-sm waves-effect"
                                                wire:click="editShow('${data}')">
                                                <i class="fas fa-eye" data-toggle="tooltip" title="View"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm waves-effect"
                                                wire:click="editShow('${data}')">
                                                <i class="fas fa-edit" data-toggle="tooltip" title="Edit"></i>
                                            </button>

                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <th>6</th>
                                    <td>23</td>
                                    <td>Bearing Holder</td>
                                    <td>
                                        <div>
                                            <button class="btn btn-info btn-sm waves-effect"
                                                wire:click="editShow('${data}')">
                                                <i class="fas fa-eye" data-toggle="tooltip" title="View"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm waves-effect"
                                                wire:click="editShow('${data}')">
                                                <i class="fas fa-edit" data-toggle="tooltip" title="Edit"></i>
                                            </button>

                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <th>6</th>
                                    <td>23</td>
                                    <td>Bearing Holder</td>
                                    <td>
                                        <div>
                                            <button class="btn btn-info btn-sm waves-effect"
                                                wire:click="editShow('${data}')">
                                                <i class="fas fa-eye" data-toggle="tooltip" title="View"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm waves-effect"
                                                wire:click="editShow('${data}')">
                                                <i class="fas fa-edit" data-toggle="tooltip" title="Edit"></i>
                                            </button>

                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <th>6</th>
                                    <td>23</td>
                                    <td>Bearing Holder</td>
                                    <td>
                                        <div>
                                            <button class="btn btn-info btn-sm waves-effect"
                                                wire:click="editShow('${data}')">
                                                <i class="fas fa-eye" data-toggle="tooltip" title="View"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm waves-effect"
                                                wire:click="editShow('${data}')">
                                                <i class="fas fa-edit" data-toggle="tooltip" title="Edit"></i>
                                            </button>

                                        </div>

                                    </td>
                                </tr>

                            </tbody>
                        </table>



                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('ace.form-input-inoculant')
</div>
@push('scripts')
<script>
    $(document).on('livewire:navigated', () => {
        Livewire.on('showFormInoculant', () => {
            $('#modal-inoculant-input').modal("show");
        });
    })
</script>
@endpush