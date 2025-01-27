<div>
    <div class="nav-buttons mt-2 d-flex justify-content-center">
        <ul class="nav custom-nav-tabs border">
            <li class="custom-item m-0 p-0 flex-grow-1 mbl-dev-active">
                <div class="reviewActiveButtons custom-nav-link {{ $activeSection === 'All' ? 'active' : '' }}" wire:click.prevent="toggleSection('All')">All</div>
            </li>
            <li class="pendingCustomStyles custom-item m-0 p-0 flex-grow-1">
                <a href="#" class="custom-nav-link {{ $activeSection === 'Favorites' ? 'active' : '' }}" wire:click.prevent="toggleSection('Favorites')">Favorites</a>
            </li>
        </ul>
    </div>

    <!-- Content Tabs -->
    <div class="tab-content mt-3">
        <!-- all Tab -->
        <div class="tab-pane {{ $activeSection === 'All' ? 'active' : '' }}" id="apply-section">
            <div class="row m-0 px-2">
                @foreach($reportsGallery->groupBy('category') as $category => $reports)
                <div class="col-md-4">
                    <div class="allreports">
                        <div class="allreports-header">
                            <i class="bi bi-file-earmark-text"></i> {{ ucfirst(strtolower($category)) }}
                        </div>
                        <div class="allreports-body">
                            <ul>
                                @foreach($reports as $report)
                                <li>
                                    {{ $report->description }}
                                    <span class="star-icon" wire:click="toggleStarred({{ $report->id }})">
                                        @if($report->favorite)
                                        <i class="ph-star-fill"></i> <!-- Filled star if favorite is true -->
                                        @else
                                        <i class="ph-star-bold"></i> <!-- Bold star if favorite is false -->
                                        @endif
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

        <!-- Pending Tab -->
        <div class="tab-pane {{ $activeSection === 'Favorites' ? 'active' : '' }}" id="pending-section">
            <div class="row m-0 px-2">
                @foreach($reportsGallery->groupBy('category') as $category => $reports)
                <div class="col-md-4">
                    <div class="allreports">
                        <div class="allreports-header">
                            <i class="bi bi-file-earmark-text"></i> {{ ucfirst(strtolower($category)) }}
                        </div>
                        <div class="allreports-body">
                            @php
                            $starredReports = $reports->where('favorite', true);
                            @endphp
                            @if($starredReports->isEmpty())
                            <p>Not added any fav</p> <!-- Message when no starred reports exist -->
                            @else
                            <ul>
                                @foreach($starredReports as $report)
                                <li>
                                    {{ $report->description }}
                                    <span class="star-icon">
                                        @if($report->favorite)
                                        <i class="ph-star-fill"></i> <!-- Filled star if favorite is true -->
                                        @else
                                        <i class="ph-star-bold"></i> <!-- Bold star if favorite is false -->
                                        @endif
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>