<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class MultiStepRegistration extends Component
{
    use WithFileUploads;

    // Step tracker
    public $currentStep = 1;

    public $totalSteps = 4;

    // Step 1: Account Information
    public $name;

    public $email;

    public $password;

    public $password_confirmation;

    // Step 2: Education Information
    public $level;

    public $institution_id;

    public $major;

    public $phone;

    // Step 3: Department & Dates
    public $department_id;

    public $start_date;

    public $end_date;

    public $acceptance_proof;

    // Data untuk dropdown
    public $institutions;

    public $departments;

    // Listeners untuk upload
    // protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->institutions = Institution::all();
        $this->departments = Department::all();
    }

    // Validation rules per step
    protected function rules()
    {
        $rules = [
            1 => [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
            ],
            2 => [
                'level' => 'required|string',
                'institution_id' => 'required|exists:institutions,id',
                'major' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
            ],
            3 => [
                'department_id' => 'required|exists:departments,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'acceptance_proof' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
            ],
        ];

        return $rules[$this->currentStep] ?? [];
    }

    public function nextStep()
    {
        $this->validate();

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submit()
    {
        $this->validate();

        try {
            // Upload file
            $proofPath = $this->acceptance_proof->store('acceptance-proofs', 'public');

            // Create user
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => 'participant',
                'institution_id' => $this->institution_id,
                'major' => $this->major,
                'level' => $this->level,
                'phone' => $this->phone,
                'department_id' => $this->department_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'acceptance_proof' => $proofPath,
                'status' => 'pending',
            ]);

            auth()->login($user);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal upload file: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.multi-step-registration');
    }
}
