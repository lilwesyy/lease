@if(session('notification'))
        <div class="alert alert-{{ session('notification')['type'] }}" id="notification" style="position: fixed; bottom: 10px; right: 10px; z-index: 1000;">
            {{ session('notification')['message'] }}
        </div>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }, 5000); // 5000 milliseconds = 5 seconds
    });
</script>
