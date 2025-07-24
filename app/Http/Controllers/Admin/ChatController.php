<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $firestore;

    public function __construct()
    {
        $this->firestore = new FirestoreClient([
            'projectId' => config('firestore.project_id'),
            'keyFilePath' => config('firestore.credentials'),
            'transport' => 'rest',
        ]);
    }

    public function index()
    {
        $title = 'Chat';
        $currentUser = Auth::user();
        return view('admin.chat.index', compact('title', 'currentUser'));
    }

    public function getChatRooms()
    {
        $chatRoomsCollection = $this->firestore->collection('chatRooms')->orderBy('updatedAt', 'DESC');
        $documents = $chatRoomsCollection->documents();
        $chatRooms = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $chatRooms[] = array_merge(['id' => $document->id()], $document->data());
            }
        }
        return response()->json($chatRooms);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'chatRoomId' => 'required|string',
        ]);

        $chatRoomRef = $this->firestore->collection('chatRooms')->document($request->chatRoomId);
        $messagesCollection = $chatRoomRef->collection('messages');

        // Add the new message to the subcollection
        $messagesCollection->add([
            'senderId' => 'admin',
            'text' => $request->message,
            'timestamp' => new \Google\Cloud\Core\Timestamp(new \DateTime()),
        ]);

        // Update the chat room's metadata
        $chatRoomRef->set([
            'updatedAt' => new \Google\Cloud\Core\Timestamp(new \DateTime()),
            'lastMessage' => $request->message,
        ], ['merge' => true]);

        return response()->json(['status' => 'success']);
    }
}
