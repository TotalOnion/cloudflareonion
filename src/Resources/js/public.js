// Initialize SnapSelect on all select elements with the class 'snapSelect'
document.addEventListener('DOMContentLoaded', () => {
    SnapSelect('.snapSelect', {
        liveSearch: true,
        placeholder: 'Select Post Types...',
        clearAllButton: true,
        closeOnSelect: false,
        allowEmpty: true
    });
});