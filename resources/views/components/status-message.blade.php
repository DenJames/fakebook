@if ($message)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Toast.fire({
                icon: 'warning',
                title: '{{ $message }}'
            })
        })
    </script>
@endif
