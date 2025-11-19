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
            ->join('directorates as d', 'd.id', '=', 'me.directorate_id')
            ->select('me.id', 'me.moph_id', 'me.email', 'me.directorate_id', 'd.name as directorate_name')
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
            'directorate_id' => 'required|exists:directorates,id',
            'email' => 'required|email|unique:moph_emails,email',
        ]);

        $generatedId = 'ME-' . strtoupper(Str::random(8));

        $row = MophEmail::create([
            'moph_id' => $generatedId,
            'directorate_id' => $validated['directorate_id'],
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
            'directorate_id' => 'required|exists:directorates,id',
            'email' => 'required|email|unique:moph_emails,email,' . $row->id,
        ]);

        $row->update([
            'directorate_id' => $validated['directorate_id'],
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
