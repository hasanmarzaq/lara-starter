@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <form action="{{ route('reports.export_bookings') }}" method="POST">
            @csrf
            <div class="card-body">
                <!-- Filter Type Selection -->
                <div class="form-group">
                    <label for="time_filter_type">{{ __('Select Time Filter') }}</label>
                    <select name="time_filter_type" id="time_filter_type" class="form-control">
                        <option value="">{{ __('Select Time Filter') }}</option>
                        <option value="date_range">{{ __('Date Range') }}</option>
                        <option value="monthly">{{ __('Monthly') }}</option>
                        <option value="yearly">{{ __('Annual') }}</option>
                    </select>
                </div>

                <!-- Date Range Filter (hidden by default) -->
                <div class="form-group time-filter date_range d-none">
                    <label for="date_range">{{ __('Date Range') }}</label>
                    <input type="text" name="date_range" id="date_range" class="form-control">
                </div>

                <!-- Month and Year Filter (hidden by default) -->
                <div class="row time-filter monthly d-none">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="month">{{ __('Select Month') }}</label>
                            <select name="month" id="month" class="form-control">
                                @foreach ($months as $key => $month)
                                    <option value="{{ $key }}">{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="year">{{ __('Select Year') }}</label>
                            <select name="year" id="year" class="form-control">
                                @for ($i = date('Y'); $i >= date('Y') - 20; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Year Filter (hidden by default) -->
                <div class="form-group time-filter yearly d-none">
                    <label for="year_only">{{ __('Select Year') }}</label>
                    <select name="year_only" id="year_only" class="form-control">
                        @for ($i = date('Y'); $i >= date('Y') - 20; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="vehicle">{{ __('Select Vehicle') }}</label>
                            <select name="vehicle_id" id="vehicle" class="form-control">
                                <option value="">{{ __('All Vehicles') }}</option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="customer">{{ __('Select Customer') }}</label>
                            <select name="customer_id" id="customer" class="form-control">
                                <option value="">{{ __('All Customers') }}</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="payment_method">{{ __('Select Payment Method') }}</label>
                            <select name="payment_method" id="payment_method" class="form-control">
                                <option value="">{{ __('All Payment Method') }}</option>
                                @foreach ($payment_methods as $paymentMethod)
                                    <option value="{{ $paymentMethod->name }}">{{ $paymentMethod->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="booking_status">{{ __('Select Booking Status') }}</label>
                            <select name="booking_status" id="booking_status" class="form-control">
                                <option value="">{{ __('All Statuses') }}</option>
                                @foreach (\App\Enums\TransactionStatus::map() as $key => $status)
                                    @if (in_array($key, [
                                            \App\Enums\TransactionStatus::DOWN_PAYMENT,
                                            \App\Enums\TransactionStatus::PAID,
                                            \App\Enums\TransactionStatus::FINISHED,
                                        ]))
                                        <option value="{{ $key }}">{{ $status }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary form-control">
                    <i class="fa fa-file-excel"></i> {{ __('Download') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@section('script_addon_footer')
    <!-- daterangepicker -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
    <script type="text/javascript" src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(function() {
            $('#date_range').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Show/Hide Filters based on Selection
            $('#time_filter_type').change(function() {
                $('.time-filter').addClass('d-none');
                var filterType = $(this).val();
                if (filterType) {
                    $('.' + filterType).removeClass('d-none');
                }
            });

            // SweetAlert loading for All Export Form Submissions
            const exportForms = document.querySelectorAll('form');

            exportForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Prevent default form submission

                    // Show SweetAlert loading animation
                    Swal.fire({
                        title: '{{ __('Processing') }}',
                        text: '{{ __('Please wait while we generate your report') }}',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form after small delay
                    setTimeout(() => {
                        Swal.close(); // Close SweetAlert before submitting
                        form.submit(); // Proceed with form submission
                    }, 1000);
                });
            });
        });
    </script>
@endsection