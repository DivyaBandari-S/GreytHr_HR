<?php

namespace App\Livewire;

use App\Models\ClientProjects;
use Livewire\Component;
use App\Helpers\FlashMessageHelper;

class AddProjects extends Component
{
    public $client_id, $project_name, $project_description, $project_start_date, $project_end_date, $selectedProjectId;
    public $showViewModal = false;
    public $search = '';
    protected $messages = [
        'project_name.unique' => 'This project name has already been taken. Please choose another name.',
    ];
    protected $rules = [
        'project_name' => 'required|string|max:100|unique:client_projects,project_name',
        'project_description' => 'nullable|string',
        'project_start_date' => 'nullable|date',
        'project_end_date' => 'nullable|date',
    ];

    // Validation rules
   
    public function viewProject($projectId)
    {
        $project = ClientProjects::find($projectId);
        $this->project_name = $project->project_name;
        $this->project_description = $project->project_description;
        $this->project_start_date = $project->project_start_date;
        $this->project_end_date = $project->project_end_date;

        $this->showViewModal = true;
    }
    public function editProject($projectId)
    {
        $project = ClientProjects::find($projectId);
        $this->selectedProjectId = $projectId;
        $this->project_name = $project->project_name;
        $this->project_description = $project->project_description;
        $this->project_start_date = $project->project_start_date;
        $this->project_end_date = $project->project_end_date;
    }
    public function deleteProject($projectId)
    {
        $project = ClientProjects::find($projectId);
        $project->delete();
        FlashMessageHelper::flashSuccess('Project deleted successfully.');
        $this->render(); // Refresh the project list
    }

    public function mount($client_id)
    {
        $this->client_id = $client_id;
    }
    public function updateProject()
    {
        $rules = [
            'project_name' => 'required|string|max:100|unique:client_projects,project_name,' . $this->selectedProjectId,
            'project_description' => 'nullable|string',
            'project_start_date' => 'nullable|date',
            'project_end_date' => 'nullable|date',
        ];
        $this->validate($rules);

        $project = ClientProjects::find($this->selectedProjectId);
        $project->update([
            'project_name' => $this->project_name,
            'project_description' => $this->project_description,
            'project_start_date' => $this->project_start_date,
            'project_end_date' => $this->project_end_date,
        ]);

      
        FlashMessageHelper::flashSuccess('Project updated successfully.');
        $this->resetForm();
    }
    public function resetForm()
    {
        $this->selectedProjectId = null;
        $this->project_name = '';
        $this->project_description = '';
        $this->project_start_date = '';
        $this->project_end_date = '';
    }

    public function submitForm()
    {
        $rules = [
            'project_name' => 'required|string|max:100|unique:client_projects,project_name',
            'project_description' => 'nullable|string',
            'project_start_date' => 'nullable|date',
            'project_end_date' => 'nullable|date',
        ];
        $this->validate($rules); // Validate the input data

        // Create the project record
        ClientProjects::create([
            'client_id' => $this->client_id,
            'project_name' => $this->project_name,
            'project_description' => $this->project_description,
            'project_start_date' => $this->project_start_date,
            'project_end_date' => $this->project_end_date,
        ]);
        FlashMessageHelper::flashSuccess('Project added successfully.');
        $this->resetForm();

        // return redirect()->route('project.list');  // Redirect to the project listing page
    }
    public function validateInputChange($field)
    {
        
        $this->validateOnly($field);
    }
    public function searchProjects()
    {
        // Filter the projects based on the search query
        $this->projects = ClientProjects::where('client_id', $this->client_id)
                                        ->where('project_name', 'like', '%' . $this->search . '%')
                                        ->get();
    }
    public $projects;
    public function render()
    {
        if (empty($this->search)) {
            $this->projects = ClientProjects::where('client_id', $this->client_id)->get();
        }
        return view('livewire.add-projects');
    }
}
