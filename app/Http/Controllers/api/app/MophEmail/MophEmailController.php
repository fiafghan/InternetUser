<?php

namespace App\Http\Controllers\api\app\MophEmail;

use App\Http\Controllers\Controller;
use App\Models\MophEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MophEmailController extends Controller
{
    public function index()
    {
        $u = auth()->user();
        if (!$u || strtolower(($u->role->name ?? '')) === 'viewer') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $rows = DB::table('moph_emails as me')
            ->select('me.id', 'me.moph_id', 'me.email', 'me.directorate')
            ->orderByDesc('me.id')
            ->get();

        return response()->json($rows, 200);
    }

    public function store(Request $request)
    {
        $u = auth()->user();
        if (!$u || strtolower(($u->role->name ?? '')) === 'viewer') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $validated = $request->validate([
            'directorate' => 'required|string|max:255',
            'email' => 'required|email|unique:moph_emails,email',
        ]);

        $generatedId = 'ME-' . strtoupper(Str::random(8));

        $row = MophEmail::create([
            'moph_id' => $generatedId,
            'directorate' => $validated['directorate'],
            'email' => $validated['email'],
        ]);

        return response()->json(['message' => 'Created', 'data' => $row], 201);
    }

    public function update(Request $request, string $id)
    {
        $u = auth()->user();
        if (!$u || strtolower(($u->role->name ?? '')) === 'viewer') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $row = MophEmail::findOrFail($id);

        $validated = $request->validate([
            'directorate' => 'required|string|max:255',
            'email' => 'required|email|unique:moph_emails,email,' . $row->id,
        ]);

        $row->update([
            'directorate' => $validated['directorate'],
            'email' => $validated['email'],
        ]);

        return response()->json(['message' => 'Updated', 'data' => $row], 200);
    }

    public function destroy(string $id)
    {
        $u = auth()->user();
        if (!$u || strtolower(($u->role->name ?? '')) === 'viewer') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $row = MophEmail::findOrFail($id);
        $row->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }
}
