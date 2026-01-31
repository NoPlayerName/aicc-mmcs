<div wire:ignore.self class="modal fade bs-example-modal-xl" id="modal-inoculant-input" tabindex="-1" role="dialog"
    aria-labelledby="modal-inoculant-input-label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 95vw;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">Inoculant Input</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- <form wire:submit.prevent="save"> --}}
                    <div class="container">
                        {{-- <div class="row"> --}}
                            <div class="card">
                                <div class="card-body">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group ">
                                                    <label class="control-label">Furnace</label>
                                                    <select class="form-control select2-search-disable">
                                                        <option>Select</option>
                                                        <option>Furnace 6</option>
                                                        <option>Furnace 7</option>
                                                        <option>Furnace 8</option>
                                                        <option>Furnace 9</option>
                                                    </select>

                                                </div>
                                                <div class="form-group ">
                                                    <label for="" class="control-lable">Lot</label>
                                                    <input class="form-control" style="width: 4rem" type="text">
                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group ">
                                                    <label class="control-label">Product</label>
                                                    <select class="form-control select2-search-disable">
                                                        <option>Select</option>
                                                        <option>FLY WHEEL 2540</option>
                                                        <option>BEARING CAP 28A0 13H</option>
                                                        <option>FLY WHEEL 3790</option>
                                                        <option>FLY WHEEL 8930</option>
                                                    </select>
                                                </div>
                                                <div class="form-group ">
                                                    <label for="" class="control-lable">Molt tempt on ladle</label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group ">
                                                    <label for="" class="control-lable">Berat Molten</label>
                                                    <input class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                {{-- <label class="form-label fw-semibold">Status Proses</label> --}}

                                                <div class="border rounded p-3">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" id="timbangan">
                                                        <label class="form-check-label" for="timbangan">
                                                            Timbangan
                                                        </label>
                                                    </div>

                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" id="conveyor">
                                                        <label class="form-check-label" for="conveyor">
                                                            Mat turun ke Conveyor
                                                        </label>
                                                    </div>

                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" id="ladle">
                                                        <label class="form-check-label" for="ladle">
                                                            Mat turun ke Ladle
                                                        </label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="treatment">
                                                        <label class="form-check-label" for="treatment">
                                                            Lama Treatment &gt; 10 detik
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="col mb-3">
                                        <div class="row align-items-end">
                                            <div class="col-md-3">
                                                {{-- <div class="form-group "> --}}
                                                    <label class="control-label">Material</label>
                                                    <select class="form-control form-control-lg select2-search-disable">
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
                                        </div>
                                    </div>
                                    <div class="col">
                                        <table class="table table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Material Name</th>
                                                    <th>Weight</th>
                                                    <th style="width: 150px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>6</th>
                                                    <td>23</td>
                                                    <td>
                                                        <div>
                                                            <button class="btn btn-danger btn-sm waves-effect"
                                                                wire:click="editShow('${data}')">
                                                                <i class=" fas fa-trash-alt" data-toggle="tooltip"
                                                                    title="View"></i>
                                                            </button>

                                                        </div>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>6</th>
                                                    <td>23</td>
                                                    <td>
                                                        <div>
                                                            <button class="btn btn-danger btn-sm waves-effect"
                                                                wire:click="editShow('${data}')">
                                                                <i class=" fas fa-trash-alt" data-toggle="tooltip"
                                                                    title="View"></i>
                                                            </button>

                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>6</th>
                                                    <td>23</td>
                                                    <td>
                                                        <div>
                                                            <button class="btn btn-danger btn-sm waves-effect"
                                                                wire:click="editShow('${data}')">
                                                                <i class=" fas fa-trash-alt" data-toggle="tooltip"
                                                                    title="View"></i>
                                                            </button>

                                                        </div>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>6</th>
                                                    <td>23</td>
                                                    <td>
                                                        <div>
                                                            <button class="btn btn-danger btn-sm waves-effect"
                                                                wire:click="editShow('${data}')">
                                                                <i class=" fas fa-trash-alt" data-toggle="tooltip"
                                                                    title="View"></i>
                                                            </button>

                                                        </div>

                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{--
                        </div> --}}
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                    {{--
                </form> --}}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->