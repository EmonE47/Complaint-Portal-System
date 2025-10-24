<?php
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\InspectorRegistrationController;
use Illuminate\Support\Facades\Route;

// Route::get('/', [InspectorController::class, 'siDashboard'])->middleware(['auth', 'role:2'])->name('home');
Route ::view('/', 'welcome')->name('home');
// Registration routes
Route::view('register', 'register')->name('register'); // GET - Show registration form
Route::post('registerSave', [UserController::class, 'register'])->name('registerSave'); // POST - Process registration

// Inspector Registration routes
Route::get('inspector/register', [InspectorRegistrationController::class, 'showRegistrationForm'])->name('inspector.register.form'); // GET - Show inspector registration form
Route::post('inspector/register', [InspectorRegistrationController::class, 'register'])->name('inspector.register'); // POST - Process inspector registration

// Login routes
Route::view('login', 'login')->name('login'); // GET - Show login form
Route::post('loginMatch', [UserController::class, 'login'])->name('loginMatch'); // POST - Process login

// Dashboard route (ONLY GET - Remove the POST version)
Route::get('dashboard', [UserController::class, 'dashboardPage'])->name('dashboard');

// User profile update route
Route::put('profile/update', [UserController::class, 'updateProfile'])->name('profile.update')->middleware('auth');

// Logout route (Should be POST for security)
Route::post('logout', [UserController::class, 'logout'])->name('logout');



// Complaint routes
// Complaint routes
Route::get('complaints', [ComplaintController::class, 'index'])->name('complaints');
Route::post('complaints/store', [ComplaintController::class, 'store'])->name('complaints.store');
Route::get('complaints/{id}', [ComplaintController::class, 'show'])->name('complaints.show');

// Message routes
Route::middleware('auth')->group(function () {
    Route::get('messages/{complaintId}', [MessageController::class, 'getMessages'])->name('messages.get');
    Route::post('messages/send', [MessageController::class, 'sendMessage'])->name('messages.send');
    Route::post('messages/{complaintId}/mark-read', [MessageController::class, 'markAsRead'])->name('messages.markRead');
    Route::get('messages/unread-count', [MessageController::class, 'getUnreadCount'])->name('messages.unreadCount');
    Route::get('messages/conversations', [MessageController::class, 'getConversations'])->name('messages.conversations');
});

Route::prefix('inspector')->middleware(['auth', 'role:3'])->group(function () {
    Route::get('/dashboard', [InspectorController::class, 'dashboard'])->name('inspector.dashboard');
    Route::post('/create-complaint', [InspectorController::class, 'createComplaint'])->name('inspector.createComplaint');
    Route::post('/assign-case', [InspectorController::class, 'assignCase'])->name('inspector.assignCase');
    Route::post('/promote-to-si', [InspectorController::class, 'promoteToSI'])->name('inspector.promoteToSI');
    Route::get('/inspector-stats/{inspectorId}', [InspectorController::class, 'getInspectorStats'])->name('inspector.stats');
    Route::post('/assignment/{assignmentId}/status', [InspectorController::class, 'updateAssignmentStatus'])->name('inspector.updateStatus');
    Route::get('/personnel', [InspectorController::class, 'personnel'])->name('inspector.personnel');
    Route::post('/update-profile', [InspectorController::class, 'updateProfile'])->name('inspector.updateProfile');
    Route::post('/change-password', [InspectorController::class, 'changePassword'])->name('inspector.changePassword');
    Route::get('/reports', [InspectorController::class, 'reports'])->name('inspector.reports');
});

// SI routes
Route::prefix('si')->middleware(['auth', 'role:2'])->group(function () {
    Route::get('/dashboard', [InspectorController::class, 'siDashboard'])->name('si.dashboard');
    Route::get('/cases', [InspectorController::class, 'siCases'])->name('si.cases');
    Route::post('/update-status', [InspectorController::class, 'updateComplaintStatus'])->name('si.updateStatus');
    Route::get('/reports', [InspectorController::class, 'siReports'])->name('si.reports');
    Route::get('/profile', [InspectorController::class, 'siProfile'])->name('si.profile');
    Route::post('/update-profile', [InspectorController::class, 'updateProfile'])->name('si.updateProfile');
});
