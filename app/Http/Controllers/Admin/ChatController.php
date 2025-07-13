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
        ]);
    }

    public function index()
    {
        $title = 'Chat';
        $currentUser = Auth::user();
        return view('admin.chat.index', compact('title', 'currentUser'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $collectionReference = $this->firestore->collection('chats');
        $collectionReference->add([
            'user' => Auth::user()->name,
            'message' => $request->message,
            'timestamp' => new \Google\Cloud\Core\Timestamp(new \DateTime()),
        ]);

        return response()->json(['status' => 'success']);
    }

    public function getMessages()
    {
        $collectionReference = $this->firestore->collection('chats')->orderBy('timestamp');
        $documents = $collectionReference->documents();

        $messages = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $data = $document->data();
                $messages[] = [
                    'user' => $data['user'],
                    'message' => $data['message'],
                    'timestamp' => $data['timestamp']->get()->format('Y-m-d H:i:s'),
                ];
            }
        }

        return response()->json($messages);
    }
}
