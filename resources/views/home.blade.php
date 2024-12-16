@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header text-white text-center py-4" style="background: linear-gradient(to right, #6a11cb, #2575fc);">
                <h2 class="mb-0 fw-bold">{{ __('messages.dashboard') }}</h2>
            </div>
            <div class="card-body bg-light py-5">
                <div class="row gy-3">
                    <div class="col-md-6 d-flex justify-content-center">
                        <a href="{{ route('tasks.create') }}" class="btn btn-lg btn-primary d-flex align-items-center gap-2 shadow-sm w-75 rounded-3">
                            <i class="fas fa-plus-circle"></i>
                            <span>{{ __('messages.create_task') }}</span>
                        </a>
                    </div>

                    <div class="col-md-6 d-flex justify-content-center">
                        <a href="{{ route('tasks.index') }}" class="btn btn-lg btn-secondary d-flex align-items-center gap-2 shadow-sm w-75 rounded-3">
                            <i class="fas fa-list-ul"></i>
                            <span>{{ __('messages.view_tasks') }}</span>
                        </a>
                    </div>

                    <div class="col-md-6 d-flex justify-content-center">
                        <a href="{{ route('categories.create') }}" class="btn btn-lg btn-success d-flex align-items-center gap-2 shadow-sm w-75 rounded-3">
                            <i class="fas fa-folder-plus"></i>
                            <span>{{ __('messages.create_category') }}</span>
                        </a>
                    </div>

                    <div class="col-md-6 d-flex justify-content-center">
                        <a href="{{ route('categories.index') }}" class="btn btn-lg btn-info text-white d-flex align-items-center gap-2 shadow-sm w-75 rounded-3">
                            <i class="fas fa-folder-open"></i>
                            <span>{{ __('messages.view_categories') }}</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
