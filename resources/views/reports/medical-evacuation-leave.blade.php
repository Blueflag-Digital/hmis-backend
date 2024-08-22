@yield('LeaveLayout.blade.php')
@section('title', $title)
@section('content')
    <div class="content">
        <p class="label">Patient Name: <span
                class="value">{{ isset($leave->patient) && isset($leave->patient->person) ? $leave->patient->person->getName() : '' }}</span>
        </p>

        <p class="label">Patient ID: <span
                class="value">{{ isset($leave->patient) && isset($leave->patient->person) ? $leave->patient->person->identifier_number : '' }}</span>
        </p>

        <p class="label">Working Days for Sickness Leave: <span class="value">{{ $leave->working_days ?? '' }}</span></p>

        <p class="label">Start Date: <span class="value">{{ $leave->start_date ?? '' }}</span></p>

        <p class="label">End Date: <span class="value">{{ $leave->end_date ?? '' }}</span></p>

        <p class="label">Reason for Sickness Leave:</p>
        <textarea class="form-control">{{ $leave->reason ?? 'Flu and fever.' }}</textarea>

        <p class="label">Doctor Name:</p>
        <p class="value">{{ isset($leave->user) ? $leave->user->name : '' }}</p>

        <div class="signatures">
            <p>Patient's Signature: ______________________</p>
            <p>Doctor's Signature: ______________________</p>
        </div>
    </div>
@endsection
