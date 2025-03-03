<?php
// File Name                       : Feeds.php
// Description                     : This file contains the information about Activities and Posts in this implemented functionality for adding comments and emojis.
// Creator                         : Ashannagari Archana
// Email                           : archanaashannagari@gmail.com
// Organization                    : PayG.
// Date                            : 2023-09-07
// Framework                       : Laravel (10.10 Version)
// Programming Language            : PHP (8.1 Version)
// Database                        : MySQL
// Models                          : Comment,AddComment,Emoji,EmojiReaction,EmployeeDetails
namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\Comment;
use App\Models\EmployeeDetails;
use App\Models\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Mail\PostCreatedNotification;

use Illuminate\Http\Request;
use App\Models\EmojiReaction;
use Illuminate\Support\Facades\Mail;
use App\Models\Employee;
use App\Models\Emoji;
use Livewire\WithFileUploads;
use App\Services\GoogleDriveService;
use App\Models\Addcomment;
use App\Models\Company;
use App\Models\Hr;
use App\Models\Kudos;
use App\Models\KudosReactions;
use Illuminate\Support\Facades\Session;
use App\Models\Post;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Feeds extends Component
{

    use WithFileUploads;
   public $image;
   public $posts;
   public $currentCardEmojis;
   public $card_id;
   public $file_path;
   public $description = '';
  

    public $category;

    public $search;


    public $showEmojiPicker = false;
    public $employeeId;
    public $showAlert = false;
    public $open = false;

    public $emojis;

    public $employees;
    public $hr;

    public $dropdownVisible = false;
    public $monthAndDay;
    public $currentCardEmpId;

    public $comments = [];

    public $sortType = 'newest';
    public $newComment = '';
    public $employeeDetails;
    public $status;
    public $postStatus;
    public $isSubmitting = false;
    public $emp_id;
    public $addcomments;
    public $allEmojis;
    public $data;

    public $selectedEmoji = null;

    public $selectedEmojiReaction;
    public $message = '';
    public $isManager;
    public $flashMessage = '';
    public $storedemojis;
    public $showDialog = false;
    public $showDialogEmoji = false;

    public $showFeedsDialog = false;
    public $showKudosDialog = false;
    public $showMessage = true;
    public $empCompanyLogoUrl;

    public $combinedData = [];

    // In your Livewire Component
    public $recognizeType = [];  // Array to store selected values
    public $searchTerm = '';  // Store search term
    public $dropdownOpen = false;  // Boolean flag to manage dropdown visibility
    public $kudosId; // Store the kudos ID
    public $kudoMessage; // Store the message content
    public $reactions = []; // Store reactions for this kudos post
    public $showKudoEmojiPicker = false; // Track if the emoji picker is visible
    public $postType = 'appreciations';
    public function updatePostType($value)
    {
        $this->postType = $value;  // Update postType when the select changes
    }


    // Array of options with the label and description
    public $options = [
        'Approachable' => 'You work well with others',
        'Articulate' => 'You can express yourself well in front of groups.',
        'Autonomous' => 'You are a self-starter with lots of initiative and agency.',
        'Collaborator' => 'You are a teamwork champion and culture builder.',
        'Competitive' => 'You thrive under pressure.',
        'Creative' => 'You are the endless source of original ideas.',
        'Devoted' => 'You are committed to the company\'s success.',
        'Efficient' => 'You have a very quick turnaround time.',
        'Enthusiastic' => 'You put all in every project.',
        'Independent' => 'You need a little direction.',
        'Innovator' => 'You are the visionary boundary-pusher.',
        'Leader' => 'You set an example for an exemplary role model and empowerer.',
        'Learner' => 'You can learn new things and put that learning to good use.',
        'Motivator' => 'You are the true inspiration and change driver.',
        'Open-minded' => 'You take constructive criticism well.',
        'Opinionated' => 'You are comfortable voicing opinions.',
        'Planning' => 'You can come up with a good plan for a project or initiative.',
        'Problem Solver' => 'You can solve problems in the most elegant and effective manner.',
        'Resourceful' => 'You use every tool at hand.',
        'Strategist' => 'You have the planning mastery with clear vision.',
        'Team Player' => 'You foster unity and team binding.',
    ];
    private $reactionEmojis = [
        'thumbs_up' => '👍',
        'heart' => '❤️',
        'clap' => '👏',
        'laugh' => '😂',
        'surprised' => '😲',
        'sad' => '😢',
        'fire' => '🔥',
        'star' => '⭐',
        'party' => '🎉',
        'thinking' => '🤔',
        'love' => '😍',
        'happy' => '😀',
        'grin' => '😁',
        'joy' => '😂',
        'smile' => '😃',
        'big_smile' => '😄',
        'sweat_smile' => '😅',
        'laughing' => '😆',
        'angel' => '😇',
        'devil' => '😈',
        'wink' => '😉',
        'blush' => '😊',
        'tongue_out' => '😋',
        'in_love' => '😍',
        'relieved' => '😌',
        'cool' => '😎',
        'smirk' => '😏',
        'neutral' => '😐',
        'expressionless' => '😑',
        'unamused' => '😒',
        'pensive' => '😓',
        'disappointed' => '😔',
        'confused' => '😕',
        'confounded' => '😖',
        'kissing' => '😗',
        'blowing_kiss' => '😘',
        'kissing_heart' => '😙',
        'kissing_smiling_eyes' => '😚',
        'stuck_out_tongue' => '😛',
        'stuck_out_tongue_winking_eye' => '😜',
        'stuck_out_tongue_closed_eyes' => '😝',
        'disappointed_relieved' => '😞',
        'worried' => '😟',
        'angry' => '😠',
        'rage' => '😡',
        'cry' => '😢',
        'persevere' => '😣',
        'angry_face' => '😤',
        'disappointed_face' => '😥',
        'frowning' => '😦',
        'anguished' => '😧',
        'fearful' => '😨',
        'weary' => '😩',
        'sleepy' => '😪',
        'tired_face' => '😫',
        'grimacing' => '😬',
        'sob' => '😭',
        'astonished' => '😮',
        'hushed' => '😯',
        'open_mouth' => '😲',
        'flushed' => '😳',
        'sleeping' => '😴',
        'dizzy_face' => '😵',
        'face_without_mouth' => '😶',
        'mask' => '😷',
        'raised_hand' => '👋',
        'raised_back_of_hand' => '✋',
        'hand' => '🖐',
        'vulcan_salute' => '🖖',
        'raised_hand_with_fingers_splayed' => '🤚',
        'point_up' => '☝',
        'point_up_2' => '👆',
        'point_down' => '👇',
        'point_left' => '👈',
        'point_right' => '👉',
        'middle_finger' => '🖕',
        'fist_raised' => '✊',
        'fist' => '👊',
        'thumbs_up_reversed' => '👍',
        'victory_hand' => '✌',
        'ok_hand' => '👌',
        'pinching_hand' => '🤏',
    ];

    public $recognizeOptions = [];

    public function searchRecognizeValues()
    {
        if ($this->searchTerm) {
            $filteredOptions = collect($this->options)
                ->filter(function ($value, $key) {
                    return strpos(strtolower($key), strtolower($this->searchTerm)) !== false;
                })
                ->toArray();
            $this->recognizeOptions =   $filteredOptions;
        } else {
            $this->recognizeOptions = $this->options;
        }
    }

    public function recognizeToggleDropdown()
    {
        $this->dropdownOpen = !$this->dropdownOpen;  // Toggle dropdown visibility
    }

    public function updatedSearchTerm()
    {
        // This method is triggered whenever the searchTerm changes, and Livewire will automatically refresh the view
    }

    public function closeMessage()
    {
        $this->showMessage = false;
    }
    public function openPost($postId)
    {
        $post = Post::find($postId);

        if ($post) {
            $post->update(['status' => 'Open']);
        }

        return redirect()->to('/feeds'); // Redirect to the appropriate route
    }

    public function addFeeds()
    {
        $this->showFeedsDialog = true;
    }
    public function addKudos()
    {
        $this->showKudosDialog = true;
    }
    public function close()
    {
        $this->showKudosDialog = false;
    }
    public $search1 = ''; // Property for the search field
    public $employees1 = []; // Property to hold employee data
    public $selectedEmployee = null;
    public function selectEmployee($employeeId)
    {

        $this->selectedEmployee = EmployeeDetails::find($employeeId); // Find and store selected employee
        $this->validateOnly('selectedEmployee');
        $this->search1 = '';
    }

    public function removeSelectedEmployee()
    {
        $this->selectedEmployee = null; // Reset the selected employee
        $this->search1 = '';  // Optionally clear the search input field
    }


    public function searchEmployees()
    {

        $loggedInId = auth()->guard('emp')->user()->emp_id;

        // Fetch all employees excluding the logged-in one
        $employees = EmployeeDetails::query()
            ->where('emp_id', '!=', $loggedInId) // Exclude the logged-in employee's record
            ->get(['first_name', 'last_name', 'emp_id']); // Fetch only the required fields

        // Filter based on search input
        if ($this->search1) {
            $filteredEmployees = $employees->filter(function ($employee) {
                return str_contains(strtolower($employee->first_name), strtolower($this->search1)) ||
                    str_contains(strtolower($employee->last_name), strtolower($this->search1)) ||
                    str_contains(strtolower($employee->emp_id), strtolower($this->search1));
            });

            // If search term is entered, update the employees list
            $this->employees1 = collect($filteredEmployees);
        } else {
            // If no search term, set employees1 to an empty collection
            $this->employees1 = collect();
        }
    }

    public function removeItem($type)
    {
        $this->recognizeType = array_filter($this->recognizeType, function ($item) use ($type) {
            return $item !== $type;
        });
        $this->recognizeType = array_values($this->recognizeType); // Reindex array
    }

    public function closeFeeds()
    {

        $this->message = '';
        $this->showFeedsDialog = false;
    }


    protected $rules = [

        'newComment' => 'required|string',
    ];
    protected $newCommentRules = [
        'category' => 'required',
        'description' => 'required',
        'file_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:40960',

    ];
    protected $messages = [
        'category.required' => 'Category is required.',

        'description.required' => 'Description is required.',

    ];

    public function validateField($field)
    {

        $this->validateOnly($field, $this->rules);
    }
    public function validateKudos(){
        $this->validate([
           'message' => 'required|string|min:5',
        'selectedEmployee' => 'required',
         
        
        ]);
    } 

    public function toggleKudosEmojiPicker()
    {
        $this->showKudoEmojiPicker = !$this->showKudoEmojiPicker;
    }


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetFields()
    {
        $this->message = '';
        $this->selectedEmployee = null;
        $this->postType = '';
        $this->recognizeType = [];
        $this->reactions = [];
    }

    public function submitKudos()
    {


        // Debug logs
        $validatedData = $this->validateKudos();
       
        Log::debug('Recognize Type:', $this->recognizeType);
        Log::debug('Reactions:', $this->reactions);
        $emojiReactions = [];
        foreach ($this->reactions as $reactionKey) {
            if (isset($this->reactionEmojis[$reactionKey])) {
                $emojiReactions[] =  [
                    'emoji' => $this->reactionEmojis[$reactionKey],  // The emoji character
                    'employee_id' => Auth::user()->emp_id,  // The employee who reacted
                    'created_at' => now(),  // Timestamp when the reaction was created
                ]; // Get the emoji character
            }
        }

        // Ensure recognizeType and reactions are properly encoded as JSON
        $recognizeTypeJson = !empty($this->recognizeType) ? json_encode($this->recognizeType) : null;
        $reactionsJson = !empty($emojiReactions) ? json_encode($emojiReactions) : null;



        // Save Kudos entry
        Kudos::create([
            'employee_id' => Auth::user()->emp_id,  // Assuming the logged-in employee
            'recipient_id' => $this->selectedEmployee->emp_id,
            'message' => $this->message,
            'recognize_type' => $recognizeTypeJson,  // Save the encoded JSON
            'reactions' => $reactionsJson,  // Save the encoded JSON
            'post_type' => $this->postType,  // Save the postType
        ]);

        // Reset form fields after submission
        $this->resetFields();
         FlashMessageHelper::flashSuccess('Kudos given successfully!');
            $this->showKudosDialog = false;
    }


    public function toggleEmojiPicker()
    {
        $this->showEmojiPicker = !$this->showEmojiPicker;
    }

    public function addReaction($reaction)
    {
        if (!in_array($reaction, $this->reactions)) {
            $this->reactions[] = $reaction;

            // Save to kudos_reactions table
            KudosReactions::create([
                'employee_id' => Auth::user()->emp_id,
                'reaction' => $reaction,
            ]);
        }

        // Close the emoji picker after selection
        $this->showKudoEmojiPicker = false;

        // Update the kudos reactions in the kudos table
        $this->updateKudosReactions();
    }

    private function updateKudosReactions()
    {
        // Encode reactions array as JSON
        $encodedReactions = json_encode($this->reactions);

        // Update the kudos table with the new reactions
        Kudos::where('id', $this->kudosId)->update(['reactions' => $encodedReactions]);
    }
    public function getReactionEmojis()
    {
        return $this->reactionEmojis;
    }

    // Get emoji for a given reaction
    public function getEmoji($reaction)
    {
        return $this->reactionEmojis[$reaction] ?? ''; // Return empty if not found
    }
    public function removeKudosReaction($reaction)
    {
        $this->reactions = array_filter($this->reactions, fn($item) => $item !== $reaction);
        $this->reactions = array_values($this->reactions); // Reindex the array

        // Remove from the kudos_reactions table
        KudosReactions::where('employee_id', Auth::user()->emp_id)
            ->where('reaction', $reaction)
            ->delete();

        // Update the kudos reactions in the kudos table
        $this->updateKudosReactions();
    }

    public function mount()
    {
        
     
    // Fetch the logged-in employee's ID
$employeeId = Auth::guard('hr')->user()->emp_id;

// Fetch emp_id and company_id for the logged-in employee
$employeeDetails = DB::table('employee_details')
    ->where('emp_id', $employeeId)
    ->select('emp_id', 'company_id') // Select specific columns
    ->first(); // Fetch the result as an object



// Fetch the company_id for the logged-in employee's parent company
$company = DB::table('employee_details')

->select('company_id') // Select specific columns
->first(); // Fetch the result as an object     

if ($company) {
    // Debugging: Print the raw company_id from the database (assumed to be a JSON string)
    $companyId = $company->company_id;

    // Ensure the company ID is not null or empty
    if (!empty($companyId)) {
        // Debugging: Check the value of companyId

        // Query to fetch all employees where company_id matches the parent company_id in employee_details
         // Query to fetch all employees where company_id (in JSON format) contains the specific company_id
      // If company_id is just a string field:
$this->employees = EmployeeDetails::where('company_id', $companyId)->get();


                $this->comments = Comment::with('employee')->whereIn('emp_id', $this->employees->pluck('emp_id'))->get();
                $this->addcomments = Addcomment::with('employee')->whereIn('emp_id', $this->employees->pluck('emp_id'))->get();
                $this->storedemojis = Emoji::whereIn('emp_id', $this->employees->pluck('emp_id'))->get();
                $this->emojis = EmojiReaction::whereIn('emp_id', $this->employees->pluck('emp_id'))->get();
                $this->allEmojis = Emoji::whereIn('emp_id', $this->employees->pluck('emp_id'))->get();
                $this->combinedData = $this->combineAndSortData($this->employees);
                $this->empCompanyLogoUrl = $this->getEmpCompanyLogoUrl();

                $this->loadComments();
                $employeeId = Auth::guard('hr')->user()->emp_id;
                $this->isManager = DB::table('employee_details')
                    ->where('manager_id', $employeeId)
                    ->exists();
            }
        } else {
            // If no parent company is found

        }
        $this->showFeedsDropdown = [];
        $this->posts  = Post::where('status', 'Closed')
        ->orderBy('updated_at', 'desc')
        ->get();

   $this->empCompanyLogoUrl = $this->getEmpCompanyLogoUrl();

   $user = Auth::user();

   if ($user) {
       $this->employeeDetails = $user->employeeDetails;
   }
   $employeeId = Auth::guard()->user()->emp_id;
   $this->isManager = DB::table('employee_details')
       ->where('manager_id', $employeeId)
       ->exists();
  
           // Fetch posts for the manager and their employees
     
               if ($this->isManager) {
               // For managers: get their posts and their team's posts
               $this->posts = Post::where('status', 'Closed')
                   ->where(function ($query) use ($employeeId) {
                       $query->where('manager_id', $employeeId) // Manager's own posts
                             ->orWhereIn('emp_id', function ($subQuery) use ($employeeId) {
                                 $subQuery->select('emp_id')
                                           ->from('employee_details')
                                           ->where('manager_id', $employeeId); // Team members' posts
                             });
                   })
                   ->orderBy('updated_at', 'desc')
                   ->get();
           } else {
               // For employees: get their posts and their manager's posts
               $this->posts = Post::where('status', 'Closed')
               ->where(function ($query) use ($employeeId) {
                   $query->where('emp_id', $employeeId) // Employee's own posts
                         ->orWhere('manager_id', function($subQuery) use ($employeeId) {
                             $subQuery->select('manager_id')
                                       ->from('employee_details')
                                       ->where('emp_id', $employeeId); // Get the employee's manager's ID
                         })
                         ->orWhereIn('emp_id', function($subQuery) use ($employeeId) {
                             // Get all employees under the same manager
                             $subQuery->select('emp_id')
                                       ->from('employee_details')
                                       ->where('manager_id', function($innerQuery) use ($employeeId) {
                                           $innerQuery->select('manager_id')
                                                       ->from('employee_details')
                                                       ->where('emp_id', $employeeId);
                                       });
                         });
               })
               ->orderBy('updated_at', 'desc')
               ->get();
           
           
       
        $today=now();
        $currentDate = $today->toDateString();
        $birthdayRecord = Notification::where('body', $currentDate)
            ->where('assignee', $authCompanyId[0])
            ->where('notification_type', 'birthday')
            ->first();
        if ($birthdayRecord) {
            // Decode the JSON field into a PHP array
            $isBirthdayRead = json_decode($birthdayRecord->is_birthday_read, true);

            // Check if the employee ID exists in the array and update it
            if (isset($isBirthdayRead[$employeeId])) {
                $isBirthdayRead[$employeeId] = 1;  // Mark as read (1)
            }

            // Encode the updated array back into JSON
            $birthdayRecord->is_birthday_read = json_encode($isBirthdayRead);

            // Save the updated record back to the database
            $birthdayRecord->save();
        }

    }

    }


    public $isEmojiListVisible = false;
    public function showEmojiList()
    {
        // Toggle the visibility of the emoji list
        $this->isEmojiListVisible = !$this->isEmojiListVisible;
    }

    public function toggleEmojiList()
    {
        // Toggle the visibility of the emoji list
        $this->isEmojiListVisible = !$this->isEmojiListVisible;
    }
    // Method to select an emoji
    public function selectEmoji($emoji, $emp_id)
    {
        // Check if an emoji is already selected
        if ($this->selectedEmoji !== $emoji) {
            // Update the selected emoji property
            $this->selectedEmoji = $emoji;
            // Call the add_emoji method with emp_id
            $this->add_emoji($emp_id);
        }
    }
    public function addEmoji($emoji_reaction, $emp_id)
    {
        // Check if an emoji is already selected
        if ($this->selectedEmojiReaction !== $emoji_reaction) {

            $this->selectedEmojiReaction = $emoji_reaction;

            // Call the add_emoji method with emp_id
            $this->createemoji($emp_id);
        }
    }

    public function openDialog($emp_id)
    {
        logger()->info('openDialog method called with emp_id: ' . $emp_id);  // Log the method call


        $this->emp_id = $emp_id;
        $this->currentCardEmojis = Emoji::where('emp_id', $emp_id)->get();
        $this->allEmojis = Emoji::where('emp_id', $emp_id)->get();


        $this->showDialog = true;
        // Fetch the latest emoji reactions for the specific employee

    }
    public function openEmojiDialog($emp_id)
    {
        logger()->info('openDialog method called with emp_id: ' . $emp_id);  // Log the method call

        $this->emp_id = $emp_id;
        $this->currentCardEmojis = Emoji::where('emp_id', $emp_id)->get();


        $this->allEmojis = Emoji::where('emp_id', $emp_id)->get();
        $this->showDialogEmoji = true;
    }
    public function handleRadioChange($value)
    {
        // Define the URLs based on the radio button value
        $urls = [
            'posts' => '/hr/everyone',
            'activities' => '/hr/hrFeeds',
            'kudos' => '/kudos',
            'post-requests' => '/emp-post-requests'
            // Add more mappings if necessary
        ];

        // Redirect to the correct URL
        if (array_key_exists($value, $urls)) {
            return redirect()->to($urls[$value]);
        }
    }

    public function closeEmojiDialog()
    {
        $this->showDialogEmoji = false;
    }
    public function closeDialog()
    {
        $this->showDialog = false;
    }


 
    public function removeEmojiReaction($emojiId)
    {
        try {
            // Locate the emoji based on ID
            $emoji_reaction = EmojiReaction::find($emojiId);

            if ($emoji_reaction && $emoji_reaction->emp_id === auth()->user()->emp_id) { // Check if the emoji belongs to the logged-in user
                $emoji_reaction->delete();

                // Dispatch a success message
                FlashMessageHelper::flashSuccess(' Reaction removed Successfully .');

                // Remove the deleted emoji from $allEmojis
                $this->allEmojis = collect($this->allEmojis)->reject(fn($item) => $item->id === $emojiId);
                $this->dispatch('emojiRemoved', ['emojiId' => $emojiId]);
            } else {
                throw new \Exception('You can only remove your own reactions.');
            }
        } catch (\Exception $e) {
            FlashMessageHelper::flashError($e->getMessage());
        }
    }

    public function removeReaction($emojiId)
    {
        try {
            // Locate the emoji based on ID
            $emoji = Emoji::find($emojiId);

            if ($emoji && $emoji->emp_id === auth()->user()->emp_id) { // Check if the emoji belongs to the logged-in user
                $emoji->delete();

                // Dispatch a success message
                FlashMessageHelper::flashSuccess('Reaction removed Successfully');

                // Remove the deleted emoji from $allEmojis
                $this->allEmojis = collect($this->allEmojis)->reject(fn($item) => $item->id === $emojiId);
                $this->dispatch('emojiRemoved', ['emojiId' => $emojiId]);
            } else {
                throw new \Exception('You can only remove your own reactions.');
            }
        } catch (\Exception $e) {
            FlashMessageHelper::flashError($e->getMessage());
        }
    }

    public function handleEmojiRemoval($emojiId)
    {
        // Handle the emoji removal logic (e.g., remove it from the list)
        $this->allEmojis = $this->allEmojis->reject(fn($item) => $item->id === $emojiId);
    }

    // Method to add emoji
    public function add_emoji($emp_id)
    {
        // Get the current user
        $user = Auth::user();
    
        // Initialize employeeId and hrId as null
        $employeeId = null;
        $hrId = null;
    
        // Check if the user is an employee
        if (auth()->check()) {
            // Get the current authenticated employee's emp_id
            $employeeId = auth()->user()->emp_id;
        } elseif (auth()->guard('hr')->check()) {
            // Get the current authenticated HR's emp_id
            $hrEmployee = auth()->guard('hr')->user();
            $hrId = $hrEmployee->emp_id;
        }
    
        // Ensure that either $employeeId or $hrId is set
        if (is_null($employeeId) && is_null($hrId)) {
            FlashMessageHelper::flashError('Employee ID cannot be null.');
            return;
        }
    
        // Retrieve first_name and last_name based on emp_id or hrId
        $employeeDetails = null;
        if ($employeeId) {
            // Fetch employee details from EmployeeDetails table
            $employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
        } elseif ($hrId) {
            // Fetch HR details from EmployeeDetails table
            $employeeDetails = EmployeeDetails::where('emp_id', $hrId)->first();
        }
    
        // Ensure that employee or HR details were found
        if ($employeeDetails) {
            $firstName = $employeeDetails->first_name;
            $lastName = $employeeDetails->last_name;
    
            // Set default values if first_name or last_name is empty
            if (empty($firstName) || empty($lastName)) {
                $firstName = 'Unknown';
                $lastName = 'Unknown';
            }
        } else {
            FlashMessageHelper::flashError('Employee details not found.');
            return;
        }
    
        // Create the emoji record for employee
        if ($employeeId) {
            Emoji::create([
                'card_id' => $emp_id,
                'emp_id' => $employeeId,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'emoji' => $this->selectedEmoji ?? '',
            ]);
        }
    
        // Create the emoji record for HR
        if ($hrId) {
            Emoji::create([
                'card_id' => $emp_id,
                'emp_id' => null, // HR has no employee ID, so set to null
                'hr_emp_id' => $hrId, // Set HR's emp_id
                'first_name' => $firstName,
                'last_name' => $lastName,
                'emoji' => $this->selectedEmoji ?? '',
            ]);
        }
    
        // Optionally, toggle emoji list visibility off
        $this->isEmojiListVisible = false;
        $this->storedemojis = Emoji::whereIn('emp_id', $this->employees->pluck('emp_id'))->get();
    }
    
    
    
    public function createemoji($emp_id)
    {
        // Get the current user
        $user = Auth::user();
    
        // Initialize employeeId and hrId as null
        $employeeId = null;
        $hrId = null;
    
        // Check if the user is an employee
        if (auth()->check()) {
            // Get the current authenticated employee's emp_id
            $employeeId = auth()->user()->emp_id;
        } elseif (auth()->guard('hr')->check()) {
            // Get the current authenticated HR's emp_id
            $hrEmployee = auth()->guard('hr')->user();
            $hrId = $hrEmployee->emp_id;
        }
    
        // Ensure that either $employeeId or $hrId is set
        if (is_null($employeeId) && is_null($hrId)) {
            FlashMessageHelper::flashError('Employee ID cannot be null.');
            return;
        }
    
        // Retrieve first_name and last_name based on emp_id or hrId
        $employeeDetails = null;
        if ($employeeId) {
            // Fetch employee details from EmployeeDetails table
            $employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
        } elseif ($hrId) {
            // Fetch HR details from EmployeeDetails table
            $employeeDetails = EmployeeDetails::where('emp_id', $hrId)->first();
        }
    
        // Ensure that employee or HR details were found
        if ($employeeDetails) {
            $firstName = $employeeDetails->first_name;
            $lastName = $employeeDetails->last_name;
    
            // Set default values if first_name or last_name is empty
            if (empty($firstName) || empty($lastName)) {
                $firstName = 'Unknown';
                $lastName = 'Unknown';
            }
        } else {
            FlashMessageHelper::flashError('Employee details not found.');
            return;
        }
       
        if ($employeeId) {
            EmojiReaction::create([
                'card_id' => $emp_id,
                'emp_id' => $employeeId,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'emoji_reaction' => $this->selectedEmojiReaction ?? '',
            ]);
        }
    
        // Create the emoji record for HR
        if ($hrId) {
            EmojiReaction::create([
                'card_id' => $emp_id,
                'emp_id' => null, // HR has no employee ID, so set to null
                'hr_emp_id' => $hrId, // Set HR's emp_id
                'first_name' => $firstName,
                'last_name' => $lastName,
                'emoji_reaction' => $this->selectedEmojiReaction ?? '',
            ]);
        }

        // Create the comment based on the authenticated role
  
        // Optionally, toggle emoji list visibility off
        $this->isEmojiListVisible = false;
        $this->emojis = EmojiReaction::whereIn('emp_id', $this->employees->pluck('emp_id'))->get();
    }

    public function getComments($sortType)
    {
        $query = Comment::query();

        if ($sortType === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sortType === 'interacted') {
            $query->orderBy('updated_at', 'desc');
        }

        $currentCardComments = $query->get();

        return view('feeds', compact('currentCardComments', 'sortType'));
    }

    public function add_comment($emp_id)
    {
        $this->validate();

        $employeeId = null;
        $hrId = null;

        if (auth()->check()) {
            // Get the current authenticated employee's emp_id
            $employeeId = auth()->user()->emp_id;
        } elseif (auth()->guard('hr')->check()) {
            // Get the current authenticated HR's emp_id
            $hrEmployee = auth()->guard('hr')->user();
            $hrId = $hrEmployee->emp_id;
        }

        // Ensure that either $employeeId or $hrId is set
        if (is_null($employeeId) && is_null($hrId)) {
            FlashMessageHelper::flashError('Employee ID cannot be null.');
            return;
        }

        // Create the comment based on the authenticated role
        if ($employeeId) {
            Comment::create([
                'card_id' => $emp_id,
                'emp_id' => $employeeId,
                'hr_emp_id' => null,
                'comment' => $this->newComment ?? '',
            ]);
        } elseif ($hrId) {
            Comment::create([
                'card_id' => $emp_id,
                'emp_id' => $employeeId,
                'hr_emp_id' => $hrId,
                'comment' => $this->newComment ?? '',
            ]);
        }


        // Clear the input field after adding the comment
        $this->reset(['newComment']);
        $this->isSubmitting = false;

        $this->comments = Comment::with('employee', 'hr')
            ->whereIn('emp_id', $this->employees->pluck('emp_id'))
            ->orWhereIn('hr_emp_id', $this->employees->pluck('emp_id'))
            ->orderByDesc('created_at')
            ->get();
    }
    public function createcomment($emp_id)
    {
        $this->validate();

        $employeeId = null;
        $hrId = null;

        if (auth()->check()) {
            // Get the current authenticated employee's emp_id
            $employeeId = auth()->user()->emp_id;
        } elseif (auth()->guard('hr')->check()) {
            // Get the current authenticated HR's emp_id
            $hrEmployee = auth()->guard('hr')->user();
            $hrId = $hrEmployee->emp_id;
        }

        // Ensure that either $employeeId or $hrId is set
        if (is_null($employeeId) && is_null($hrId)) {
            FlashMessageHelper::flashError('Employee ID cannot be null.');
            return;
        }

        // Create the comment based on the authenticated role
        if ($employeeId) {
            AddComment::create([
                'card_id' => $emp_id,
                'emp_id' => $employeeId,

                'addcomment' => $this->newComment ?? '',
            ]);
        } elseif ($hrId) {
            AddComment::create([
                'card_id' => $emp_id,
                'emp_id' => $employeeId,

                'addcomment' => $this->newComment ?? '',
            ]);
        }


        // Clear the input field after adding the comment
        $this->reset(['newComment']);
        $this->isSubmitting = false;


        $this->addcomments = Addcomment::with('employee')
            ->whereIn('emp_id', $this->employees->pluck('emp_id'))
            ->orderByDesc('created_at')
            ->get();
    }
    protected $listeners = ['updateSortType', 'emojiRemoved' => 'handleEmojiRemoval'];
    // Toggle dropdown visibility


    public function updateSortType($sortType)
    {
        $this->sortType = $sortType;

        $this->loadComments();
    }

    public function loadComments()
    {
        // Fetch all comments initially
        $commentsQuery = Comment::with('employee', 'hr')
            ->whereIn('emp_id', $this->employees->pluck('emp_id'))
            ->orWhereIn('hr_emp_id', $this->employees->pluck('emp_id'));

        // Fetch all comments
        $allComments = $commentsQuery->get();

        // Group comments by card_id and filter card_ids with more than 2 comments
        $cardIdsWithMoreThanTwoComments = $allComments->groupBy('card_id')
            ->filter(function ($comments) {
                return $comments->count() > 2;
            })
            ->keys();

        // Fetch comments only for those card IDs
        $filteredCommentsQuery = $commentsQuery->whereIn('card_id', $cardIdsWithMoreThanTwoComments);

        // Sort the filtered comments based on the sortType
        if ($this->sortType === 'interacted') {
            $filteredCommentsQuery = $filteredCommentsQuery->orderByDesc('updated_at');
        } else {
            $filteredCommentsQuery = $filteredCommentsQuery->orderByDesc('created_at');
        }

        $this->comments = $filteredCommentsQuery->get();
    }
    public function loadaddComments()
    {
        // Fetch all comments initially
        $commentsQuery = Comment::with('employee', 'hr')
            ->whereIn('emp_id', $this->employees->pluck('emp_id'))
            ->orWhereIn('hr_emp_id', $this->employees->pluck('emp_id'));

        // Fetch all comments
        $allComments = $commentsQuery->get();

        // Group comments by card_id and filter card_ids with more than 2 comments
        $cardIdsWithMoreThanTwoComments = $allComments->groupBy('card_id')
            ->filter(function ($comments) {
                return $comments->count() > 2;
            })
            ->keys();

        // Fetch comments only for those card IDs
        $filteredCommentsQuery = $commentsQuery->whereIn('card_id', $cardIdsWithMoreThanTwoComments);

        // Sort the filtered comments based on the sortType
        if ($this->sortType === 'interacted') {
            $filteredCommentsQuery = $filteredCommentsQuery->orderByDesc('updated_at');
        } else {
            $filteredCommentsQuery = $filteredCommentsQuery->orderByDesc('created_at');
        }

        $this->addcomments  = $filteredCommentsQuery->get();
    }



    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id');
    }
    public function submit()
    {
        $validatedData = $this->validate([
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'file_path' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Only allow image files
        ]);

        try {
            $fileContent = null;
            $mimeType = null;
            $fileName = null;

            // Process the uploaded image file
            if ($this->file_path) {
                if (!in_array($this->file_path->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'])) {
                    session()->flash('error', 'Only image files (jpeg, png, gif, svg) are allowed.');
                    return;
                }

                $fileContent = file_get_contents($this->file_path->getRealPath());
                $mimeType = $this->file_path->getMimeType();
                $fileName = $this->file_path->getClientOriginalName();
    
                if ($fileContent === false || strlen($fileContent) > 16777215) {
                    FlashMessageHelper::flashWarning('File size exceeds the allowed limit or could not be read.');
                    return;
                }
            }
    
            $user = Auth::user();
            $employeeId = auth()->user()->emp_id;
            $employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
             // Fetch the manager_id of the current employee
             $managerId = $employeeDetails->manager_id;
    
             if (!$managerId) {
                 FlashMessageHelper::flashError('Manager information not found for the current employee.');
                 return;
             }
     
             // Check if the authenticated employee is a manager
             $isManager = DB::table('employee_details')
                 ->where('manager_id', $employeeId)
                 ->exists();
     
             $postStatus = $isManager ? 'Closed' : 'Pending';
             $managerId = $isManager ? $employeeId : null;
             $empId = $isManager ? null : $employeeId;
    
           
    
            $hrDetails = Hr::where('hr_emp_id', $user->hr_emp_id)->first();
    
            $post = Post::create([
                'hr_emp_id' => $hrDetails->hr_emp_id ?? '-',
                'manager_id' => $managerId,
                'emp_id' => $empId,
                'category' => $this->category,
                'description' => $this->description,
                'file_path' => $fileContent,
                'mime_type' => $mimeType,
                'file_name' => $fileName,
                'status' => $postStatus,
            ]);
    
            // Send email notification to manager
            $managerDetails = EmployeeDetails::where('emp_id', $employeeDetails->manager_id)->first();
            if ($managerDetails && $managerDetails->email) {
                $managerName = $managerDetails->first_name . ' ' . $managerDetails->last_name;

                Mail::to($managerDetails->email)->send(new PostCreatedNotification($post, $employeeDetails, $managerName));
            }
            // Optionally, send email to HR
            if ($hrDetails && $hrDetails->email) {
                $managerName = $managerDetails->first_name . ' ' . $managerDetails->last_name;
                Mail::to($hrDetails->email)->send(new PostCreatedNotification($post, $employeeDetails, $managerName));
            }
    
    
            // Reset form fields and redirect to posts page
            $this->reset(['category', 'description', 'file_path']);
            FlashMessageHelper::flashSuccess('Post created successfully!');
            $this->showFeedsDialog=false;
             // Update 'manager.posts' to the actual route name for the posts page
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setErrorBag($e->validator->getMessageBag());
        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage(), [
                'employee_id' => $employeeId ?? 'N/A',
                'file_path_length' => isset($fileContent) ? strlen($fileContent) : null,
            ]);
            FlashMessageHelper::flashError('An error occurred while creating the post. Please try again.');
        }
    }
    
    
    




    public function setCurrentCardEmpId($empId)
    {
        // Set the current card's emp_id
        $this->currentCardEmpId = $empId;

        // Set the employeeDetails based on the current card's emp_id
        $this->employeeDetails = EmployeeDetails::where('emp_id', $empId)->first();

        // Fetch comments for the current card
        $this->comments = Comment::with('employee', 'hr')
            ->where('card_id', $empId)
            ->orderByDesc('created_at')
            ->get();

        $this->addcomments = Addcomment::where('card_id', $this->currentCardEmpId)->get();
        $this->storedemojis = Emoji::where('emp_id', $this->currentCardEmpId)->get();
        $this->emojis = EmojiReaction::where('emp_id', $this->currentCardEmpId)->get();
    }



    public function toggleOpen()
    {
        $this->open = !$this->open;
    }

    public $fileId;

    // Method to fetch the company logo URL for the current employee
    private function getEmpCompanyLogoUrl()
    {
        // Get the current authenticated employee's company ID
        if (auth()->check()) {
            // Get the current authenticated employee's company ID
            $empCompanyId = auth()->user()->company_id;
            $employeeId = auth()->user()->emp_id;
            $employeeDetails = DB::table('employee_details')
                ->where('emp_id', $employeeId)
                ->select('company_id') // Select only the company_id
                ->first();

            // Assuming you have a Company model with a 'company_logo' attribute
            $companyIds = json_decode($employeeDetails->company_id);
            $company = DB::table('companies')
                ->where('company_id', $companyIds)
                ->where('is_parent', 'yes')
                ->first();

            // Return the company logo URL, or a default if company not found
            return $company ? $company->company_logo : asset('user.jpg');
        } elseif (auth()->guard('hr')->check()) {
            $empCompanyId = auth()->guard('hr')->user()->company_id;

            // Assuming you have a Company model with a 'company_logo' attribute
            $company = Company::where('company_id', $empCompanyId)->first();
            return $company ? $company->company_logo : asset('user.jpg');
        }
    }

    public function render()
    {
        // Initialize variables
        $this->employeeDetails = collect();
        $this->hr = collect();
        $storedEmojis = collect();
        $emojis = collect();
        $employeeId = auth()->user()->emp_id;
        $employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();

        // $isManager = DB::table('employee_details')
        //     ->where('manager_id', $employeeId)
        //     ->exists();
        $isManager = DB::table('employee_details')
            ->where('manager_id', $employeeId)  // Assuming $employeeId is the manager's ID
            ->get();

        // Check if 'emp' guard is authenticated
        if (auth()->check()) {
            $this->employeeDetails = EmployeeDetails::with('personalInfo') // Eager load personal info
                ->where('emp_id', auth()->user()->emp_id)
                ->get();
            
            $storedEmojis = Emoji::where('emp_id', auth()->user()->emp_id)->get();
            $emojis = EmojiReaction::where('emp_id', auth()->user()->emp_id)->get();

            // Check if no employee details are found
            if ($this->employeeDetails->isEmpty()) {
                // Redirect or handle the case where no employee details are found
                return redirect()->route('Feeds'); // Redirect to a route for no employee details
            }
        }
        // Check if 'hr' guard is authenticated
        elseif (auth()->guard('hr')->check()) {
            $this->employeeDetails = Hr::where('hr_emp_id', auth()->guard('hr')->user()->hr_emp_id)->get();

            // Check if no employee details are found
            if ($this->employeeDetails->isEmpty()) {
                // Redirect or handle the case where no employee details are found
                return redirect()->route('no-employee-details'); // Redirect to a route for no employee details
            }
        }

        // Return the view with the necessary data
        return view('livewire.feeds', [
            'data' => $this->data,
            'comments' => $this->comments,
            'addcomments' => $this->addcomments,
            'empCompanyLogoUrl' => $this->empCompanyLogoUrl,
      'hr' => $this->employeeDetails,
            'employees' => $this->employeeDetails,
            'emojis' => $emojis,
            'isManager' => $this->isManager,
            'storedEmojis' => $storedEmojis
        ]);
    }
    // Method to toggle the dropdown visibility for a specific employee card



    public function showEmployee($id)
    {
        $employee = EmployeeDetails::find($id);
        $comments = Comment::with(['employee', 'hr'])
            ->where('card_id', $employee->emp_id)
            ->orWhere('hr_emp_id', $employee->emp_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('yourview', compact('employee', 'comments'));
    }

    public function saveEmoji()
    {
        // Save the selected emoji to the database
        if ($this->selectedEmoji) {
            Emoji::create(['code' => $this->selectedEmoji]);
            $this->selectedEmoji = null; // Clear selected emoji after saving
        }
    }
  

    public $showFeedsDropdown = []; // Visibility state for each employee card
    public function deleteCard($empId, $type)
    {
        // Store deleted employee empId in the session to persist across page refreshes
        $deletedEmployees = session()->get('deleted_employees', []);
    
        // Add the employee to the list of deleted employees
        $deletedEmployees[] = $empId;
        session()->put('deleted_employees', $deletedEmployees);
    
        // Filter out the employee's record from the combinedData array based on empId and type
        $this->combinedData = array_filter($this->combinedData, function ($data) use ($empId, $type) {
            // Remove the employee record if it matches both the empId and the type
            return !(isset($data['employee']->emp_id) && $data['employee']->emp_id === $empId && $data['type'] === $type);
        });
    
        // Re-index the array after filtering to ensure the keys are sequential
        $this->combinedData = array_values($this->combinedData);
    
        // Optionally, you can log the result to check if the deletion is working
        Log::info('Updated combinedData after delete:', $this->combinedData);
    
        // Flash success message for user feedback
        session()->flash('message', 'Employee card deleted successfully!');
    }
    
    
    public function toggleCardDropdown($empId)
    {
        // Toggle the dropdown visibility for a specific employee
        if (isset($this->showFeedsDropdown[$empId]) && $this->showFeedsDropdown[$empId] === true) {
            $this->showFeedsDropdown[$empId] = false;
        } else {
            $this->showFeedsDropdown[$empId] = true;
        }
    
        // Log the visibility state to debug
        Log::info('Dropdown visibility state: ', $this->showFeedsDropdown);
    }
    



    private function combineAndSortData($employees)
    {

    
        $combinedData = [];
        $currentDate = Carbon::now();
        $deletedEmployees = session()->get('deleted_employees', []);


        foreach ($employees as $employee) {
            if (in_array($employee->emp_id, $deletedEmployees)) {
                continue;  // Skip this employee
            }
            if (in_array($employee->employee_status, ['terminated', 'resigned'])) {
                continue;  // Skip this iteration and move to the next employee
            }
          
    
            if ($employee->personalInfo && !empty($employee->personalInfo->date_of_birth)) {
                $dateOfBirth = Carbon::parse($employee->personalInfo->date_of_birth);

                // Check if the date of birth is within the current month and up to the current date
                if (
                    $dateOfBirth->month < $currentDate->month ||
                    ($dateOfBirth->month === $currentDate->month && $dateOfBirth->day <= $currentDate->day)
                ) {
                    $combinedData[] = [
                        'date' => $dateOfBirth->format('m-d'), // Format date as needed
                        'type' => 'date_of_birth',
                        'employee' => $employee,
                    ];
                }
            }

            if ($employee->hire_date) {
                $hireDate = Carbon::parse($employee->hire_date);

                // Check if the hire date is within the current month and up to the current date
                if (
                    $hireDate->month < $currentDate->month ||
                    ($hireDate->month === $currentDate->month && $hireDate->day <= $currentDate->day)
                ) {
                    $combinedData[] = [
                        'date' => $hireDate->format('m-d'),
                        'type' => 'hire_date',
                        'employee' => $employee,
                    ];
                }
            }
        }
      
        // Sort the combined data by date in descending order
        usort($combinedData, function ($a, $b) {
            return $b['date'] <=> $a['date']; // Sort in descending order
        });

        return $combinedData;
       
    }
}
