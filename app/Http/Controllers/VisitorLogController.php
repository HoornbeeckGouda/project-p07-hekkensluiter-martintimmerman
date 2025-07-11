<?php
namespace App\Http\Controllers;
use App\Models\VisitorLog;
use App\Models\Prisoner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitorLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:visitor.view')->only(['index']);
        $this->middleware('permission:visitor.register')->only(['create', 'store', 'checkOut']);
        $this->middleware('permission:visitor.manage')->only(['edit', 'update', 'destroy']);
    }

    public function index()
    {
        $logs = VisitorLog::with(['prisoner', 'creator'])->latest()->get();
        return view('visitor_logs.index', compact('logs'));
    }

    public function create()
    {
        $prisoners = Prisoner::all();
        return view('visitor_logs.create', compact('prisoners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'visitor_name' => 'required|string|max:255',
            'id_document_type' => 'required|in:paspoort,id_kaart',
            'id_document_number' => 'required|string|max:50',
            'visit_purpose' => 'nullable|string|max:255',
            'prisoner_id' => 'nullable|exists:prisoners,id',
        ]);

        VisitorLog::create([
            'visitor_name' => $request->visitor_name,
            'id_document_type' => $request->id_document_type,
            'id_document_number' => $request->id_document_number,
            'arrival_time' => now(),
            'visit_purpose' => $request->visit_purpose,
            'prisoner_id' => $request->prisoner_id,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('visitor_logs.index')->with('success', 'Bezoeker geregistreerd.');
    }

    public function checkOut(VisitorLog $visitorLog)
    {
        $this->authorize('update', $visitorLog);
        $visitorLog->update(['departure_time' => now()]);
        return redirect()->route('visitor_logs.index')->with('success', 'Bezoeker uitgecheckt.');
    }
}