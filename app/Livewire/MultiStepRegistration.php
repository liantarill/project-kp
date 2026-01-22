<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Department;
use App\Models\Institution;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MultiStepRegistration extends Component
{
    use WithFileUploads;

    public $currentStep = 1;
    public $totalSteps = 4;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $level;
    public $institution_id;
    public $major;
    public $phone;
    public $department_id;
    public $start_date;
    public $end_date;
    public $acceptance_proof;
    public $institutions;
    public $departments;

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
                'level' => 'required|in:SMA,D1,D2,D3,D4,S1',
                'institution_id' => 'required|exists:institutions,id',
                'major' => 'required|string|max:255',
                'phone' => 'required|string|max:20|regex:/^[0-9]+$/',
            ],
            3 => [
                'department_id' => 'required|exists:departments,id',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'acceptance_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ],
        ];

        return $rules[$this->currentStep] ?? [];
    }

    // Custom validation messages
    protected function messages()
    {
        return [
            // Step 1
            'name.required' => 'Nama lengkap wajib diisi',
            'name.max' => 'Nama terlalu panjang (maksimal 255 karakter)',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',

            // Step 2
            'level.required' => 'Jenjang pendidikan wajib dipilih',
            'level.in' => 'Jenjang pendidikan tidak valid',
            'institution_id.required' => 'Institusi wajib dipilih',
            'institution_id.exists' => 'Institusi tidak valid',
            'major.required' => 'Jurusan wajib diisi',
            'major.max' => 'Jurusan terlalu panjang (maksimal 255 karakter)',
            'phone.required' => 'Nomor telepon wajib diisi',
            'phone.max' => 'Nomor telepon terlalu panjang',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka',

            // Step 3
            'department_id.required' => 'Bagian wajib dipilih',
            'department_id.exists' => 'Bagian tidak valid',
            'start_date.required' => 'Tanggal mulai wajib diisi',
            'start_date.date' => 'Format tanggal mulai tidak valid',
            'start_date.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini',
            'end_date.required' => 'Tanggal selesai wajib diisi',
            'end_date.date' => 'Format tanggal selesai tidak valid',
            'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai',
            'acceptance_proof.required' => 'Bukti penerimaan wajib diupload',
            'acceptance_proof.file' => 'File tidak valid',
            'acceptance_proof.mimes' => 'File harus berformat PDF, JPG, JPEG, atau PNG',
            'acceptance_proof.max' => 'Ukuran file maksimal 2MB',
        ];
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

        // Reset validation errors saat kembali
        $this->resetErrorBag();
    }

    public function submit()
    {
        // Validate all steps before submission
        $this->validateAllSteps();

        try {
            // Upload file dengan nama unik
            $fileName = time() . '_' . $this->acceptance_proof->getClientOriginalName();
            $proofPath = $this->acceptance_proof->storeAs('acceptance-proofs', $fileName, 'public');

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

            // Login user otomatis
            auth()->login($user);

            // Flash success message
            session()->flash('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu verifikasi.');

            auth()->user()->sendEmailVerificationNotification();

            // Redirect ke dashboard
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            // Hapus file jika ada error
            if (isset($proofPath) && Storage::disk('public')->exists($proofPath)) {
                Storage::disk('public')->delete($proofPath);
            }

            // Log error untuk debugging
            Log::error('Registration Error: ' . $e->getMessage());

            session()->flash('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.');

            // Kembali ke step 3 untuk perbaikan
            $this->currentStep = 3;
        }
    }

    // Validate semua step sebelum submit
    private function validateAllSteps()
    {
        $allRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'level' => 'required|in:SMA,D1,D2,D3,D4,S1',
            'institution_id' => 'required|exists:institutions,id',
            'major' => 'required|string|max:255',
            'phone' => 'required|string|max:20|regex:/^[0-9]+$/',
            'department_id' => 'required|exists:departments,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'acceptance_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        $this->validate($allRules);
    }

    // Updated file untuk re-validation
    public function updatedAcceptanceProof()
    {
        $this->validateOnly('acceptance_proof', [
            'acceptance_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
    }

    // Real-time validation untuk fields tertentu
    public function updated($propertyName)
    {
        // Validate field saat user mengetik (optional, bisa dinonaktifkan jika mengganggu UX)
        if (in_array($propertyName, ['email', 'phone'])) {
            $this->validateOnly($propertyName);
        }
    }

    public function render()
    {
        return view('livewire.multi-step-registration');
    }
}
