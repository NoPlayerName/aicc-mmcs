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
                    @if ($this->canView('/jsh/material-input', 'can_access'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('jsh.material-input.index') }}" wire:navigate>
                            <i class="ri-file-list-3-line mr-2"></i> JSH
                        </a>
                    </li>
                    @endif
                    @if ($this->canView('/ace/material-input', 'can_access') || $this->canView('/ace/ladle-transfer',
                    'can_access'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class=" ri-file-list-3-line mr-2"></i>ACE <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
                            @if ($this->canView('/ace/material-input', 'can_access'))
                            <a href="{{ route('ace.material-input-ace.index') }}" class="dropdown-item"
                                wire:navigate>Material
                                Input</a>
                            @endif
                            @if ($this->canView('/ace/ladle-transfer',
                            'can_access'))
                            <a href="{{ route('ace.ladle-transfer.index') }}" class="dropdown-item " wire:navigate>Ladle
                                Transfer</a>
                            @endif
                        </div>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" href="#" wire:navigate>
                            <i class="ri-file-chart-line mr-2"></i> Transaction Adjust
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-file-chart-line mr-2"></i>JSH Report <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
                            <a href="{{ route('jsh.report.total-furnace-report') }}" class="dropdown-item">All
                                Furnace</a>
                            <a href="{{ route('jsh.report.furnace-report') }}" class="dropdown-item">Furnace</a>
                            <a href="{{ route('jsh.report.product-report') }}" class="dropdown-item">Product</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-file-chart-line mr-2"></i>ACE Report <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="topnav-layout">
                            <a href="{{ route('ace.report.total-furnace-report') }}" class="dropdown-item">All
                                Furnace</a>
                            <a href="{{ route('ace.report.furnace-report') }}" class="dropdown-item">Furnace</a>
                            <a href="{{ route('ace.report.product-report') }}" class="dropdown-item">Product</a>
                        </div>
                    </li>



                </ul>
            </div>
        </nav>
    </div>
</div>