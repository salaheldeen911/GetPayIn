<div>
    <h3 class="text-lg font-medium text-gray-900 mb-4">Calendar View</h3>
    <div class="calendar-container min-h-[400px]"></div>

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
                events: @json($events)
            });
            calendar.render();

            // Listen for Livewire events to update the calendar
            Livewire.on('refreshCalendar', events => {
                calendar.removeAllEvents();
                calendar.addEventSource(events);
            });
        });
    </script>
    @endpush
</div> 