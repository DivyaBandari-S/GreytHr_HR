<?php

namespace App\Livewire;

use Livewire\Component;

class KnowledgeBase extends Component
{
    
    public $searchQuery = '';

    public $articles = [];

    public function mount()
    {
        $this->articles = $this->getFilteredArticles();
    }

    public function updatedSearch()
    {
        $this->articles = $this->getFilteredArticles();
    }

    public function highlightKeywords($text, $keyword)
    {
        if (!$keyword) return e($text);

        $escapedKeyword = preg_quote($keyword, '/');
        return preg_replace_callback("/($escapedKeyword)/i", function ($match) {
            return '<span class="bg-yellow-200 px-1 rounded-sm">' . e($match[0]) . '</span>';
        }, e($text));
    }

    public function getFilteredArticles()
    {
        $allArticles = collect([
            ['title' => 'Can admin view the work hours summary of their employees?', 'category' => 'Attendance Management'],
            ['title' => 'How can admin view the data of different sign-in methods used by employees?', 'category' => 'Leave Management'],
            ['title' => 'What does attendance overview page explain?', 'category' => 'Attendance Management'],
            ['title' => 'Can admin view the work hours summary of their employees?', 'category' => 'Attendance Management'],
            ['title' => 'How can admin view the data of different sign-in methods used by employees?', 'category' => 'Leave Management'],
            ['title' => 'What does attendance overview page explain?', 'category' => 'Attendance Management'],
            ['title' => 'Can admin view the work hours summary of their employees?', 'category' => 'Attendance Management'],
            ['title' => 'How can admin view the data of different sign-in methods used by employees?', 'category' => 'Leave Management'],
            ['title' => 'What does attendance overview page explain?', 'category' => 'Attendance Management'],
            ['title' => 'Can admin view the work hours summary of their employees?', 'category' => 'Attendance Management'],
            ['title' => 'How can admin view the data of different sign-in methods used by employees?', 'category' => 'Leave Management'],
            ['title' => 'What does attendance overview page explain?', 'category' => 'Attendance Management'],
            ['title' => 'Can admin view the work hours summary of their employees?', 'category' => 'Attendance Management'],
            ['title' => 'How can admin view the data of different sign-in methods used by employees?', 'category' => 'Leave Management'],
            ['title' => 'What does attendance overview page explain?', 'category' => 'Attendance Management'],
            
        ]);

        return $allArticles->filter(function ($article) {
            return str_contains(strtolower($article['title']), strtolower($this->searchQuery));
        })->values();
    }

    public function render()
    {
        return view('livewire.knowledge-base');
    }
}
