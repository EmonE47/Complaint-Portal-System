<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Complaint;
use App\Models\CaseAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Get messages for a specific complaint
     */
    public function getMessages(Request $request, $complaintId)
    {
        $user = Auth::user();

        // Validate that user has access to this complaint
        $complaint = Complaint::findOrFail($complaintId);

        // Check if user is the complainant or assigned SI
        $hasAccess = false;

        if ($user->role == 1) { // Regular user
            $hasAccess = ($complaint->email == $user->email || $complaint->voter_id_number == $user->voter_id_number);
        } elseif ($user->role == 2) { // SI
            $hasAccess = CaseAssignment::where('complaint_id', $complaintId)
                ->where('user_id', $user->id)
                ->whereIn('status', ['assigned', 'in_progress'])
                ->exists();
        }

        if (!$hasAccess) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get messages between user and assigned SI
        $assignedSI = $complaint->currentAssignment ? $complaint->currentAssignment->user : null;

        if (!$assignedSI) {
            return response()->json(['messages' => [], 'assigned_si' => null]);
        }

        $messages = Message::where('complaint_id', $complaintId)
            ->where(function ($query) use ($user, $assignedSI) {
                $query->where(function ($q) use ($user, $assignedSI) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $assignedSI->id);
                })->orWhere(function ($q) use ($user, $assignedSI) {
                    $q->where('sender_id', $assignedSI->id)->where('receiver_id', $user->id);
                });
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read if user is the receiver
        Message::where('complaint_id', $complaintId)
            ->where('receiver_id', $user->id)
            ->where('status', '!=', 'read')
            ->update(['status' => 'read', 'read_at' => now()]);

        return response()->json([
            'messages' => $messages,
            'assigned_si' => $assignedSI,
            'can_chat' => true
        ]);
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'complaint_id' => 'required|exists:complaints,id',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $complaintId = $request->complaint_id;

        // Validate that user has access to this complaint
        $complaint = Complaint::findOrFail($complaintId);

        // Check if user is the complainant or assigned SI
        $hasAccess = false;
        $receiverId = null;

        if ($user->role == 1) { // Regular user
            $hasAccess = ($complaint->email == $user->email || $complaint->voter_id_number == $user->voter_id_number);
            if ($hasAccess && $complaint->currentAssignment) {
                $receiverId = $complaint->currentAssignment->user_id;
            }
        } elseif ($user->role == 2) { // SI
            $assignment = CaseAssignment::where('complaint_id', $complaintId)
                ->where('user_id', $user->id)
                ->whereIn('status', ['assigned', 'in_progress'])
                ->first();

            if ($assignment) {
                $hasAccess = true;
                // Find the complainant user
                $complainant = User::where('email', $complaint->email)->first();
                $receiverId = $complainant ? $complainant->id : null;
            }
        }

        if (!$hasAccess || !$receiverId) {
            return response()->json(['error' => 'Unauthorized or no assigned SI'], 403);
        }

        // Create the message
        $message = Message::create([
            'complaint_id' => $complaintId,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'status' => 'sent',
        ]);

        // Load relationships for response
        $message->load(['sender', 'receiver']);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(Request $request, $complaintId)
    {
        $user = Auth::user();

        Message::where('complaint_id', $complaintId)
            ->where('receiver_id', $user->id)
            ->where('status', '!=', 'read')
            ->update(['status' => 'read', 'read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread message count for user
     */
    public function getUnreadCount(Request $request)
    {
        $user = Auth::user();

        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('status', '!=', 'read')
            ->count();

        return response()->json(['unread_count' => $unreadCount]);
    }

    /**
     * Get all conversations for the authenticated user
     */
    public function getConversations(Request $request)
    {
        $user = Auth::user();
        $conversations = [];

        if ($user->role == 1) { // Regular user
            // Get all complaints where user is the complainant
            $complaints = Complaint::where('email', $user->email)
                ->orWhere('voter_id_number', $user->voter_id_number)
                ->with(['currentAssignment.user'])
                ->get();

            foreach ($complaints as $complaint) {
                if ($complaint->currentAssignment && $complaint->currentAssignment->user) {
                    $si = $complaint->currentAssignment->user;

                    // Get latest message in this conversation
                    $latestMessage = Message::where('complaint_id', $complaint->id)
                        ->where(function ($query) use ($user, $si) {
                            $query->where(function ($q) use ($user, $si) {
                                $q->where('sender_id', $user->id)->where('receiver_id', $si->id);
                            })->orWhere(function ($q) use ($user, $si) {
                                $q->where('sender_id', $si->id)->where('receiver_id', $user->id);
                            });
                        })
                        ->with(['sender', 'receiver'])
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($latestMessage) {
                        $conversations[] = [
                            'complaint_id' => $complaint->id,
                            'complaint_number' => $complaint->complaint_number,
                            'complaint_type' => $complaint->complaint_type,
                            'si_name' => $si->name,
                            'si_id' => $si->id,
                            'latest_message' => $latestMessage,
                            'unread_count' => Message::where('complaint_id', $complaint->id)
                                ->where('receiver_id', $user->id)
                                ->where('status', '!=', 'read')
                                ->count()
                        ];
                    }
                }
            }
        } elseif ($user->role == 2) { // SI
            // Get all assigned cases
            $assignments = CaseAssignment::where('user_id', $user->id)
                ->whereIn('status', ['assigned', 'in_progress'])
                ->with(['complaint'])
                ->get();

            foreach ($assignments as $assignment) {
                $complaint = $assignment->complaint;

                // Find the complainant user
                $complainant = User::where('email', $complaint->email)->first();

                if ($complainant) {
                    // Get latest message in this conversation
                    $latestMessage = Message::where('complaint_id', $complaint->id)
                        ->where(function ($query) use ($user, $complainant) {
                            $query->where(function ($q) use ($user, $complainant) {
                                $q->where('sender_id', $user->id)->where('receiver_id', $complainant->id);
                            })->orWhere(function ($q) use ($user, $complainant) {
                                $q->where('sender_id', $complainant->id)->where('receiver_id', $user->id);
                            });
                        })
                        ->with(['sender', 'receiver'])
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($latestMessage) {
                        $conversations[] = [
                            'complaint_id' => $complaint->id,
                            'complaint_number' => $complaint->complaint_number,
                            'complaint_type' => $complaint->complaint_type,
                            'user_name' => $complainant->name,
                            'user_id' => $complainant->id,
                            'latest_message' => $latestMessage,
                            'unread_count' => Message::where('complaint_id', $complaint->id)
                                ->where('receiver_id', $user->id)
                                ->where('status', '!=', 'read')
                                ->count()
                        ];
                    }
                }
            }
        }

        // Sort conversations by latest message time
        usort($conversations, function($a, $b) {
            return strtotime($b['latest_message']->created_at) - strtotime($a['latest_message']->created_at);
        });

        return response()->json(['conversations' => $conversations]);
    }
}
