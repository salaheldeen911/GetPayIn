@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Dashboard</h1>

    <div class="space-y-6">
        <!-- Stats -->
        <livewire:dashboard-stats />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Calendar View -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <livewire:calendar-view />
                </div>
            </div>

            <!-- Upcoming Posts -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <livewire:upcoming-posts />
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.querySelector('.calendar-container');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: {!! json_encode(auth()->user()->posts()->scheduled()->get()->map(function($post) {
                    return [
                        'title' => $post->title,
                        'start' => $post->scheduled_at,
                        'url' => route('posts.edit', $post),
                        'backgroundColor' => $post->status === 'published' ? '#34D399' : '#60A5FA',
                    ];
                })) !!}
            });
            calendar.render();
        });
    </script>
    @endpush
@endsection
