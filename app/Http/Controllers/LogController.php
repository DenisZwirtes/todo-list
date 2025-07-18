<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Exibe a lista de logs
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Log::with('user')
            ->orderBy('created_at', 'desc');

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhere('error_message', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $perPage = $request->get('per_page', 50);
        $logs = $query->paginate($perPage);

        $stats = [
            'total' => Log::count(),
            'errors' => Log::where('level', 'error')->count(),
            'warnings' => Log::where('level', 'warning')->count(),
            'info' => Log::where('level', 'info')->count(),
            'debug' => Log::where('level', 'debug')->count(),
        ];

        return view('logs.index', compact('logs', 'stats'));
    }

    /**
     * Exibe detalhes de um log específico
     */
    public function show(Log $log): \Illuminate\View\View
    {
        return view('logs.show', compact('log'));
    }

    /**
     * Limpa logs antigos
     */
    public function clear(Request $request): RedirectResponse
    {
        $days = $request->get('days', 30);
        $deleted = Log::where('created_at', '<', now()->subDays($days))->delete();

        return redirect()->route('logs.index')
            ->with('success', "{$deleted} logs antigos foram removidos.");
    }


    public function export(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $query = Log::with('user')->orderBy('created_at', 'desc');

        // Aplicar filtros
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date);
        }

        $logs = $query->get();

        $filename = 'logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');

            // Cabeçalho
            fputcsv($file, [
                'ID', 'Level', 'Message', 'Error Message', 'File', 'Line',
                'IP Address', 'User Agent', 'User ID', 'Created At'
            ]);

            // Dados
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->level,
                    $log->message,
                    $log->error_message,
                    $log->file,
                    $log->line,
                    $log->ip_address,
                    $log->user_agent,
                    $log->user_id,
                    $log->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
