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

                        <div id="accordion" class="custom-accordion">
                            <div class="card mb-1 shadow-none">
                                <a href="#collapseOne" class="text-dark" data-toggle="collapse" aria-expanded="true"
                                    aria-controls="collapseOne">
                                    <div class="card-header" id="headingOne">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="row">


                                                    <div class="col-lg-4">
                                                        <div>
                                                            <strong>
                                                                Furnace : 1
                                                            </strong>
                                                        </div>
                                                        <div>
                                                            <strong>
                                                                Process date: 17/12/2025
                                                            </strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div>
                                                            <strong>
                                                                Shift : D
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

                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                    data-parent="#accordion">
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
                                                    <tr>
                                                        <th>1</th>
                                                        <td>1, 2, 3</td>
                                                        <td>ES30</td>
                                                        <td>
                                                            <div>
                                                                <button class="btn btn-info btn-sm waves-effect">
                                                                    <i class="fas fa-eye" data-toggle="tooltip"
                                                                        title="View"></i>
                                                                </button>
                                                                <button class="btn btn-warning btn-sm waves-effect">
                                                                    <i class="fas fa-edit" data-toggle="tooltip"
                                                                        title="Edit"></i>
                                                                </button>
                                                                <button class="btn btn-primary btn-sm waves-effect"
                                                                    wire:click="Proccess">
                                                                    <i class="fas fa-cogs" data-toggle="tooltip"
                                                                        title="Proccess"></i>
                                                                </button>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>2</th>
                                                        <td>4, 5, 6</td>
                                                        <td>EJ40</td>
                                                        <td>
                                                            <div>
                                                                <button class="btn btn-info btn-sm waves-effect">
                                                                    <i class="fas fa-eye" data-toggle="tooltip"
                                                                        title="View"></i>
                                                                </button>
                                                                <button class="btn btn-warning btn-sm waves-effect">
                                                                    <i class="fas fa-edit" data-toggle="tooltip"
                                                                        title="Edit"></i>
                                                                </button>
                                                                <button class="btn btn-primary btn-sm waves-effect">
                                                                    <i class="fas fa-cogs" data-toggle="tooltip"
                                                                        title="Proccess"></i>
                                                                </button>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>3</th>
                                                        <td>6, 7, 8</td>
                                                        <td>ES30</td>
                                                        <td>
                                                            {{-- <div> --}}
                                                            <button class="btn btn-info btn-sm waves-effect">
                                                                <i class="fas fa-eye" data-toggle="tooltip"
                                                                    title="View"></i>
                                                            </button>
                                                            <button class="btn btn-warning btn-sm waves-effect">
                                                                <i class="fas fa-edit" data-toggle="tooltip"
                                                                    title="Edit"></i>
                                                            </button>
                                                            <button class="btn btn-primary btn-sm waves-effect">
                                                                <i class="fas fa-cogs" data-toggle="tooltip"
                                                                    title="Proccess"></i>
                                                            </button>
                                                            {{--
                                                            </div> --}}

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-1 shadow-none">
                                <a href="#collapseTwo" class="text-dark collapsed" data-toggle="collapse"
                                    aria-expanded="false" aria-controls="collapseTwo">
                                    <div class="card-header" id="headingTwo">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div>
                                                            <strong>
                                                                Furnace : 2
                                                            </strong>
                                                        </div>
                                                        <div>
                                                            <strong>
                                                                Process date: 17/12/2025
                                                            </strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div>
                                                            <strong>
                                                                Shift : D
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
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                    data-parent="#accordion">
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
                                                    <tr>
                                                        <th>1</th>
                                                        <td>9, 10, 11</td>
                                                        <td>ES30</td>
                                                        <td>
                                                            <div>
                                                                <button class="btn btn-info btn-sm waves-effect">
                                                                    <i class="fas fa-eye" data-toggle="tooltip"
                                                                        title="View"></i>
                                                                </button>
                                                                <button class="btn btn-warning btn-sm waves-effect">
                                                                    <i class="fas fa-edit" data-toggle="tooltip"
                                                                        title="Edit"></i>
                                                                </button>
                                                                <button class="btn btn-primary btn-sm waves-effect">
                                                                    <i class="fas fa-cogs" data-toggle="tooltip"
                                                                        title="Proccess"></i>
                                                                </button>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>2</th>
                                                        <td>12, 13, 14</td>
                                                        <td>EJ40</td>
                                                        <td>
                                                            <div>
                                                                <button class="btn btn-info btn-sm waves-effect">
                                                                    <i class="fas fa-eye" data-toggle="tooltip"
                                                                        title="View"></i>
                                                                </button>
                                                                <button class="btn btn-warning btn-sm waves-effect">
                                                                    <i class="fas fa-edit" data-toggle="tooltip"
                                                                        title="Edit"></i>
                                                                </button>
                                                                <button class="btn btn-primary btn-sm waves-effect">
                                                                    <i class="fas fa-cogs" data-toggle="tooltip"
                                                                        title="Proccess"></i>
                                                                </button>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>3</th>
                                                        <td>15, 16, 17</td>
                                                        <td>ES30</td>
                                                        <td>
                                                            {{-- <div> --}}
                                                            <button class="btn btn-info btn-sm waves-effect">
                                                                <i class="fas fa-eye" data-toggle="tooltip"
                                                                    title="View"></i>
                                                            </button>
                                                            <button class="btn btn-warning btn-sm waves-effect">
                                                                <i class="fas fa-edit" data-toggle="tooltip"
                                                                    title="Edit"></i>
                                                            </button>
                                                            <button class="btn btn-primary btn-sm waves-effect">
                                                                <i class="fas fa-cogs" data-toggle="tooltip"
                                                                    title="Proccess"></i>
                                                            </button>
                                                            {{--
                                                            </div> --}}

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-1 shadow-none">
                                <a href="#collapseThree" class="text-dark collapsed" data-toggle="collapse"
                                    aria-expanded="false" aria-controls="collapseThree">
                                    <div class="card-header" id="headingThree">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div>
                                                            <strong>
                                                                Furnace : 2
                                                            </strong>
                                                        </div>
                                                        <div>
                                                            <strong>
                                                                Process date: 17/12/2025
                                                            </strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div>
                                                            <strong>
                                                                Shift : D
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
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                    data-parent="#accordion">
                                    <div class="card-body">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life
                                        accusamus terry richardson ad squid. 3 wolf moon officia
                                        aute, non cupidatat skateboard dolor brunch. Food truck
                                        sunt aliqua put a bird on it squid single-origin coffee
                                        nulla assumenda anderson cred nesciunt
                                    </div>
                                </div>
                            </div>
                        </div>


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
    $(document).on('livewire:navigated', () => {
        Livewire.on('showFormInput', () => {
            $('#modal-material-input').modal("show");
        });
    })
</script>
@endpush