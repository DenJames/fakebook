@if ($message)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Toast.fire({
                icon: 'success',
                title: '{{ $message }}'
            })
        })
    </script>
@endif
