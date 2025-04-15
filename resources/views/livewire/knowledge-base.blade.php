<div>
<style>
    .search-bar {
    display: flex;
    align-items: center;
    padding: 5px; /* Reduced padding */
    background-color: rgb;
    color: white;
    border-radius: 5px;
    width: 100%; /* Make the search bar occupy full width */
    box-sizing: border-box; /* Include padding and border in the element's total width and height */
}

.search-bar a {
    color: white;
    text-decoration: none;
    margin-right: 10px;
}

.search-bar input[type="text"] {
    padding: 5px; /* Reduced padding */
    border: none;
    border-radius: 5px;
    margin-left: 10px;
    height: 30px; /* Set a specific height for the input */
    width: calc(100% - 120px); /* Calculate the width based on the full width minus the width of the home and search links */
    box-sizing: border-box; /* Include padding and border in the element's total width and height */
}
    .search-results {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .article-list {
        margin-top: 20px;
    }
    .article {
        background-color: #ffffff;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
    }
    .article-icon {
        margin-right: 10px;
    }
    .article-link {
        color: #007bff;
        text-decoration: none;
    }
    .article-link:hover {
        text-decoration: underline;
    }
    .badge {
        margin-left: 10px;
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }
</style>
    <div style="padding-top: 20px;">
            <img src="{{ asset('images/hr_new_blue.png') }}" alt="Company Logo" style="width: 12em !important; height: auto !important; margin-bottom: 10px;">
        </div>
    

    <div class="search-results">
        
            <h2 class="mb-0">Search results for "{{ $searchQuery }}" ({{ count($articles) }})</h2>
           
        
        <div class="article-list">
            @foreach ($articles as $article)
                <div class="article">
                    <span class="article-icon">📄</span>
                    <a href="#" class="article-link">{{ $article['title'] }}</a>
                    <span class="badge badge-secondary">{{ $article['category'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

