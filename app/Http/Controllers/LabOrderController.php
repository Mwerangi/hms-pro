<?php

namespace App\Http\Controllers;

use App\Models\LabOrder;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LabOrderController extends Controller
{
    /**
     * Lab Tech Dashboard - Shows pending and in-progress orders
     */
    public function dashboard()
    {
        $pendingOrders = LabOrder::with(['patient', 'doctor', 'consultation'])
            ->whereIn('status', ['pending', 'sample-collected', 'in-progress'])
            ->where('test_type', '!=', 'imaging')
            ->orderByRaw("FIELD(urgency, 'stat', 'urgent', 'routine')")
            ->orderByRaw("FIELD(priority, 'critical', 'high', 'normal')")
            ->orderBy('order_date', 'asc')
            ->get();

        $stats = [
            'pending' => LabOrder::where('status', 'pending')->where('test_type', '!=', 'imaging')->count(),
            'sample_collected' => LabOrder::where('status', 'sample-collected')->where('test_type', '!=', 'imaging')->count(),
            'in_progress' => LabOrder::where('status', 'in-progress')->where('test_type', '!=', 'imaging')->count(),
            'urgent' => LabOrder::whereIn('status', ['pending', 'sample-collected', 'in-progress'])
                ->where('test_type', '!=', 'imaging')
                ->whereIn('urgency', ['urgent', 'stat'])
                ->count(),
        ];

        return view('lab.dashboard', compact('pendingOrders', 'stats'));
    }

    /**
     * Radiology Dashboard - Shows pending imaging orders
     */
    public function radiologyDashboard()
    {
        $pendingOrders = LabOrder::with(['patient', 'doctor', 'consultation'])
            ->whereIn('status', ['pending', 'in-progress'])
            ->where('test_type', 'imaging')
            ->orderByRaw("FIELD(urgency, 'stat', 'urgent', 'routine')")
            ->orderByRaw("FIELD(priority, 'critical', 'high', 'normal')")
            ->orderBy('scheduled_at', 'asc')
            ->get();

        $stats = [
            'pending' => LabOrder::where('status', 'pending')->where('test_type', 'imaging')->count(),
            'in_progress' => LabOrder::where('status', 'in-progress')->where('test_type', 'imaging')->count(),
            'urgent' => LabOrder::whereIn('status', ['pending', 'in-progress'])
                ->where('test_type', 'imaging')
                ->whereIn('urgency', ['urgent', 'stat'])
                ->count(),
        ];

        return view('lab.radiology-dashboard', compact('pendingOrders', 'stats'));
    }

    /**
     * Display a listing of all lab orders
     */
    public function index(Request $request)
    {
        $query = LabOrder::with(['patient', 'doctor', 'consultation']);

        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('test_type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by urgency
        if ($request->has('urgency') && $request->urgency !== 'all') {
            $query->where('urgency', $request->urgency);
        }

        $labOrders = $query->orderBy('order_date', 'desc')->paginate(20);

        return view('lab.index', compact('labOrders'));
    }

    /**
     * Show lab order details
     */
    public function show(LabOrder $labOrder)
    {
        $labOrder->load(['patient', 'doctor', 'consultation', 'collectedBy', 'processedBy', 'reportedBy']);
        return view('lab.show', compact('labOrder'));
    }

    /**
     * Sample collection form
     */
    public function collectSampleForm(LabOrder $labOrder)
    {
        if (!$labOrder->canCollectSample()) {
            return redirect()->route('lab.show', $labOrder)
                ->with('error', 'Sample has already been collected for this order.');
        }

        return view('lab.collect-sample', compact('labOrder'));
    }

    /**
     * Store sample collection
     */
    public function storeSampleCollection(Request $request, LabOrder $labOrder)
    {
        $request->validate([
            'collection_notes' => 'nullable|string',
        ]);

        $labOrder->collectSample();
        
        if ($request->collection_notes) {
            $labOrder->lab_technician_notes = $request->collection_notes;
            $labOrder->save();
        }

        return redirect()->route('lab.show', $labOrder)
            ->with('success', 'Sample collected successfully.');
    }

    /**
     * Process lab order form
     */
    public function processForm(LabOrder $labOrder)
    {
        if (!$labOrder->canProcess()) {
            return redirect()->route('lab.show', $labOrder)
                ->with('error', 'This order cannot be processed at this time.');
        }

        return view('lab.process', compact('labOrder'));
    }

    /**
     * Store processing status
     */
    public function storeProcess(Request $request, LabOrder $labOrder)
    {
        $labOrder->startProcessing();

        return redirect()->route('lab.show', $labOrder)
            ->with('success', 'Lab order processing started.');
    }

    /**
     * Results entry form
     */
    public function resultsForm(LabOrder $labOrder)
    {
        if (!$labOrder->canComplete()) {
            return redirect()->route('lab.show', $labOrder)
                ->with('error', 'Cannot enter results for this order at this time.');
        }

        return view('lab.results', compact('labOrder'));
    }

    /**
     * Store results
     */
    public function storeResults(Request $request, LabOrder $labOrder)
    {
        $request->validate([
            'result_values' => 'nullable|array',
            'result_values.*' => 'nullable|string',
            'results' => 'required|string',
            'result_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'lab_technician_notes' => 'nullable|string',
        ]);

        // Upload result file if provided
        if ($request->hasFile('result_file')) {
            $path = $request->file('result_file')->store('lab-results', 'public');
            $labOrder->result_file_path = $path;
        }

        $labOrder->result_values = $request->result_values;
        $labOrder->results = $request->results;
        $labOrder->lab_technician_notes = $request->lab_technician_notes;
        $labOrder->complete();

        return redirect()->route('lab.show', $labOrder)
            ->with('success', 'Lab results recorded successfully.');
    }

    /**
     * Radiology processing form
     */
    public function radiologyProcessForm(LabOrder $labOrder)
    {
        if ($labOrder->test_type !== 'imaging') {
            return redirect()->route('lab.show', $labOrder)
                ->with('error', 'This is not an imaging order.');
        }

        return view('lab.radiology-process', compact('labOrder'));
    }

    /**
     * Store radiology results
     */
    public function storeRadiologyResults(Request $request, LabOrder $labOrder)
    {
        $request->validate([
            'radiologist_findings' => 'required|string',
            'imaging_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,dcm|max:51200', // Up to 50MB for images
            'result_file' => 'nullable|file|mimes:pdf|max:10240',
            'lab_technician_notes' => 'nullable|string',
        ]);

        // Upload imaging file
        if ($request->hasFile('imaging_file')) {
            $path = $request->file('imaging_file')->store('radiology-images', 'public');
            $labOrder->imaging_file_path = $path;
        }

        // Upload radiology report
        if ($request->hasFile('result_file')) {
            $path = $request->file('result_file')->store('radiology-reports', 'public');
            $labOrder->result_file_path = $path;
        }

        $labOrder->radiologist_findings = $request->radiologist_findings;
        $labOrder->results = $request->radiologist_findings;
        $labOrder->lab_technician_notes = $request->lab_technician_notes;
        $labOrder->complete();
        $labOrder->report($request->radiologist_findings);

        return redirect()->route('lab.show', $labOrder)
            ->with('success', 'Radiology report submitted successfully.');
    }

    /**
     * Mark as reported
     */
    public function markReported(Request $request, LabOrder $labOrder)
    {
        if (!$labOrder->canReport()) {
            return redirect()->route('lab.show', $labOrder)
                ->with('error', 'Cannot report this order at this time.');
        }

        $labOrder->report($labOrder->results ?? 'Report generated');

        return redirect()->route('lab.show', $labOrder)
            ->with('success', 'Order marked as reported.');
    }

    /**
     * Cancel lab order
     */
    public function cancel(Request $request, LabOrder $labOrder)
    {
        if (!$labOrder->canBeCancelled()) {
            return redirect()->route('lab.show', $labOrder)
                ->with('error', 'This order cannot be cancelled.');
        }

        $labOrder->cancel();

        return redirect()->route('lab.show', $labOrder)
            ->with('success', 'Lab order cancelled successfully.');
    }

    /**
     * Download result file
     */
    public function downloadResult(LabOrder $labOrder)
    {
        if (!$labOrder->result_file_path) {
            return redirect()->back()->with('error', 'No result file available.');
        }

        return Storage::disk('public')->download($labOrder->result_file_path);
    }

    /**
     * Download imaging file
     */
    public function downloadImaging(LabOrder $labOrder)
    {
        if (!$labOrder->imaging_file_path) {
            return redirect()->back()->with('error', 'No imaging file available.');
        }

        return Storage::disk('public')->download($labOrder->imaging_file_path);
    }
}
