document.addEventListener('DOMContentLoaded', function() {
    // Function to extract ISO code from a tab identifier like "-en-tab"
    function extractIsoCode(tabId) {
        const match = tabId.match(/-([a-z]{2})-tab/);
        return match ? match[1] : null;
    }

    // Create a global language selection tracking object
    window.languageSelectionCoordinator = {
        // Method to synchronize all buttons for a given language
        synchronizeLanguage: function(isoCode) {
            // Find all buttons that set tab to the language code
            const allLangButtons = document.querySelectorAll(`button[x-on\\:click*="tab = '-${isoCode}-tab'"]`);

            // Click each button to ensure related content is displayed
            allLangButtons.forEach(button => {
                // Skip clicking the button that initiated the change to prevent loops
                if (!button.dataset.triggeredByCoordinator) {
                    button.dataset.triggeredByCoordinator = 'true';
                    button.click();
                    setTimeout(() => {
                        delete button.dataset.triggeredByCoordinator;
                    }, 100);
                }
            });
        }
    };

    // Find all language buttons
    const languageButtons = document.querySelectorAll('button[x-on\\:click*="-tab"]');

    // Attach click listeners to all language buttons
    languageButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            // Only proceed if this wasn't triggered by our coordinator
            if (button.dataset.triggeredByCoordinator === 'true') {
                return;
            }

            // Extract the ISO code from the x-on:click attribute
            const clickAttr = button.getAttribute('x-on:click');
            if (!clickAttr) return;

            const tabMatch = clickAttr.match(/tab = ['"](-([a-z]{2})-tab)['"]/);
            if (!tabMatch) return;

            const isoCode = tabMatch[2];

            // Synchronize all buttons for this language
            window.languageSelectionCoordinator.synchronizeLanguage(isoCode);
        }, { capture: true }); // Use capture to run before Alpine's handlers
    });
});
