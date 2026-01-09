<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
// Toast Notification System
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `transform transition-all duration-300 ease-in-out translate-x-full opacity-0 max-w-sm w-full shadow-lg rounded-lg pointer-events-auto flex ring-1 ring-black ring-opacity-5`;
    
    // Set colors based on type
    let bgColor, textColor, icon;
    switch(type) {
        case 'success':
            bgColor = 'bg-green-50';
            textColor = 'text-green-800';
            icon = '✓';
            break;
        case 'error':
            bgColor = 'bg-red-50';
            textColor = 'text-red-800';
            icon = '✕';
            break;
        case 'warning':
            bgColor = 'bg-yellow-50';
            textColor = 'text-yellow-800';
            icon = '⚠';
            break;
        case 'info':
            bgColor = 'bg-blue-50';
            textColor = 'text-blue-800';
            icon = 'ℹ';
            break;
        default:
            bgColor = 'bg-gray-50';
            textColor = 'text-gray-800';
            icon = '•';
    }

    toast.innerHTML = `
        <div class="${bgColor} p-4 flex-1 flex items-center">
            <div class="flex-shrink-0">
                <span class="${textColor} text-xl font-bold">${icon}</span>
            </div>
            <div class="ml-3 flex-1">
                <p class="${textColor} text-sm font-medium">${message}</p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button onclick="this.closest('.transform').remove()" class="${textColor} hover:${textColor.replace('800', '900')} focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    `;

    container.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    }, 10);

    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Check for URL parameters for messages
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.has('success')) {
        showToast(urlParams.get('success'), 'success');
    }
    if (urlParams.has('error')) {
        showToast(urlParams.get('error'), 'error');
    }
    if (urlParams.has('warning')) {
        showToast(urlParams.get('warning'), 'warning');
    }
    if (urlParams.has('info')) {
        showToast(urlParams.get('info'), 'info');
    }
    
    // Clean URL after showing message
    if (urlParams.has('success') || urlParams.has('error') || urlParams.has('warning') || urlParams.has('info')) {
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }
});
</script>
