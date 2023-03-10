@extends('layouts.app')

@section('title', '一覧')

@section('content')
<div class="mt-4">
  <h6 class="mb-4">{{ $facility->name }}</h6>
  @if (session('status'))
  <div class="alert alert-info alert-dismissible fade show" role="alert">
    {{ session('status') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
  <small><div id="calendar"></div></small>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      let calendarEl = document.getElementById('calendar');
      let calendar = new FullCalendar.Calendar(calendarEl, {
        themeSystem: 'bootstrap5',
        initialView: 'timeGridWeek',
        headerToolbar: {
          left: 'today',
          center: 'title',
          right: 'next'
        },
        events: "/api/facilities/{{ $facility->id }}/reservations",
        eventDidMount: (e) => {
          tippy(e.el, {
            content: e.event.extendedProps.description
          });
        },
        selectable: true,
        select: function(info) {
          document.location.href = "/facilities/{{ $facility->id }}/reservations/create?start=" + info.startStr + "&end=" + info.endStr;
        },
        allDaySlot: false,
        slotMinTime: '06:00:00',
        slotMaxTime: '21:00:00',
        contentHeight: 'auto',
        timeZone: 'Asia/Tokyo',
        locale: 'ja'
      });
      calendar.render();
    });
  </script>
</div>
@endsection
