<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('education')) {
            $query->where('education', $request->education);
        }
        
        if ($request->filled('interest')) {
            $query->where('interest', $request->interest);
        }
        
        // Mengambil daftar ID user yang tidak ingin ditampilkan dari session
        $notInterestedUsers = session('not_interested', []);
        
        // Exclude users yang ada di not_interested
        if (!empty($notInterestedUsers)) {
            $query->whereNotIn('id', $notInterestedUsers);
        }

        $query->where('id', '!=', auth()->id());
        
        $users = $query->paginate(5);
        
        return view('users.index', compact('users'));
    }

    public function addFriend($id)
    {
        $friendship = Friendship::create([
            'user_id' => auth()->id(),
            'friend_id' => $id,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Friend request sent!');
    }

    public function acceptFriend($id)
    {
        $friendship = Friendship::where('friend_id', auth()->id())
            ->where('user_id', $id)
            ->first();

        if ($friendship) {
            $friendship->update(['status' => 'accepted']);
        }

        return redirect()->back()->with('success', 'Friend request accepted!');
    }

    public function rejectFriend($id)
    {
        $friendship = Friendship::where('friend_id', auth()->id())
            ->where('user_id', $id)
            ->first();

        if ($friendship) {
            $friendship->update(['status' => 'rejected']);
        }

        return redirect()->back()->with('success', 'Friend request rejected!');
    }

    public function notInterested($id)
    {
        // Ambil daftar not_interested yang sudah ada
        $notInterestedUsers = session('not_interested', []);
        
        // Tambahkan ID baru ke daftar
        $notInterestedUsers[] = $id;
        
        // Simpan kembali ke session
        session(['not_interested' => $notInterestedUsers]);
        
        return redirect()->back()->with('success', 'User removed from list');
    }
}
