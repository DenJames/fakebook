@if ($message)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Toast.fire({
                icon: 'error',
                title: '{{ $message }}'
            })
        })
    </script>
@endif
