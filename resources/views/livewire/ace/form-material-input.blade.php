<div wire:ignore.self class="modal fade bs-example-modal-xl" id="modal-material-input" tabindex="-1" role="dialog"
    aria-labelledby="modal-material-input-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">Material Input Furnace</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- <form wire:submit.prevent="save"> --}}
                    <div class="container">
                        {{-- <div class="row"> --}}
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <h5>
                                                Lot: 1, 2, 3
                                            </h5>
                                            <div class="row align-items-center">

                                                <div class="col-auto">
                                                    <h5 class="mb-0">Charging:</h5>
                                                </div>

                                                <div class="col-auto">
                                                    <input type="text" class="form-control form-control-md"
                                                        style="width: 4rem">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <h5>
                                                Product: ES30
                                            </h5>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#rawMaterial" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                <span class="d-none d-sm-block">
                                                    <h5>
                                                        Raw Material</h5>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#additive" role="tab">
                                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                                <span class="d-none d-sm-block">
                                                    <h5>
                                                        Additive
                                                    </h5>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kwh" role="tab">
                                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                                <span class="d-none d-sm-block">
                                                    <h5>
                                                        KWH
                                                    </h5>
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#temptTapping" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                                <span class="d-none d-sm-block">
                                                    <h5>
                                                        Tempt Tapping
                                                    </h5>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="rawMaterial" role="tabpanel">
                                            <div class="row align-items-end">
                                                <div class="col-md-3">
                                                    {{-- <div class="form-group "> --}}
                                                        <label class="control-label">Material</label>
                                                        <select
                                                            class="form-control form-control-lg select2-search-disable">
                                                            <option>Select Material</option>
                                                            <option>Select Shift</option>
                                                            <option>Steel Scrap</option>
                                                            <option>Bricket</option>
                                                            <optgroup label="Return Scrap">
                                                                <option value="CA">RS ACE</option>
                                                                <option value="NV">AGARI</option>
                                                                <option value="OR">NG Prod</option>
                                                            </optgroup>
                                                            <option>N</option>
                                                        </select>
                                                        {{--
                                                    </div> --}}
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="" class="control-lable">Weight (KG)</label>
                                                    <input class="form-control form-control-lg" type="text"
                                                        placeholder="Kg">
                                                </div>
                                                <div class="col-md-auto">
                                                    <button class="btn btn-lg btn-primary" data-toggle="tooltip"
                                                        title="Add Material"> <i class="fas fa-plus"></i> Add</button>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="" class="control-lable">Total Material</label>
                                                    <input class="form-control form-control-lg" type="text"
                                                        placeholder="Total" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="additive" role="tabpanel">
                                            <div class="col">
                                                <div class="row align-items-end">
                                                    <div class="col-md-3">
                                                        {{-- <div class="form-group "> --}}
                                                            <label class="control-label">Material</label>
                                                            <select
                                                                class="form-control form-control-lg select2-search-disable">
                                                                <option>Select Additive</option>
                                                                <option>Carbon G-8</option>
                                                                <option>Carbon SP-500</option>
                                                                <option>Fe.Si</option>
                                                            </select>
                                                            {{--
                                                        </div> --}}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="" class="control-lable">Weight (KG)</label>
                                                        <input class="form-control form-control-lg" type="text"
                                                            placeholder="Kg">
                                                    </div>
                                                    <div class="col-md-auto">
                                                        <button class="btn btn-lg btn-primary" data-toggle="tooltip"
                                                            title="Add Material"> <i class="fas fa-plus"></i>
                                                            Add</button>
                                                    </div>


                                                </div>
                                                <div class="align-items-end">
                                                    <a href="#"
                                                        class="btn btn-primary waves-effect waves-light">Button</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="kwh" role="tabpanel">
                                            <div class="row align-items-end">
                                                <div class="col-md-3">
                                                    <label for="" class="control-lable">Charging Time</label>
                                                    <input class="form-control form-control-lg" type="text">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="" class="control-lable">KWH Start Charge</label>
                                                    <input class="form-control form-control-lg" type="text">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="" class="control-lable">KWH Ok Charge</label>
                                                    <input class="form-control form-control-lg" type="text">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="" class="control-lable">Power</label>
                                                    <input class="form-control form-control-lg" type="text">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="tab-pane" id="temptTapping" role="tabpanel">
                                            <div class="row align-items-end">
                                                <div class="col-md-3">
                                                    <label for="" class="control-lable">Temperatur</label>
                                                    <input class="form-control form-control-lg" type="text">
                                                </div>
                                                <div class="col-md-3">
                                                    {{-- <div class="form-group "> --}}
                                                        <label class="control-label">Type Tapping</label>
                                                        <select
                                                            class="form-control form-control-lg select2-search-disable">
                                                            <option>Select Type Tapping</option>
                                                            <option>Sample 1</option>
                                                            <option>Sample 2</option>
                                                            <option>Taping 1</option>
                                                            <option>Taping 2</option>
                                                        </select>
                                                        {{--
                                                    </div> --}}
                                                </div>
                                                <div class="col-md-auto">
                                                    <button class="btn btn-lg btn-primary" data-toggle="tooltip"
                                                        title="Add Material"> <i class="fas fa-plus"></i> Add</button>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--
                        </div> --}}
                    </div>


                    {{--
                </form> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>