<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">

                    {{-- Dashboard --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">
                            <i class="ri-dashboard-line mr-2"></i> Dashboard
                        </a>
                    </li>

                    {{-- JSH Dropdown --}}
                    @if ($this->canView('/jsh/material-input', 'can_access') || $this->canView('/jsh/material-adjust',
                    'can_access') || $this->canView('/jsh/report/total-furnace', 'can_access') ||
                    $this->canView('/jsh/report/furnace-report', 'can_access') ||
                    $this->canView('/jsh/report/product-report', 'can_access') ||
                    $this->canView('/jsh/material-adjust-report', 'can_access'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-jsh" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-file-list-3-line mr-2"></i>JSH <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-jsh">
                            @if ($this->canView('/jsh/material-input', 'can_access'))
                            <a href="{{ route('jsh.material-input.index') }}" class="dropdown-item"
                                wire:navigate>Material Input</a>
                            @endif
                            @if ($this->canView('/jsh/material-adjust', 'can_access'))
                            <a href="{{ route('jsh.material-adjust.index') }}" class="dropdown-item"
                                wire:navigate>Material Adjust</a>
                            @endif
                            @if ($this->canView('/jsh/report/total-furnace', 'can_access') ||
                            $this->canView('/jsh/report/furnace-report', 'can_access') ||
                            $this->canView('/jsh/report/product-report', 'can_access'))
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Report</h6>
                            @if ($this->canView('/jsh/report/total-furnace', 'can_access'))
                            <a href="{{ route('jsh.report.total-furnace-report') }}" class="dropdown-item">All
                                Furnace</a>
                            @endif
                            @if ($this->canView('/jsh/report/furnace-report', 'can_access'))
                            <a href="{{ route('jsh.report.furnace-report') }}" class="dropdown-item">Furnace</a>
                            @endif
                            @if ($this->canView('/jsh/report/product-report', 'can_access'))
                            <a href="{{ route('jsh.report.product-report') }}" class="dropdown-item">Product</a>
                            @endif
                            @if ($this->canView('/jsh/material-adjust-report', 'can_access'))
                            <a href="{{ route('jsh.report.material-adjust-report') }}" class="dropdown-item"
                                wire:navigate>Material Adjust Report</a>
                            @endif
                            @endif
                        </div>
                    </li>
                    @endif

                    {{-- ACE Dropdown --}}
                    @if ($this->canView('/ace/material-input', 'can_access') || $this->canView('/ace/ladle-transfer',
                    'can_access') || $this->canView('/ace/material-adjust', 'can_access') ||
                    $this->canView('/ace/report/total-furnace', 'can_access') ||
                    $this->canView('/ace/report/furnace-report', 'can_access') ||
                    $this->canView('/ace/report/product-report', 'can_access') ||
                    $this->canView('/ace/report/ladle-transfer-report', 'can_access') ||
                    $this->canView('/ace/report/ladle-tf-adjust-report', 'can_access'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-ace" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-file-list-3-line mr-2"></i>ACE <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-ace">
                            @if ($this->canView('/ace/material-input', 'can_access'))
                            <a href="{{ route('ace.material-input-ace.index') }}" class="dropdown-item"
                                wire:navigate>Material Input</a>
                            @endif
                            @if ($this->canView('/ace/ladle-transfer', 'can_access'))
                            <a href="{{ route('ace.ladle-transfer.index') }}" class="dropdown-item" wire:navigate>Ladle
                                Transfer</a>
                            @endif
                            {{-- @if ($this->canView('/ace/ladle-tf-adjust', 'can_access'))
                            <a href="{{ route('ace.ladle-tf-adjust.index') }}" class="dropdown-item" wire:navigate>Ladle
                                TF Adjust</a>
                            @endif --}}
                            @if ($this->canView('/ace/material-adjust', 'can_access'))
                            <a href="{{ route('ace.material-adjust-ace.index') }}" class="dropdown-item"
                                wire:navigate>Material Adjust</a>
                            @endif
                            @if ($this->canView('/ace/report/total-furnace', 'can_access') ||
                            $this->canView('/ace/report/furnace-report', 'can_access') ||
                            $this->canView('/ace/report/product-report', 'can_access') ||
                            $this->canView('/ace/report/ladle-transfer-report', 'can_access') ||
                            $this->canView('/ace/report/ladle-tf-adjust-report', 'can_access'))
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Report</h6>
                            @if ($this->canView('/ace/report/total-furnace', 'can_access'))
                            <a href="{{ route('ace.report.total-furnace-report') }}" class="dropdown-item">All
                                Furnace</a>
                            @endif
                            @if ($this->canView('/ace/report/furnace-report', 'can_access'))
                            <a href="{{ route('ace.report.furnace-report') }}" class="dropdown-item">Furnace</a>
                            @endif
                            @if ($this->canView('/ace/report/product-report', 'can_access'))
                            <a href="{{ route('ace.report.product-report') }}" class="dropdown-item">Product</a>
                            @endif
                            @if ($this->canView('/ace/report/ladle-transfer-report', 'can_access'))
                            <a href="{{ route('ace.report.ladle-transfer-report') }}" class="dropdown-item"
                                wire:navigate>Ladle Transfer Report</a>
                            @endif
                            {{-- @if ($this->canView('/ace/report/ladle-tf-adjust-report', 'can_access'))
                            <a href="{{ route('ace.report.ladle-tf-adjust-report') }}" class="dropdown-item"
                                wire:navigate>Ladle TF Adjust Report</a>
                            @endif --}}
                            @if ($this->canView('/ace/report/material-adjust-report', 'can_access'))
                            <a href="{{ route('ace.report.material-adjust-report') }}" class="dropdown-item"
                                wire:navigate>Material Adjust Report</a>
                            @endif
                            @endif
                        </div>
                    </li>
                    @endif

                </ul>
            </div>
        </nav>
    </div>
</div>