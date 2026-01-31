<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">
                            <i class="ri-dashboard-line mr-2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('jsh.material-input.index') }}" wire:navigate>
                            <i class="ri-file-list-3-line mr-2"></i> JSH
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class=" ri-file-list-3-line mr-2"></i>ACE <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
                            <a href="{{ route('ace.material-input-ace.index') }}" class="dropdown-item"
                                wire:navigate>Material
                                Input</a>
                            <a href="{{ route('ace.ladle-transfer.index') }}" class="dropdown-item " wire:navigate>Ladle
                                Transfer</a>

                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#" wire:navigate>
                            <i class="ri-file-chart-line mr-2"></i> Transaction Adjust
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-file-chart-line mr-2"></i>Report <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
                            <a href="#report/asakai-finishing" class="dropdown-item">Melting Report JSH</a>
                            <a href="#report/asakai-finishing" class="dropdown-item">Melting Report ACE</a>
                            <a href="#report/asakai-finishing" class="dropdown-item">Ladle Transfer Report</a>
                        </div>
                    </li>



                </ul>
            </div>
        </nav>
    </div>
</div>